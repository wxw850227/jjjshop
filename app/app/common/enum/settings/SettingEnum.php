<?php

namespace app\common\enum\settings;

use MyCLabs\Enum\Enum;

/**
 * 商城设置枚举类
 */
class SettingEnum extends Enum
{
    // 商城设置
    const STORE = 'store';

    // 交易设置
    const TRADE = 'trade';

    // 上传设置
    const STORAGE = 'storage';

    // 短信通知
    const SMS = 'sms';

    // 引导收藏
    const COLLECTION = 'collection';

    /**
     * 获取订单类型值
     */
    public static function data()
    {
        return [
            self::STORE => [
                'value' => self::STORE,
                'describe' => '商城设置',
            ],
            self::TRADE => [
                'value' => self::TRADE,
                'describe' => '交易设置',
            ],
            self::STORAGE => [
                'value' => self::STORAGE,
                'describe' => '上传设置',
            ],
            self::SMS => [
                'value' => self::SMS,
                'describe' => '短信通知',
            ],
            self::COLLECTION => [
                'value' => self::COLLECTION,
                'describe' => '引导收藏',
            ],
        ];
    }

}