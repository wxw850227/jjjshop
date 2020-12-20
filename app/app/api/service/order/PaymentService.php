<?php

namespace app\api\service\order;

use app\common\library\easywechat\AppMp;
use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\WxPay;
use app\common\enum\order\OrderTypeEnum;

class PaymentService
{

    /**
     * 构建微信支付
     */
    public static function wechat(
        $user,
        $orderNo,
        $payPrice,
        $orderType = OrderTypeEnum::MASTER,
        $pay_source
    )
    {
        // 统一下单API
        if($pay_source == 'wx'){
            $app = AppWx::getWxPayApp($user['app_id']);
            $open_id = $user['open_id'];
        }else if($pay_source == 'mp'){
            $app = AppMp::getWxPayApp($user['app_id']);
            $open_id = $user['mpopen_id'];
        }

        $WxPay = new WxPay($app);
        $payment = $WxPay->unifiedorder($orderNo, $open_id, $payPrice, $orderType, $pay_source);
        if($pay_source == 'wx'){
            return $payment;
        }else if($pay_source == 'mp'){
            $jssdk = $app->jssdk;
            return $jssdk->bridgeConfig($payment['prepay_id']);
        }
    }

}