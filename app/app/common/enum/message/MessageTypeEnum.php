<?php

namespace app\common\enum\message;

use MyCLabs\Enum\Enum;

/**
 * 消息类型枚举类,方便后续扩展
 */
class MessageTypeEnum extends Enum
{
    // 订单
    const ORDER = 10;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data()
    {
        return [
            self::ORDER => [
                'value' => self::ORDER,
                'name' => '订单',
            ],
        ];
    }

}