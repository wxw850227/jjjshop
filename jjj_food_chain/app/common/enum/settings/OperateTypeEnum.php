<?php

namespace app\common\enum\settings;

use MyCLabs\Enum\Enum;
/**
 * 运营模式枚举类
 */
class OperateTypeEnum extends Enum
{
    // B2B2C
    const B2B2C = 1;
    // B2B
    const B2B = 2;

    /**
     * 获取枚举数据
     */
    public static function data()
    {
        return [
            self::B2B2C => [
                'name' => 'B2B2C',
                'value' => self::B2B2C,
            ],
            self::B2B => [
                'name' => 'B2B',
                'value' => self::B2B,
            ],
        ];
    }

}