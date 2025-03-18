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
    // 模板消息
    const TPL_MSG = 'tplMsg';
    // 上传设置
    const STORAGE = 'storage';
    // 小票打印
    const PRINTER = 'printer';
    // 系统配置
    const SYS_CONFIG = 'sys_config';
    // 配送设置
    const DELIVER = 'deliver';
    // 地图设置
    const MAP = 'map';
    // 用户协议
    const SERVICE = 'service';
    // 隐私协议
    const PRIVACY = 'privacy';

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
            self::TPL_MSG => [
                'value' => self::TPL_MSG,
                'describe' => '模板消息',
            ],
            self::STORAGE => [
                'value' => self::STORAGE,
                'describe' => '上传设置',
            ],
            self::PRINTER => [
                'value' => self::PRINTER,
                'describe' => '小票打印',
            ],
            self::SYS_CONFIG => [
                'value' => self::SYS_CONFIG,
                'describe' => '系统设置',
            ],
            self::DELIVER => [
                'value' => self::DELIVER,
                'describe' => '配送设置',
            ],
            self::MAP => [
                'value' => self::MAP,
                'describe' => '地图设置',
            ],
            self::SERVICE => [
                'value' => self::SERVICE,
                'describe' => '用户协议',
            ],
            self::PRIVACY => [
                'value' => self::PRIVACY,
                'describe' => '隐私协议',
            ],
        ];
    }

}