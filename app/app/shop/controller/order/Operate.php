<?php

namespace app\shop\controller\order;

use app\shop\controller\Controller;
use app\shop\model\order\Order as OrderModel;
use app\common\model\settings\Express as ExpressModel;

class Operate extends Controller
{
    // 模型
    private $model;

    /**
     * 构造方法
     */
    public function initialize()
    {
        $this->model = new OrderModel;
    }

    /**
     * 审核：用户取消订单
     */
    public function confirmCancel($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->confirmCancel($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError('操作失败');
    }


}