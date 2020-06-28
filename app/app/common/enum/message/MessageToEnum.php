<?php

namespace app\common\enum\message;

use MyCLabs\Enum\Enum;

/**
 * 消息发送对象枚举类,方便后续扩展
 */
class MessageToEnum extends Enum
{
    // 会员
    const MEMBER = 10;

    // 商家
    const SHOP = 20;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data()
    {
        return [
            self::MEMBER => [
                'value' => self::MEMBER,
                'name' => '会员',
            ],
            self::SHOP => [
                'value' => self::SHOP,
                'name' => '商家',
            ],
        ];
    }

}