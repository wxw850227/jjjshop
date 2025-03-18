<?php

namespace app\common\enum\order;

use MyCLabs\Enum\Enum;

/**
 * 订单来源
 */
class OrderSourceEnum extends Enum
{
    // 普通订单
    const MASTER = 10;
    // 扫码点餐
    const SCAN = 30;

    /**
     * 获取枚举数据
     */
    public static function data()
    {
        return [
            self::MASTER => [
                'name' => '用户下单',
                'value' => self::MASTER,
            ],
            self::SCAN => [
                'name' => '扫码下单',
                'value' => self::SCAN,
            ],
        ];
    }

}