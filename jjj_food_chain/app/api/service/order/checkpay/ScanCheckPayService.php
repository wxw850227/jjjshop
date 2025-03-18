<?php

namespace app\api\service\order\checkpay;

use app\api\model\product\ProductSku as ProductSkuModel;
use app\common\enum\product\DeductStockTypeEnum;

/**
 * 主订单支付检查服务类
 */
class ScanCheckPayService extends CheckPayService
{
    /**
     * 判断订单是否允许付款
     */
    public function checkOrderStatus($order)
    {
        // 判断订单状态
        if (!$this->checkOrderStatusCommon($order)) {
            return false;
        }
        // 判断商品状态、库存
        if (!$this->checkProductStatus($order['product'])) {
            return false;
        }
        return true;
    }

    /**
     * 判断商品状态、库存 (未付款订单)
     */
    protected function checkProductStatus($productList)
    {
        return true;
    }

}