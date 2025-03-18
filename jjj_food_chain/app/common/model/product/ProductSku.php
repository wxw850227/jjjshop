<?php

namespace app\common\model\product;

use app\common\model\BaseModel;
use think\db\Where;

/**
 * 商品SKU模型
 */
class ProductSku extends BaseModel
{
    protected $name = 'product_sku';
    protected $pk = 'product_sku_id';

    /**
     * 隐藏字段
     */
    protected $hidden = [
        'create_time',
        'update_time',
    ];

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

    /**
     * 获取sku信息详情
     */
    public static function getDetailSku($productId, $productSkuId)
    {
        return static::where('product_id', '=', $productId)
            ->where('product_sku_id', '=', $productSkuId)->find();
    }

}
