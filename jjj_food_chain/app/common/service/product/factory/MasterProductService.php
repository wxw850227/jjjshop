<?php

namespace app\common\service\product\factory;

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
        $productData = [];
        $productSkuData = [];
        foreach ($productList as $product) {
            // 下单减库存
            if ($product['product']['deduct_stock_type'] == DeductStockTypeEnum::CREATE) {
                // 总库存
                $productData[] = [
                    'data' => ['product_stock' => ['dec', $product['product_num']]],
                    'where' => [
                        'product_id' => $product['product_id'],
                    ],
                ];
                $productSkuData[] = [
                    'data' => ['stock_num' => ['dec', $product['product_num']]],
                    'where' => [
                        'product_id' => $product['product_id'],
                        'product_sku_id' => $product['product_sku_id'],
                    ],
                ];
            }
        }
        // 更新商品销量
        !empty($productData) && $this->updateProduct($productData);
        try {
            // 更新商品sku库存
            !empty($productSkuData) && $this->updateProductSku($productSkuData);
        } catch (\Exception $e) {
            log_write('master updateProductStock' . $e->getMessage());
        }
        return true;
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
            $product_data = [
                'data' => ['sales_actual' => ['inc', $product['total_num']]],
                'where' => [
                    'product_id' => $product['product_id']
                ],
            ];
            // 付款减库存
            if ($product['deduct_stock_type'] == DeductStockTypeEnum::PAYMENT) {
                //总库存
                $product_data['data']['product_stock'] = ['dec', $product['total_num']];
                //sku库存
                $productSkuData[] = [
                    'data' => ['stock_num' => ['dec', $product['total_num']]],
                    'where' => [
                        'product_id' => $product['product_id'],
                        'product_sku_id' => $product['product_sku_id'],
                    ],
                ];
            }
            $productData[] = $product_data;
        }
        // 更新商品销量
        !empty($productData) && $this->updateProduct($productData);
        try {
            // 更新商品sku库存
            !empty($productSkuData) && $this->updateProductSku($productSkuData);
        } catch (\Exception $e) {
            log_write('master updateStockSales' . $e->getMessage());
        }
        return true;
    }

    /**
     * 回退商品库存
     */
    public function backProductStock($productList, $isPayOrder = false)
    {
        $productData = [];
        $productSkuData = [];
        foreach ($productList as $product) {
            $product_item = [
                'where' => [
                    'product_id' => $product['product_id'],
                ],
                'data' => ['product_stock' => ['inc', $product['total_num']]],
            ];
            $sku_item = [
                'where' => [
                    'product_id' => $product['product_id'],
                    'product_sku_id' => $product['product_sku_id'],
                ],
                'data' => ['stock_num' => ['inc', $product['total_num']]],
            ];
            if ($isPayOrder == true) {
                // 付款订单全部库存
                $productData[] = $product_item;
                $productSkuData[] = $sku_item;
            } else {
                // 未付款订单，判断必须为下单减库存时才回退
                $product['deduct_stock_type'] == DeductStockTypeEnum::CREATE && $productData[] = $product_item;
                $product['deduct_stock_type'] == DeductStockTypeEnum::CREATE && $productSkuData[] = $sku_item;
            }
        }
        // 更新商品库存
        !empty($productData) && $this->updateProduct($productData);
        // 更新商品sku库存
        !empty($productSkuData) && $this->updateProductSku($productSkuData);
        return true;
    }

    /**
     * 更新商品信息
     */
    private function updateProduct($data)
    {
        return (new ProductModel)->updateAll($data);
    }

    /**
     * 更新商品sku信息
     */
    private function updateProductSku($data)
    {
        return (new ProductSkuModel)->updateAll($data);
    }
}