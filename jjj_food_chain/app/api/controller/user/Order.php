<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\order\Order as OrderModel;
use app\api\model\settings\Setting as SettingModel;
use app\common\enum\order\OrderTypeEnum;

/**
 * 我的订单
 */
class Order extends Controller
{
    // user
    private $user;

    /**
     * 构造方法
     */
    public function initialize()
    {
        parent::initialize();
        $this->user = $this->getUser();   // 用户信息

    }

    /**
     * 我的订单列表
     */
    public function lists()
    {
        $data = $this->postData();
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $data);
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 订单详情信息
     */
    public function detail($order_id)
    {
        // 订单详情
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 剩余支付时间
        if ($model['pay_status']['value'] == 10 && $model['order_status']['value'] != 20) {
            $model['pay_end_time'] = $this->formatPayEndTime($model['pay_end_time'] - time());
        } else {
            $model['pay_end_time'] = '';
        }
        return $this->renderSuccess('', [
            'order' => $model,  // 订单详情
        ]);
    }

    /**
     * 支付成功详情信息
     */
    public function paySuccess($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        $order['pay_price'] = $model['pay_price'];
        $order['points_bonus'] = $model['points_bonus'];
        $order['callNo'] = $model['callNo'];
        return $this->renderSuccess('', compact('order'));
    }

    /**
     * 取消订单
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel($this->user)) {
            return $this->renderSuccess('订单取消成功');
        }
        return $this->renderError($model->getError() ?: '订单取消失败');
    }

    /**
     * 立即支付
     */
    public function pay($order_id)
    {
        // 获取订单详情
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        $params = $this->postData();
        if ($this->request->isGet()) {
            // 支付金额
            $payPrice = $model['pay_price'];
            $payTypes = SettingModel::getPayType($params['pay_source']);
            $balance = $this->user['balance'];
            return $this->renderSuccess('', compact('payPrice', 'payTypes', 'balance'));
        }
        // 订单支付事件
        if (!$model->onPay($this->user)) {
            return $this->renderError($model->getError() ?: '订单支付失败');
        }
        $OrderModel = new OrderModel;
        // 构建微信支付请求
        $payInfo = $OrderModel->OrderPay($params, $model, $this->getUser());
        if (!$payInfo) {
            return $this->renderError($OrderModel->getError() ?: '订单支付失败');
        }
        // 支付状态提醒
        return $this->renderSuccess('', [
            'order_id' => $order_id,   // 订单id
            'pay_type' => $payInfo['payType'],  // 支付方式
            'payment' => $payInfo['payment'],   // 微信支付参数
            'order_type' => OrderTypeEnum::MASTER, //订单类型
            'return_Url' => $params['pay_source'] == 'h5' ? urlencode(base_url() . "h5/pages/user/index/index") : '', //h5支付跳转地址
        ]);
    }

    /**
     * 确认完成订单
     */
    public function receipt($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->receipt()) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    private function formatPayEndTime($leftTime)
    {
        if ($leftTime <= 0) {
            return '';
        }
        $str = '';
        $day = floor($leftTime / 86400);
        $hour = floor(($leftTime - $day * 86400) / 3600);
        $min = floor((($leftTime - $day * 86400) - $hour * 3600) / 60);

        if ($day > 0) $str .= $day . '天';
        if ($hour > 0) $str .= $hour . '小时';
        if ($min > 0) $str .= $min . '分钟';
        return $str;
    }
}