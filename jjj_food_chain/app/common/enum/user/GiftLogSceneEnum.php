<?php

namespace app\common\enum\user;

use MyCLabs\Enum\Enum;

/**
 * 礼物币变动场景枚举类
 */
class GiftLogSceneEnum extends Enum
{
    // 用户充值
    const RECHARGE = 10;

    // 用户消费
    const CONSUME = 20;

    /**
     * 获取订单类型值
     */
    public static function data()
    {
        return [
            self::RECHARGE => [
                'name' => '用户充值',
                'value' => self::RECHARGE,
                'describe' => '用户充值：%s',
            ],
            self::CONSUME => [
                'name' => '用户消费',
                'value' => self::CONSUME,
                'describe' => '用户消费：%s',
            ],
        ];
    }

}