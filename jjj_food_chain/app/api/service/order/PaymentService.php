<?php

namespace app\api\service\order;

use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\WxPay;

class PaymentService
{
    /**
     * 构建微信支付
     */
    public static function wechat(
        $user,
        $order,
        $orderType,
        $pay_source
    )
    {
        // 统一下单API
        if ($pay_source == 'wx') {
            $app = AppWx::getWxPayApp($user['app_id']);
            $open_id = $user['open_id'];
        }
        $orderNo = $order['trade_no'];
        $payPrice = $order['online_money'];

        $WxPay = new WxPay($app);
        $payment = $WxPay->unifiedorder($orderNo, $open_id, $payPrice, $orderType, $pay_source, $user['app_id']);
        return $payment;
    }

}