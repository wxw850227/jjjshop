<?php

namespace app\shop\model\order;

use app\common\model\order\OrderRefund as OrderRefundModel;
use app\shop\model\order\OrderRefundAddress;
use app\shop\model\user\User as UserModel;
use app\common\service\order\OrderRefundService;
use app\common\service\message\MessageService;
use app\common\enum\order\OrderTypeEnum;

/**
 * 售后管理模型
 */
class OrderRefund extends OrderRefundModel
{
    /**
     * 获取售后单列表
     */
    public function getList($params = [])
    {
        $model = $this;
        // 查询条件：订单号
        if (isset($params['order_no']) && !empty($params['order_no'])) {
            $model = $model->where('order.order_no', 'like', '%' . trim($params['order_no']) . '%');
        }
        // 查询条件：起始日期
        if (isset($params['create_time']) && !empty($params['create_time'])) {
            $sta_time = array_shift($params['create_time']);
            $end_time = array_pop($params['create_time']);
            $model = $model->whereBetweenTime('m.create_time', $sta_time, $end_time);
        }
        // 售后类型
        if (isset($params['type']) && $params['type'] > 0) {
            $model = $model->where('m.type', '=', $params['type']);
        }

        // 售后单状态(选项卡)
        if (isset($params['status']) && $params['status'] >= 0) {
            $model = $model->where('m.status', '=', $params['status']);
        }

        // 获取列表数据
        return $model->alias('m')
            ->field('m.*, order.order_no')
            ->with(['orderProduct.image', 'orderMaster', 'user'])
            ->join('order', 'order.order_id = m.order_id')
            ->order(['m.create_time' => 'desc'])
            ->paginate($params, false, [
                'query' => \request()->request()
            ]);
    }

    public function getRefundCount($params, $type = 'wait'){
        $model = $this;
        // 查询条件：订单号
        if (isset($params['order_no']) && !empty($params['order_no'])) {
            $model = $model->where('order.order_no', 'like', '%' . trim($params['order_no']) . '%');
        }
        // 查询条件：起始日期
        if (isset($params['create_time']) && !empty($params['create_time'])) {
            $sta_time = array_shift($params['create_time']);
            $end_time = array_pop($params['create_time']);
            $model = $model->whereBetweenTime('m.create_time', $sta_time, $end_time);
        }
        // 售后类型
        if (isset($params['type']) && $params['type'] > 0) {
            $model = $model->where('m.type', '=', $params['type']);
        }

        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'wait';
                $filter['m.status'] = 0;
                break;
        }

        // 获取列表数据
        return $model->alias('m')
            ->join('order', 'order.order_id = m.order_id')
            ->where($filter)
            ->count();
    }

    /**
     * 商家审核
     */
    public function audit($data)
    {
        if ($data['is_agree'] == 20 && empty($data['refuse_desc'])) {
            $this->error = '请输入拒绝原因';
            return false;
        }
        if ($data['is_agree'] == 10 && empty($data['address_id'])) {
            $this->error = '请选择退货地址';
            return false;
        }
        $this->startTrans();
        try {
            // 拒绝申请, 标记售后单状态为已拒绝
            $data['is_agree'] == 20 && $data['status'] = 10;
            // 同意换货申请, 标记售后单状态为已完成
            $data['is_agree'] == 10 && $this['type']['value'] == 20 && $data['status'] = 20;
            // 更新退款单状态
            $this->save($data);
            // 同意售后申请, 记录退货地址
            if ($data['is_agree'] == 10) {
                $model = new OrderRefundAddress();
                $model->add($this['order_refund_id'], $data['address_id']);
            }
            // 订单详情
            $order = Order::detail($this['order_id']);
            // 发送模板消息
            (new MessageService)->refund(self::detail($this['order_refund_id']), $order['order_no'], OrderTypeEnum::MASTER);
            // 事务提交
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 确认收货并退款
     */
    public function receipt($data)
    {
        // 订单详情
        $order = Order::detail($this['order_id']);
//        if ($data['refund_money'] > min($order['pay_price'], $this['order_product']['total_pay_price'])) {
        if ($data['refund_money'] > $order['pay_price']) {
            $this->error = '退款金额不能大于商品实付款金额';
            return false;
        }
        $this->transaction(function () use ($order, $data) {
            // 更新售后单状态
            $this->save([
                'refund_money' => $data['refund_money'],
                'is_receipt' => 1,
                'status' => 20
            ]);
            // 消减用户的实际消费金额
            // 条件：判断订单是否已结算
            if ($order['is_settled'] == true) {
                (new UserModel)->setDecUserExpend($order['user_id'], $data['refund_money']);
            }
            // 执行原路退款
            (new OrderRefundService)->execute($order, $data['refund_money']);
            // 发送模板消息
            (new MessageService)->refund(self::detail($this['order_refund_id']), $order['order_no'], OrderTypeEnum::MASTER);
        });
        return true;
    }


    /**
     * 统计售后订单
     */
    public function getRefundOrderTotal()
    {
        $filter['is_agree'] = 0;
        return $this->where($filter)->count();
    }
}