<?php

namespace app\shop\model\order;

use app\common\model\order\OrderDeliver as OrderDeliverModel;
use app\shop\service\order\ExportService;
use app\common\service\order\OrderCompleteService;
use app\common\enum\order\OrderTypeEnum;
use app\common\service\deliveryapi\DadaApi;
use app\common\service\deliveryapi\MeTuanApi;
use app\common\service\deliveryapi\UuApi;

/**
 * 订单模型
 */
class OrderDeliver extends OrderDeliverModel
{
    /**
     * 订单列表
     */
    public function getList($data)
    {
        $model = $this;
        // 检索查询条件
        $model = $model->setWhere($model, $data);
        // 获取数据列表
        return $model->with(['orders.address', 'supplier'])
            ->order(['create_time' => 'desc'])
            ->paginate($data);
    }

    /**
     * 订单列表(全部)
     */
    public function getListAll($query = [])
    {
        $model = $this;
        // 检索查询条件
        $model = $model->setWhere($model, $query);
        // 获取数据列表
        return $model->with(['orders', 'supplier'])
            ->alias('order')
            ->field('order.*')
            ->order(['order.create_time' => 'desc'])
            ->select();
    }

    /**
     * 订单导出
     */
    public function exportList($query)
    {
        // 获取订单列表
        $list = $this->getListAll($query);
        // 导出excel文件
        return (new Exportservice)->deliverList($list);
    }

    /**
     * 设置检索查询条件
     */
    private function setWhere($model, $data)
    {
        //搜索订单号
        if (isset($data['order_no']) && $data['order_no'] != '') {
            $model = $model->where('order_no', 'like', '%' . trim($data['order_no']) . '%');
        }
        //配送方式
        if (isset($data['deliver_source']) && $data['deliver_source']) {
            $model = $model->where('deliver_source', '=', $data['deliver_source']);
        }
        //配送状态
        if (isset($data['deliver_status']) && $data['deliver_status']) {
            $model = $model->where('deliver_status', '=', $data['deliver_status']);
        }
        //订单状态
        if (isset($data['status']) && $data['status']) {
            $model = $model->where('status', '=', $data['status']);
        }
        //商家查询
        if (isset($data['shop_supplier_id']) && $data['shop_supplier_id']) {
            $model = $model->where('shop_supplier_id', '=', $data['shop_supplier_id']);
        }
        //搜索时间段
        if (isset($data['create_time']) && $data['create_time'] != '') {
            $model = $model->where('create_time', 'between', [strtotime($data['create_time'][0]), strtotime($data['create_time'][1]) + 86399]);
        }
        return $model;
    }

    /**
     * 取消配送
     */
    public function cancelDeliver($data)
    {
        // 判断订单是否有效
        if ($this['status'] != 10 || $this['deliver_status'] == 5 || $this['deliver_status'] == 4) {
            $this->error = '该订单不合法';
            return false;
        }
        if ($this['deliver_source'] == 20) {
            $result = (new DadaApi($this['shop_supplier_id']))->formalCancel($this['order_id'], $data['cancel_reason']);
            if ($result['status'] == 'fail') {
                $this->error = $result['msg'];
                return false;
            } else {
                //更新配送状态
                (new Order())->where('order_id', '=', $this['order_id'])->update(['deliver_status' => 0, 'delivery_status' => 10]);
                return $this->DeliverCancel();
            }
        }
        if ($this['deliver_source'] == 40) {
            $order = $this['orders'];
            $order['cancel_reason'] = $data['cancel_reason'];
            $result = (new MeTuanApi($order['shop_supplier_id'], $this['app_id']))->delete($order);
            if ($result['code'] != 0) {
                $this->error = $result['message'];
                return false;
            } else {
                //更新配送状态
                (new Order())->where('order_id', '=', $this['order_id'])->update(['deliver_status' => 0, 'delivery_status' => 10]);
                return $this->DeliverCancel();
            }
        }
        if ($this['deliver_source'] == 50) {
            $order = $this;
            $order['cancel_reason'] = $data['cancel_reason'];
            $result = (new UuApi($order['shop_supplier_id'], $this['app_id']))->cancelorder($order);
            if ($result['return_code'] != 'ok') {
                $this->error = $result['return_msg'];
                return false;
            } else {
                //更新配送状态
                (new Order())->where('order_id', '=', $this['order_id'])->update(['deliver_status' => 0, 'delivery_status' => 10]);
                return $this->DeliverCancel();
            }
        }
    }

    /**
     * 确认送达（配送订单）
     * @param $extractClerkId
     * @return bool|mixed
     */
    public function verify()
    {
        if (in_array($this['orders']['order_status']['value'], [20, 30]) || $this['status'] != 10) {
            $this->error = '该订单不满足确认条件';
            return false;
        }
        return $this->transaction(function () {
            $order = (new Order)::detail($this['order_id']);
            //更新配送状态
            $this->updateDeliver();
            // 更新订单状态：已发货、已收货
            $order->save([
                'delivery_status' => 20,
                'delivery_time' => time(),
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 执行订单完成后的操作
            $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
            $OrderCompleteService->complete([$order], static::$app_id);
            return true;
        });
    }
}