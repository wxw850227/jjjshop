<?php

namespace app\common\service\order;

use app\common\model\user\User as UserModel;
use app\common\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\enum\user\balanceLog\BalanceLogSceneEnum;
use app\common\library\easywechat\WxPay;
use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\AppMp;

/**
 * 订单退款服务类
 */
class OrderRefundService
{
    /**
     * 执行订单退款
     */
    public function execute(&$order, $money = null)
    {
        // 退款金额，如不指定则默认为订单实付款金额
        is_null($money) && $money = $order['pay_price'];
        // 1.微信支付退款
        if ($order['pay_type']['value'] == OrderPayTypeEnum::WECHAT) {
            return $this->wxpay($order, $money);
        }
        return false;
    }


    /**
     * 微信支付退款
     */
    private function wxpay(&$order, $money)
    {
        if($order['pay_source'] == 'mp'){
            $app = AppMp::getWxPayApp($order['app_id']);
        }else if($order['pay_source'] == 'wx'){
            $app = AppWx::getWxPayApp($order['app_id']);
        }
        $WxPay = new WxPay($app);
        return $WxPay->refund($order['transaction_id'], $order['pay_price'], $money);
    }

}