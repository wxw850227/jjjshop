<?php

namespace app\common\service\product;

use app\common\library\helper;
use app\common\model\product\Product as ProductModel;

/**
 * 商品服务类,公共处理方法
 */
class BaseProductService
{
    /**
     * 商品多规格信息
     */
    public static function getSpecData($model = null)
    {
        // 商品sku数据
        if (!is_null($model) && $model['spec_type'] == 20) {
            return $model->getManySpecData($model['spec_rel'], $model['sku']);
        }
        return null;
    }

}