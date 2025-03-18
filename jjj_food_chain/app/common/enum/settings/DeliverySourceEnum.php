<?php

namespace app\common\enum\settings;

use MyCLabs\Enum\Enum;

/**
 * 配送方式枚举类
 */
class DeliverySourceEnum extends Enum
{
    // 商家配送
    const SELLER = 10;
    // 达达配送
    const DADA = 20;
    // 配送员配送
    const DRIVER = 30;
    // 美团配送
    const MEITUAN = 40;
    // uu跑腿
    const UU = 50;

    /**
     * 获取枚举数据
     */
    public static function data()
    {
        return [
            self::SELLER => [
                'name' => '商家配送',
                'value' => self::SELLER,
            ],
            self::DADA => [
                'name' => '达达配送',
                'value' => self::DADA,
            ],
            self::DRIVER => [
                'name' => '配送员配送',
                'value' => self::DRIVER,
            ],
            self::MEITUAN => [
                'name' => '美团配送',
                'value' => self::MEITUAN,
            ],
            self::UU => [
                'name' => 'uu跑腿',
                'value' => self::UU,
            ],
        ];
    }
}