<?php

namespace app\shop\controller\takeout;

use app\shop\controller\Controller;
use app\shop\model\order\OrderDeliver as OrderDeliverModel;
use app\common\enum\settings\DeliverySourceEnum;
use app\shop\model\order\Order as OrderModel;

/**
 * 订单控制器
 */
class Deliver extends Controller
{
    /**
     * 订单列表
     */
    public function index()
    {
        // 订单列表
        $model = new OrderDeliverModel();
        $data = $this->postData();
        $data['shop_supplier_id'] = $this->store['user']['shop_supplier_id'];
        $list = $model->getList($data);
        $deliver_source = DeliverySourceEnum::data();
        return $this->renderSuccess('', compact('list', 'deliver_source'));
    }

    /**
     * 订单详情
     */
    public function detail($deliver_id)
    {
        $deliver = OrderDeliverModel::detail($deliver_id);
        // 订单详情
        $detail = OrderModel::detail($deliver['order_id']);
        if (isset($detail['pay_time']) && $detail['pay_time'] != '') {
            $detail['pay_time'] = date('Y-m-d H:i:s', $detail['pay_time']);
        }
        if (isset($detail['delivery_time']) && $detail['delivery_time'] != '') {
            $detail['delivery_time'] = date('Y-m-d H:i:s', $detail['delivery_time']);
        }
        if (isset($deliver['deliver_time']) && $deliver['deliver_time'] != '') {
            $deliver['deliver_time'] = date('Y-m-d H:i:s', $deliver['deliver_time']);
        }
        $detail['buy_remark'] = $detail['buy_remark'] ? json_decode($detail['buy_remark'], 1) : '';
        return $this->renderSuccess('', compact('detail', 'deliver'));
    }

    /**
     * 取消配送
     */
    public function cancel($deliver_id)
    {
        $model = OrderDeliverModel::detail($deliver_id);
        if ($model->cancelDeliver($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 确认送达
     */
    public function verify($deliver_id)
    {
        $model = OrderDeliverModel::detail($deliver_id);
        if ($model->verify()) {
            return $this->renderSuccess('核销成功');
        }
        return $this->renderError($model->getError() ?: '核销失败');
    }

    /**
     * 订单导出
     */
    public function export()
    {
        $model = new OrderDeliverModel();
        return $model->exportList($this->postData());
    }
}