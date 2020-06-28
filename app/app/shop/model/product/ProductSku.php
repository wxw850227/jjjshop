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
    public function addSkuList($product_id, $spec_list)
    {
        $data = [];
        foreach ($spec_list as $item) {
            $data[] = array_merge($item['spec_form'], [
                'spec_sku_id' => $item['spec_sku_id'],
                'product_id' => $product_id,
                'app_id' => self::$app_id,
            ]);
        }
        return $this->saveAll($data);
    }

    /**
     * 添加商品规格关系记录
     */
    public function addProductSpecRel($product_id, $spec_attr)
    {
        $data = [];
        array_map(function ($val) use (&$data, $product_id) {
            array_map(function ($item) use (&$val, &$data, $product_id) {
                $data[] = [
                    'product_id' => $product_id,
                    'spec_id' => $val['group_id'],
                    'spec_value_id' => $item['item_id'],
                    'app_id' => self::$app_id,
                ];
            }, $val['spec_items']);
        }, $spec_attr);
        $model = new ProductSpecRel;
        return $model->saveAll($data);
    }

    /**
     * 移除指定商品的所有sku
     */
    public function removeAll($product_id)
    {
        $model = new ProductSpecRel;
        $model->where('product_id','=', $product_id)->delete();
        return $this->where('product_id','=', $product_id)->delete();
    }

}
