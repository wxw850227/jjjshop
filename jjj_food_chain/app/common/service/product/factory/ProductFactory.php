<?php

namespace app\common\service\product\factory;

use app\common\enum\order\OrderSourceEnum;

/**
 * 商品辅助工厂类
 */
class ProductFactory
{
    public static function getFactory($type = OrderSourceEnum::MASTER)
    {
        switch ($type) {
            case OrderSourceEnum::MASTER:
                return new MasterProductService();
                break;
            case OrderSourceEnum::SCAN;
                return new MasterProductService();
                break;
        }
    }
}