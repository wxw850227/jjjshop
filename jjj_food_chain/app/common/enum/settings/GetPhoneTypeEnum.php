<?php

namespace app\common\enum\settings;

use MyCLabs\Enum\Enum;
/**
 * 配送方式枚举类
 */
class GetPhoneTypeEnum extends Enum
{
    // 个人中心
    const USER = 10;

    /**
     * 获取枚举数据
     */
    public static function data()
    {
        return [
            self::USER => [
                'name' => '个人中心',
                'value' => self::USER,
            ],
        ];
    }

}