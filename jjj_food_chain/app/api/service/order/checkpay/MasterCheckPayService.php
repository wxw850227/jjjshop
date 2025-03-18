<?php

namespace app\api\service\order\checkpay;

use app\api\model\product\ProductSku as ProductSkuModel;
use app\common\enum\product\DeductStockTypeEnum;

/**
 * 主订单支付检查服务类
 */
class MasterCheckPayService extends CheckPayService
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
        foreach ($productList as $product) {
            // 判断商品是否下架
            if (
                empty($product['product'])
                || $product['product']['product_status']['value'] != 10
            ) {
                $this->error = "很抱歉，商品 [{$product['product_name']}] 已下架";
                return false;
            }
            // 获取商品的sku信息
            $productSku = $this->getOrderProductSku($product['product_id'], $product['product_sku_id']);
            // sku已不存在
            if (empty($productSku)) {
                $this->error = "很抱歉，商品 [{$product['product_name']}] sku已不存在，请重新下单";
                return false;
            }
            !isset($productData[$product['product_id']]) && $productData[$product['product_id']] = 0;
            $productData[$product['product_id']] += $product['total_num'];
            // 付款减库存
            if ($product['deduct_stock_type'] == DeductStockTypeEnum::PAYMENT && $productData[$product['product_id']] > $productSku['stock_num']) {
                $this->error = "很抱歉，商品 [{$product['product_name']}] 库存不足";
                return false;
            }
        }
        return true;
    }

    /**
     * 获取指定的商品sku信息
     */
    private function getOrderProductSku($productId, $productSkuId)
    {
        return ProductSkuModel::getDetailSku($productId, $productSkuId);
    }

}