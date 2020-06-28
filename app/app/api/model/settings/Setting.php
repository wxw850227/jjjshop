<?php

namespace app\api\model\settings;

use app\common\model\settings\Setting as SettingModel;

/**
 * 设置模型
 */
class Setting extends SettingModel
{
    /**
     * 获取积分名称
     */
    public static function getPointsName()
    {
        return static::getItem('points')['points_name'];
    }

    /**
     * 获取积分名称
     */
    public static function getBargain()
    {
        return static::getItem('bargain');
    }
}