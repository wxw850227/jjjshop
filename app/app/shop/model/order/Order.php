<?php

namespace app\shop\model\order;

use app\common\model\order\Order as OrderModel;
use app\common\library\helper;
use app\common\enum\order\OrderTypeEnum;
use app\common\service\message\MessageService;
use app\common\service\order\OrderRefundService;
use app\common\enum\order\OrderPayStatusEnum;
use app\common\service\product\factory\MasterProductService;
use app\common\enum\settings\DeliveryTypeEnum;

/**
 * 订单模型
 */
class Order extends OrderModel
{
    /**
     * 订单列表
     */
    public function getList($type, $params = null)
    {
        $model = $this;
        //搜索订单号
        if (isset($params['order_no']) && $params['order_no'] != '') {
            $model = $model->where('order_no', 'like', '%' . trim($params['order_no']) . '%');
        }
        //搜索时间段
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $sta_time = array_shift($params['create_time']);
            $end_time = array_pop($params['create_time']);
            $model = $model->whereBetweenTime('create_time', $sta_time, $end_time);
        }
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = OrderPayStatusEnum::PENDING;
                $filter['order_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = OrderPayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 10;
                $filter['order_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = OrderPayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                $filter['order_status'] = 10;
                break;
            case 'comment';
                $filter['is_comment'] = 0;
                $filter['order_status'] = 30;
                break;
            case 'complete';
                $filter['is_comment'] = 1;
                $filter['order_status'] = 30;
                break;
        }
        // 获取数据列表
        return $model->with(['product.image', 'user'])
            ->order(['create_time' => 'desc'])
            ->where($filter)
            ->paginate($params, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 获取订单总数
     */
    public function getCount($type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = OrderPayStatusEnum::PENDING;
                $filter['order_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = OrderPayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 10;
                $filter['order_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = OrderPayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                $filter['order_status'] = 10;
                break;

        }
        return $this->where($filter)->count();
    }

    /**
     * 订单列表(全部)
     */
    public function getListAll($dataType, $query = [])
    {
        // 检索查询条件
        !empty($query) && $this->setWhere($query);
        // 获取数据列表
        return $this->with(['product.image', 'address', 'user', 'extract', 'extract_shop'])
            ->alias('order')
            ->field('order.*')
            ->join('user', 'user.user_id = order.user_id')
            ->where($this->transferDataType($dataType))
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->select();
    }

    /**
     * 设置检索查询条件
     */
    private function setWhere($query)
    {
        if (isset($query['search']) && !empty($query['search'])) {
            $this->where('order_no|user.nickName', 'like', '%' . trim($query['search']) . '%');
        }
        if (isset($query['start_time']) && !empty($query['start_time'])) {
            $this->where('order.create_time', '>=', strtotime($query['start_time']));
        }
        if (isset($query['end_time']) && !empty($query['end_time'])) {
            $this->where('order.create_time', '<', strtotime($query['end_time']) + 86400);
        }
        if (isset($query['delivery_type']) && !empty($query['delivery_type'])) {
            $query['delivery_type'] > -1 && $this->where('delivery_type', '=', $query['delivery_type']);
        }
        if (isset($query['extract_shop_id']) && !empty($query['extract_shop_id'])) {
            $query['extract_shop_id'] > -1 && $this->where('extract_shop_id', '=', $query['extract_shop_id']);
        }
        // 用户id
        if (isset($query['user_id']) && $query['user_id'] > 0) {
            $this->where('order.user_id', '=', (int)$query['user_id']);
        }
    }

    /**
     * 转义数据类型条件
     */
    private function transferDataType($dataType)
    {
        // 数据类型
        $filter = [];
        switch ($dataType) {
            case 'delivery':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 10,
                    'order_status' => ['in', [10, 21]]
                ];
                break;
            case 'receipt':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 20,
                    'receipt_status' => 10
                ];
                break;
            case 'pay':
                $filter = ['pay_status' => 10, 'order_status' => 10];
                break;
            case 'complete':
                $filter = ['order_status' => 30];
                break;
            case 'cancel':
                $filter = ['order_status' => 20];
                break;
            case 'all':
                $filter = [];
                break;
        }
        return $filter;
    }

    /**
     * 确认发货(单独订单)
     */
    public function delivery($data)
    {
        // 转义为订单列表
        $orderList = [$this];
        // 验证订单是否满足发货条件
        if (!$this->verifyDelivery($orderList)) {
            return false;
        }
        // 整理更新的数据
        $updateList = [[
            'order_id' => $this['order_id'],
            'express_id' => $data['express_id'],
            'express_no' => $data['express_no']
        ]];
        // 更新订单发货状态
        if ($status = $this->updateToDelivery($updateList)) {
            // 获取已发货的订单
            $completed = self::detail($this['order_id'], ['user', 'address', 'product', 'express']);
            // 发送消息通知
            $this->sendDeliveryMessage([$completed]);
        }
        return $status;
    }

    /**
     * 确认发货后发送消息通知
     */
    private function sendDeliveryMessage($orderList)
    {
        // 实例化消息通知服务类
        $Service = new MessageService;
        foreach ($orderList as $item) {
            // 发送消息通知
            $Service->delivery($item, OrderTypeEnum::MASTER);
        }
        return true;
    }

    /**
     * 更新订单发货状态(批量)
     */
    private function updateToDelivery($orderList)
    {
        $data = [];
        foreach ($orderList as $item) {
            $data[] = [
                'order_id' => $item['order_id'],
                'express_no' => $item['express_no'],
                'express_id' => $item['express_id'],
                'delivery_status' => 20,
                'delivery_time' => time(),
            ];
        }
        return $this->saveAll($data);
    }

    /**
     * 验证订单是否满足发货条件
     */
    private function verifyDelivery($orderList)
    {
        foreach ($orderList as $order) {
            if (
               $order['pay_status']['value'] != 20
                || $order['delivery_type']['value'] != DeliveryTypeEnum::EXPRESS
                || $order['delivery_status']['value'] != 10
            ) {
                $this->error = "订单号[{$order['order_no']}] 不满足发货条件!";
                return false;
            }
        }
        return true;
    }

    /**
     * 修改订单价格
     */
    public function updatePrice($data)
    {
        if ($this['pay_status']['value'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        if ($this['order_source'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        // 实际付款金额
        $payPrice = bcadd($data['update_price'], $data['update_express_price'], 2);
        if ($payPrice <= 0) {
            $this->error = '订单实付款价格不能为0.00元';
            return false;
        }
        return $this->save([
                'order_no' => $this->orderNo(), // 修改订单号, 否则微信支付提示重复
                'order_price' => $data['update_price'],
                'pay_price' => $payPrice,
                'update_price' => helper::bcsub($data['update_price'], helper::bcsub($this['total_price'], $this['coupon_money'])),
                'express_price' => $data['update_express_price']
            ]) !== false;
    }

    /**
     * 审核：用户取消订单
     */
    public function confirmCancel($data)
    {
        // 判断订单是否有效
        if ($this['pay_status']['value'] != 20) {
            $this->error = '该订单不合法';
            return false;
        }
        // 订单取消事件
        $status = $this->transaction(function () use ($data) {
            if ($data['is_cancel'] == 2) {
                // 执行退款操作
                (new OrderRefundService)->execute($this);
                // 回退商品库存
                (new MasterProductService())->backProductStock($this['product'], true);
            }
            // 更新订单状态
            return $this->save(['order_status' => $data['is_cancel'] ? 20 : 10]);
        });
        return $status;
    }

    /**
     * 获取已付款订单总数 (可指定某天)
     */
    public function getPayOrderTotal($startDate = null, $endDate = null)
    {
        $model = $this;

        $model = $model->where('pay_time', '>=', strtotime($startDate));
        if(is_null($endDate)){
            $model = $model->where('pay_time', '<', strtotime($startDate) + 86400);
        }else{
            $model = $model->where('pay_time', '<', strtotime($endDate) + 86400);
        }

        return $model->where('is_delete', '=', 0)
            ->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->count();
    }

    /**
     * 获取待处理订单
     */
    public function getReviewOrderTotal()
    {
        $filter['pay_status'] = OrderPayStatusEnum::SUCCESS;
        $filter['delivery_status'] = 10;
        $filter['order_status'] = 10;
        return $this->where($filter)->count();
    }

    /**
     * 获取订单总数量
     */
    public function getOrderTotal($filter = [], $startDate = null, $endDate = null)

    {
        $model = $this;
        if (empty($filter)) {
            $model = $model->where($filter);
        }
        //搜索时间段
        if ($startDate !== null && $endDate !== null) {
            $model = $model->whereBetweenTime('create_time', $startDate, $endDate);
        }
        return $model->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 获取某天的总销售额
     */
    public function getOrderTotalPrice($startDate = null, $endDate = null)
    {
        $model = $this;
        $model = $model->where('pay_time', '>=', strtotime($startDate));

        if (is_null($endDate)) {
            $model = $model->where('pay_time', '<', strtotime($startDate) + 86400);
        } else {
            $model = $model->where('pay_time', '<', strtotime($endDate) + 86400);
        }

        return $model->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->where('is_delete', '=', 0)
            ->sum('pay_price');
    }

    /**
     * 获取某天的下单用户数
     */
    public function getPayOrderUserTotal($day)
    {
        $startTime = strtotime($day);
        $userIds = $this->distinct(true)
            ->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', 20)
            ->where('is_delete', '=', 0)
            ->column('user_id');
        return count($userIds);
    }

    /**
     * 获取兑换记录
     * @param $param array
     * @return \think\Paginator
     */
    public function getExchange($param)
    {
        $model = $this;
        if (isset($param['order_status']) && $param['order_status'] > -1) {
            $model = $model->where('order.order_status', '=', $param['order_status']);
        }
        if (isset($param['nickName']) && !empty($param['nickName'])) {
            $model = $model->where('user.nickName', 'like', '%' . trim($param['nickName']) . '%');
        }

        return $model->with(['user'])->alias('order')
            ->join('user', 'user.user_id = order.user_id')
            ->where('order.order_source', '=', 20)
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->paginate($param, false, [
                'query' => request()->request()
            ]);


    }

}