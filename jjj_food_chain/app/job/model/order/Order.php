<?php

namespace app\job\model\order;

use app\common\model\order\Order as OrderModel;
use app\api\model\supplier\Supplier as SupplierModel;
use app\common\model\settings\Setting as SettingModel;
use app\common\service\message\MessageService;

/**
 * 订单模型
 */
class Order extends OrderModel
{
    /**
     * 获取订单列表
     */
    public function getCloseList($with, $app_id)
    {
        return $this->with($with)
            ->where('pay_status', '=', 10)
            ->where('order_status', '=', 10)
            ->where('pay_end_time', '<=', time())
            ->where('pay_end_time', '>', 0)
            ->where('is_delete', '=', 0)
            ->where('app_id', '=', $app_id)
            ->select();
    }


    /**
     * 获取订单列表
     */
    public function getReceiveList($orderIds, $with = [])
    {
        return $this->with($with)
            ->where('order_id', 'in', $orderIds)
            ->select();
    }

    /**
     * 获取订单列表
     */
    public function getSettledList($deadlineTime, $with, $app_id)
    {
        return $this->with($with)
            ->where('order_status', '=', 30)
            ->where('receipt_time', '<=', $deadlineTime)
            ->where('is_settled', '=', 0)
            ->where('app_id', '=', $app_id)
            ->select();
    }

    /**
     * 获取外卖未配送订单列表
     */
    public function getDeliverList($app_id)
    {
        $supplierList = SupplierModel::where('app_id', '=', $app_id)
            ->where('is_delete', '=', 0)
            ->where('status', '=', 0)
            ->where('is_recycle', '=', 0)
            ->select();
        $supplierId = [];
        foreach ($supplierList as $key => $item) {
            $deliver = SettingModel::getSupplierItem('deliver', $item['shop_supplier_id'], $app_id)['default'];
            if ($deliver == 'local') {
                $supplierId[] = $item['shop_supplier_id'];
            }
        }
        if ($supplierId) {
            return $this->where('pay_status', '=', 20)
                ->where('order_status', '=', 10)
                ->where('delivery_status', '=', 10)
                ->where('deliver_status', '=', 0)
                ->where('app_id', '=', $app_id)
                ->where('shop_supplier_id', 'in', $supplierId)
                ->column('order_id');
        }
        return [];
    }

    /**
     * 获取外卖未配送订单列表
     */
    public function sellerDeliver($data, $app_id)
    {
        $deliver = SettingModel::getSupplierItem('deliver', $data['shop_supplier_id'], $app_id);
        if ($deliver['default'] == 'local' && $deliver['engine']['local']['time'] > 0) {
            if ($data['pay_time'] + $deliver['engine']['local']['time'] * 60 < time()) {
                $this->startTrans();
                try {
                    $this->sendLocal($data);
                    $Service = new MessageService;
                    // 发送消息通知
                    $Service->delivery($data);
                    $this->commit();
                    return true;
                } catch (\Exception $e) {
                    $this->error = $e->getMessage();
                    $this->rollback();
                    return false;
                }
            }
            return false;
        } else {
            return false;
        }
    }
}
