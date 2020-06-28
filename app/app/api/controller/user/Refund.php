<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\settings\Express as ExpressModel;
use app\api\model\order\OrderProduct as OrderProductModel;
use app\api\model\order\OrderRefund as OrderRefundModel;

/**
 * 订单售后服务
 */
class Refund extends Controller
{
    // 当前用户
    private $user;

    /**
     * 构造方法
     */
    public function initialize()
    {
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 用户售后单列表
     */
    public function lists($state = -1)
    {
        $model = new OrderRefundModel;
        $list = $model->getList($this->user['user_id'], $state, $this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 申请售后
     */
    public function apply($order_product_id)
    {
        // 订单商品详情
        $product = OrderProductModel::detail($order_product_id);
        if (isset($product['refund']) && !empty($product['refund'])) {
            return $this->renderError('当前商品已申请售后');
        }
        if (!$this->request->isPost()) {
            return $this->renderSuccess('', ['detail' => $product]);
        }
        // 新增售后单记录
        $model = new OrderRefundModel;
        if ($model->apply($this->user, $product, $this->request->post())) {
            return $this->renderSuccess('提交成功');
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

    /**
     * 售后单详情
     */
    public function detail($order_refund_id)
    {
        // 售后单详情
        $detail = OrderRefundModel::detail([
            'user_id' => $this->user['user_id'],
            'order_refund_id' => $order_refund_id
        ]);
        if (empty($detail)) {
            return $this->renderError('售后单不存在');
        }
        // 物流公司列表
        $model = new ExpressModel();
        $expressList = $model->getAll();
        return $this->renderSuccess('', compact('detail', 'expressList'));
    }

    /**
     * 用户发货
     */
    public function delivery($order_refund_id)
    {
        // 售后单详情
        $model = OrderRefundModel::detail([
            'user_id' => $this->user['user_id'],
            'order_refund_id' => $order_refund_id
        ]);
        if ($model->delivery($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

}