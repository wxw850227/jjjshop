<?php

namespace app\shop\controller\order;

use app\shop\controller\Controller;
use app\shop\model\order\Order as OrderModel;
use app\shop\model\store\Store as StoreModel;
use app\common\enum\settings\DeliveryTypeEnum;
use app\shop\model\settings\Express as ExpressModel;
use app\shop\model\store\Clerk as ShopClerkModel;

/**
 * 订单控制器
 */
class Order extends Controller
{
    /**
     * 订单列表
     */
    public function lists($type = 'all')
    {
        // 订单列表
        $model = new OrderModel();
        $list = $model->getList($type, $this->postData());
        $order_count = [
            'order_count' => [
                'payment' => $model->getCount('payment'),
                'delivery' => $model->getCount('delivery'),
                'received' => $model->getCount('received'),
            ],
        ];
        return $this->renderSuccess('', compact('list', 'order_count'));
    }

    /**
     * 订单详情
     */
    public function detail($order_id)
    {
        // 订单详情
        $detail = OrderModel::detail($order_id);
        if (isset($detail['pay_time']) && $detail['pay_time'] != '') {
            $detail['pay_time'] = date('Y-m-d H:i:s', $detail['pay_time']);
        }
        if (isset($detail['delivery_time']) && $detail['delivery_time'] != '') {
            $detail['delivery_time'] = date('Y-m-d H:i:s', $detail['delivery_time']);
        }
        // 物流公司列表
        $model = new ExpressModel();
        $expressList = $model->getAll();
        return $this->renderSuccess('', compact('detail', 'expressList'));
    }

    /**
     * 确认发货
     */
    public function delivery()
    {
        $order_id = $this->postData('order_id');
        $model = OrderModel::detail($order_id);
        if ($model->delivery($this->postData('param'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError('发货失败');
    }

    /**
     * 修改订单价格
     */
    public function updatePrice($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->updatePrice($this->postData('order'))) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

}