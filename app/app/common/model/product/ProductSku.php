<?php

namespace app\common\model\product;

use app\common\model\BaseModel;
use think\db\Where;

/**
 * 商品SKU模型
 */
class ProductSku extends BaseModel
{
    //表名
    protected $name = 'product_sku';

    /**
     * 规格图片
     */
    public function image()
    {
        return $this->hasOne('app\\common\\model\\file\\UploadFile', 'file_id', 'image_id');
    }

    /**
     * 获取sku信息详情
     */
    public static function detail($productId, $specSkuId)
    {
        return static::where('product_id', '=', $productId)
        ->where('spec_sku_id', '=', $specSkuId)->find();
    }

}
