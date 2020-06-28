<?php

namespace app\api\service\order;

use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\WxPay;
use app\common\enum\order\OrderTypeEnum;

class PaymentService
{
    /**
     * 构建订单支付参数
     */
    public static function orderPayment($user, $order)
    {
        return self::wechat(
            $user,
            $order['order_no'],
            $order['pay_price'],
            OrderTypeEnum::MASTER
        );
    }

    /**
     * 构建微信支付
     */
    public static function wechat(
        $user,
        $orderNo,
        $payPrice,
        $orderType = OrderTypeEnum::MASTER
    )
    {
        // 统一下单API
        $app = AppWx::getWxPayApp($user['app_id']);
        $open_id = $user['open_id'];

        $WxPay = new WxPay($app);
        $payment = $WxPay->unifiedorder($orderNo, $open_id, $payPrice, $orderType);
        return $payment;
    }

}