<?php

namespace app\common\service\message;

use app\common\model\settings\Setting as SettingModel;
use app\common\enum\order\OrderTypeEnum;
use app\common\model\settings\MessageSettings as MessageSettingsModel;
use app\common\model\settings\Message as MessageModel;

/**
 * 消息通知服务
 */
class MessageService
{
    /**
     * 订单支付成功后通知
     */
    public function payment($order, $orderType = OrderTypeEnum::MASTER)
    {
        $message = MessageModel::detailByEname('order_pay_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id'], $order['app_id']);
        if (!$settings) {
            return;
        }
        $data = [
            // 商家名称
            'title' => $order['supplier']['name'],
            // 订单编号
            'order_no' => $order['order_no'],
            // 商品名称
            'product_name' => mb_substr($this->formatProductName($order['product']), 0, 20, 'utf-8'),
            // 订单金额
            'pay_price' => $order['pay_price'],
            // 支付时间
            'pay_time' => date('Y-m-d H:i:s', $order['pay_time'])
        ];
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $order['user']['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $order['user']['open_id'], $order['app_id']);
        }
    }


    /**
     * 后台配送通知
     */
    public function delivery($order, $orderType = OrderTypeEnum::MASTER)
    {
        $message = MessageModel::detailByEname('order_delivery_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id'], $order['app_id']);
        if (!$settings) {
            return;
        }
        $data = [
            // 订单编号
            'title' => $order['supplier']['name'],
            // 订单编号
            'order_no' => $order['order_no'],
            // 商品信息
            'product_name' => $this->formatProductName($order['product']),
            //订单状态
            'status' => '正在配送中',
            // 物流单号
            'remark' => '您的订单已配送，请注意查收',
        ];
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $order['user']['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $order['user']['open_id'], $order['app_id']);
        }
    }

    /**
     * 取消订单通知
     */
    public function cancel($order, $cancel_remark)
    {
        $message = MessageModel::detailByEname('order_refund_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id'], $order['app_id']);
        if (!$settings) {
            return;
        }
        $data = [
            // 订单编号
            'title' => $order['supplier']['name'],
            // 订单编号
            'order_no' => $order['order_no'],
            // 商品信息
            'product_name' => $this->formatProductName($order['product']),
            //订单状态
            'status' => '订单已取消',
            // 取消原因
            'remark' => $cancel_remark,
        ];
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $order['user']['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $order['user']['open_id'], $order['app_id']);
        }
    }

    /**
     * 格式化商品名称
     */
    private function formatProductName($productData)
    {
        $str = '';
        foreach ($productData as $product) {
            $str .= $product['product_name'] . ' ';
        }
        return $str;
    }

}