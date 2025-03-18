<?php

namespace app\shop\controller\takeout;

use app\shop\controller\Controller;
use app\shop\model\order\Order as OrderModel;

/**
 * 订单操作
 * @package app\shop\controller\order
 */
class Operate extends Controller
{
    /**
     * 订单导出
     */
    public function export($dataType)
    {
        $model = new OrderModel();
        $data = $this->postData();
        $data['order_type'] = 0;
        return $model->exportList($dataType, $data);
    }

    /**
     * 取消订单
     */
    public function orderCancel($order_id)
    {
        // 订单信息
        $model = OrderModel::detail($order_id);
        if ($model->orderCancel($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 门店核销
     */
    public function extract($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->verificationOrder()) {
            return $this->renderSuccess('核销成功');
        }
        return $this->renderError($model->getError() ?: '核销失败');
    }

    /**
     * 退款
     */
    public function refund($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->refund($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 订单配送
     */
    public function sendOrder($order_id)
    {
        // 订单详情
        $model = OrderModel::detail($order_id);
        if ($model->sendOrder($order_id)) {
            return $this->renderSuccess('发送成功');
        }
        return $this->renderError($model->getError() ?: '发送失败');
    }

}