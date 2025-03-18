<?php

namespace app\shop\model\product;

use app\common\model\product\ProductSku as ProductSkuModel;

/**
 * 商品规格模型
 */
class ProductSku extends ProductSkuModel
{
    /**
     * 批量添加商品sku记录
     */
    public function addSkuList($product_id, $sku_list, $productSkuIdList)
    {
        $updateData = [];
        $saveData = [];
        foreach ($sku_list as $item) {
            $data = $item;
            $data['product_id'] = $product_id;
            $data['line_price'] = $item['product_price'];
            $data['base_stock_num'] = $item['stock_num'];
            $data['app_id'] = self::$app_id;

            if (isset($item['product_sku_id'])&&$item['product_sku_id'] > 0) {
                $index = 0;
                foreach ($productSkuIdList as $skuId) {
                    if ($skuId == $item['product_sku_id']) {
                        array_splice($productSkuIdList, $index, 1);
                        break;
                    }
                    $index++;
                }
                $updateData[] = [
                    'data' => $data,
                    'where' => [
                        'product_sku_id' => $item['product_sku_id'],
                    ],
                ];
            } else {
                $saveData[] = $data;
            }
        }
        count($updateData) > 0 && $this->updateAll($updateData);
        count($saveData) > 0 && $this->saveAll($saveData);
        count($productSkuIdList) > 0 && $this->where('product_sku_id', 'in', $productSkuIdList)->delete();
    }

    /**
     * 添加商品规格关系记录
     */
    public function addProductSpecRel($product_id, $spec_attr)
    {
        $data = [];
        $model = new ProductSpecRel;

        array_map(function ($val) use (&$data, $product_id, $model) {
            array_map(function ($item) use (&$val, &$data, $product_id, $model) {
                $detail = $model->where('product_id', '=', $product_id)
                    ->where('spec_id', '=', $val['group_id'])
                    ->where('spec_value_id', '=', $item['item_id'])->find();
                if (!$detail) {
                    $data[] = [
                        'product_id' => $product_id,
                        'spec_id' => $val['group_id'],
                        'spec_value_id' => $item['item_id'],
                        'app_id' => self::$app_id,
                    ];
                }
            }, $val['spec_items']);
        }, $spec_attr);

        count($data) > 0 && $model->saveAll($data);
    }

    /**
     * 移除指定商品的所有sku
     */
    public function removeAll($product_id)
    {
        $model = new ProductSpecRel;
        return $model->where('product_id', '=', $product_id)->delete();
    }

    /**
     * 移除指定商品的所有sku
     */
    public function removeSkuBySpec($product_id)
    {
        $model = new self;
        return $model->where('product_id', '=', $product_id)
            ->where('spec_sku_id', '<>', 0)
            ->delete();
    }
}
