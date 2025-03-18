<?php

namespace app\api\model\product;

use app\common\model\product\ProductSku as ProductSkuModel;

/**
 * 商品sku模型
 */
class ProductSku extends ProductSkuModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];

}