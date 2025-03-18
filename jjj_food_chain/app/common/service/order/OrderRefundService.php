<?php

namespace app\common\service\order;

use app\common\model\user\User as UserModel;
use app\common\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\enum\user\balanceLog\BalanceLogSceneEnum;
use app\common\library\easywechat\WxPay;
use app\common\library\easywechat\AppWx;

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
        $pay_type = $order['pay_type']['value'];
        if ($pay_type != OrderPayTypeEnum::BALANCE && $order['balance'] > 0) {
            if ($order['refund_money'] < $order['balance']) {
                if ($order['refund_money'] + $money > $order['balance']) {
                    $balance = round($order['balance'] - $order['refund_money'], 2);//余额退款金额
                    $money = round($money - $balance, 2);
                    $this->balance($order, $balance);
                } else {
                    $pay_type = 10;
                }
            }
        }
        if ($money <= 0) {
            return true;
        }
        // 1.微信支付退款
        if ($pay_type == OrderPayTypeEnum::WECHAT) {
            return $this->wxpay($order, $money);
        }
        // 2.余额支付退款
        if ($pay_type == OrderPayTypeEnum::BALANCE) {
            return $this->balance($order, $money);
        }
        return false;
    }

    /**
     * 余额支付退款
     */
    private function balance(&$order, $money)
    {
        // 回退用户余额
        $user = UserModel::detail($order['user_id']);
        $user->where('user_id', '=', $order['user_id'])->inc('balance', $money)->update();
        // 记录余额明细
        BalanceLogModel::add(BalanceLogSceneEnum::REFUND, [
            'user_id' => $user['user_id'],
            'money' => $money,
            'app_id' => $order['app_id'],
        ], ['order_no' => $order['order_no']]);
        return true;
    }

    /**
     * 微信支付退款
     */
    private function wxpay(&$order, $money)
    {
        if ($order['pay_source'] == 'wx') {
            $app = AppWx::getWxPayApp($order['app_id']);
        }
        $WxPay = new WxPay($app);
        return $WxPay->refund($order['transaction_id'], $order['online_money'], $money);
    }
}