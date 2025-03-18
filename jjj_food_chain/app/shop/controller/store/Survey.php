<?php

namespace app\shop\controller\store;

use app\shop\controller\Controller;
use app\shop\model\order\Order as OrderModel;

/**
 * 店内概况控制器
 */
class Survey extends Controller
{
    /**
     * 店内概况列表
     */
    public function index()
    {
        // 订单列表
        $model = new OrderModel();
        $detail = $model->getOrderTotalMoney(1,$this->store['user']['shop_supplier_id']);
        // 销量排行
        $salesNumRank = $model->getProductRank(0,1,$this->store['user']['shop_supplier_id']);
        // 销售额排行
        $salesMoneyRank = $model->getProductRank(1,1,$this->store['user']['shop_supplier_id']);
        return $this->renderSuccess('', compact('detail','salesNumRank','salesMoneyRank'));
    }
}