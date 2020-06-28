<?php

namespace app\common\enum\order;

use MyCLabs\Enum\Enum;

/**
 * 订单类型枚举类,用于后期扩展，比如虚拟物品
 */
class OrderTypeEnum extends Enum
{
    // 实物订单
    const MASTER = 10;

    /**
     * 获取订单类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::MASTER => [
                'name' => '实物订单',
                'value' => self::MASTER,
            ]
        ];
    }

    /**
     * 获取订单类型名称
     * @return array
     */
    public static function getTypeName()
    {
        static $names = [];

        if (empty($names)) {
            foreach (self::data() as $item)
                $names[$item['value']] = $item['name'];
        }

        return $names;
    }

}