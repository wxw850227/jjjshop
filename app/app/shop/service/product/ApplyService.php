<?php

namespace app\shop\service\product;

use app\common\service\product\BaseProductService;

class ApplyService extends BaseProductService
{
    /**
     * 验证商品规格属性是否锁定
     */
    public static function checkSpecLocked($productId)
    {
        return true;
    }

    /**
     * 验证商品是否允许删除
     */
    public static function checkIsAllowDelete($productId)
    {
        return true;
    }


}