<?php

namespace app\api\model\settings;

use app\common\model\settings\Setting as SettingModel;
use app\common\model\app\App as AppModel;

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
     * 获取支付方式
     */
    public static function getPayType($pay_source)
    {
        $checkedPay = AppModel::detail()['pay_type'];
        sort($checkedPay);
        $use_balance = in_array(10, $checkedPay) ? 1 : 0;
        $payType = [];
        foreach ($checkedPay as $key => &$value) {
            if ($pay_source == 'h5') {
                break;
            }
            if ($value == 10) {
                unset($payType[$key]);
                continue;
            }
            $value = intval($value);
            $payType[] = $value;
        }

        $data = [
            'payType' => $payType,
            'use_balance' => $use_balance
        ];
        return $data;
    }
}