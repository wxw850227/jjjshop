<?php

namespace app\common\service\product\factory;

use app\common\service\product\factory\ProductService;
use app\common\model\product\Product as ProductModel;
use app\common\model\product\ProductSku as ProductSkuModel;
use app\common\enum\product\DeductStockTypeEnum;

/**
 * 商品来源-普通商品扩展类
 */
class MasterProductService extends ProductService
{
    /**
     * 更新商品库存 (针对下单减库存的商品)
     */
    public function updateProductStock($productList)
    {
        $data = [];
        foreach ($productList as $product) {
            // 下单减库存
            if ($product['deduct_stock_type'] == DeductStockTypeEnum::CREATE) {
                $data[] = [
                    'data' => ['stock_num' => ['dec', $product['total_num']]],
                    'where' => [
                        'product_id' => $product['product_id'],
                        'spec_sku_id' => $product['spec_sku_id'],
                    ],
                ];
            }
        }
        return !empty($data) && $this->updateProductSku($data);
    }

    /**
     * 更新商品库存销量（订单付款后）
     */
    public function updateStockSales($productList)
    {
        $productData = [];
        $productSkuData = [];
        foreach ($productList as $product) {
            // 记录商品的销量
            $productData[] = [
                'product_id' => $product['product_id'],
                'sales_actual' => ['inc', $product['total_num']]
            ];
            // 付款减库存
            if ($product['deduct_stock_type'] == DeductStockTypeEnum::PAYMENT) {
                $productSkuData[] = [
                    'data' => ['stock_num' => ['dec', $product['total_num']]],
                    'where' => [
                        'product_id' => $product['product_id'],
                        'spec_sku_id' => $product['spec_sku_id'],
                    ],
                ];
            }
        }
        // 更新商品销量
        !empty($productData) && $this->updateProduct($productData);
        // 更新商品sku库存
        !empty($productSkuData) && $this->updateProductSku($productSkuData);
        return true;
    }

    /**
     * 回退商品库存
     */
    public function backProductStock($productList, $isPayOrder = false)
    {
        $productSkuData = [];
        foreach ($productList as $product) {
            $item = [
                'where' => [
                    'product_id' => $product['product_id'],
                    'spec_sku_id' => $product['spec_sku_id'],
                ],
                'data' => ['stock_num' => ['inc', $product['total_num']]],
            ];
            if ($isPayOrder == true) {
                // 付款订单全部库存
                $productSkuData[] = $item;
            } else {
                // 未付款订单，判断必须为下单减库存时才回退
                $product['deduct_stock_type'] == DeductStockTypeEnum::CREATE && $productSkuData[] = $item;
            }
        }
        // 更新商品sku库存
        return !empty($productSkuData) && $this->updateProductSku($productSkuData);
    }

    /**
     * 更新商品信息
     */
    private function updateProduct($data)
    {
        return (new ProductModel)->saveAll($data);
    }

    /**
     * 更新商品sku信息
     */
    private function updateProductSku($data)
    {
        return (new ProductSkuModel)->updateAll($data);
    }
}