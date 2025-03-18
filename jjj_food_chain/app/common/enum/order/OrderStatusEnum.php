<?php
namespace app\common\enum\order;

use MyCLabs\Enum\Enum;

/**
 * 订单状态
 */
class OrderStatusEnum extends Enum
{
    // 进行中
    const NORMAL = 10;

    // 已取消
    const CANCELLED = 20;

    // 待取消
    const APPLY_CANCEL = 21;

    // 已完成
    const COMPLETED = 30;

    /**
     * 获取枚举数据
     */
    public static function data()
    {
        return [
            self::NORMAL => [
                'name' => '进行中',
                'value' => self::NORMAL,
            ],
            self::CANCELLED => [
                'name' => '已取消',
                'value' => self::CANCELLED,
            ],
            self::APPLY_CANCEL => [
                'name' => '待取消',
                'value' => self::APPLY_CANCEL,
            ],
            self::COMPLETED => [
                'name' => '已完成',
                'value' => self::COMPLETED,
            ],
        ];
    }

}