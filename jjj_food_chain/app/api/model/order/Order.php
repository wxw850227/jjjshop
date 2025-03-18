<?php

namespace app\api\model\order;

use app\api\service\order\paysuccess\type\MasterPaySuccessService;
use app\api\service\order\PaymentService;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\enum\order\OrderSourceEnum;
use app\common\enum\order\OrderTypeEnum;
use app\common\enum\order\OrderStatusEnum;
use app\common\exception\BaseException;
use app\common\model\order\Order as OrderModel;
use app\api\service\order\checkpay\CheckPayFactory;
use app\common\service\order\OrderCompleteService;

/**
 * 普通订单模型
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'update_time'
    ];

    /**
     * 订单支付事件
     */
    public function onPay($user)
    {
        // 判断订单状态
        $checkPay = CheckPayFactory::getFactory($this['order_source']);

        if (!$checkPay->checkOrderStatus($this)) {
            $this->error = $checkPay->getError();
            return false;
        }
        //判断是否为扫码点餐
        if ($this['order_source'] == OrderSourceEnum::SCAN) {
            $this->save(['user_id' => $user['user_id']]);
        }
        return true;
    }

    /**
     * 用户中心订单列表
     */
    public function getList($user_id, $params)
    {
        $model = $this;
        if (isset($params['shop_supplier_id']) && $params['shop_supplier_id']) {
            $model = $model->where('shop_supplier_id', '=', $params['shop_supplier_id']);
        }
        if (isset($params['order_type'])) {
            $model = $model->where('order_type', '=', $params['order_type']);
        }
        if (isset($params['delivery_type']) && $params['delivery_type']) {
            $model = $model->where('delivery_type', '=', $params['delivery_type']);
        }
        switch ($params['dataType']) {
            case '0'://全部订单
                break;
            case '1'://当前订单
                $model = $model->where('order_status', '=', 10);
                break;
            case '2'://历史订单
                $model = $model->where('order_status', '<>', 10);
                break;
        }
        $list = $model->with(['product.image', 'supplier'])
            ->where('user_id', '=', $user_id)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc'])
            ->paginate($params);
        foreach ($list as &$item) {
            $item['order_label'] = '';
            if ($item['order_type'] == 0 && $item['delivery_type']['value'] == 10) {
                $item['order_label'] = '外送';
            } elseif ($item['order_type'] == 0 && $item['delivery_type']['value'] == 20) {
                $item['order_label'] = '自提';
            } elseif ($item['order_type'] == 1 && $item['delivery_type']['value'] == 30) {
                $item['order_label'] = '打包';
            } elseif ($item['order_type'] == 1 && $item['delivery_type']['value'] == 40) {
                $item['order_label'] = '堂食';
            }
            $productNum = 0;
            //商品数量
            foreach ($item['product'] as $product) {
                $productNum += $product['total_num'];
            }
            $item['productNum'] = $productNum;
        }
        return $list;
    }

    /**
     * 取消订单
     */
    public function cancel($user)
    {
        if ($this['pay_status']['value'] == 20 || $this['order_status']['value'] != 10) {
            $this->error = '订单状态错误不允许取消!';
            return false;
        }
        return $this->save(['order_status' => OrderStatusEnum::CANCELLED]);
    }

    /**
     * 确认收货
     */
    public function receipt()
    {
        // 验证订单是否合法
        // 条件1: 订单必须已发货
        // 条件2: 订单必须未收货
        if ($this['delivery_status']['value'] != 20 || $this['receipt_status']['value'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->transaction(function () {
            // 更新订单状态
            $status = $this->save([
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 执行订单完成后的操作
            $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
            $OrderCompleteService->complete([$this], static::$app_id);
            return $status;
        });
    }

    /**
     * 订单详情
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        $model = new static();
        $order = $model->where(['order_id' => $order_id])->with(['product' => ['image'], 'address', 'supplier', 'user', 'deliver'])->find();
        if (empty($order)) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }

    /**
     * 余额支付标记订单已支付
     */
    public function onPaymentByBalance($orderNo)
    {
        // 获取订单详情
        $PaySuccess = new MasterPaySuccessService($orderNo);
        // 发起余额支付
        $status = $PaySuccess->onPaySuccess(OrderPayTypeEnum::BALANCE);
        if (!$status) {
            $this->error = $PaySuccess->getError();
        }
        return $status;
    }

    /**
     * 构建微信支付请求
     */
    protected static function onPaymentByWechat($user, $order, $pay_source)
    {
        return PaymentService::wechat(
            $user,
            $order,
            OrderTypeEnum::MASTER,
            $pay_source
        );
    }

    /**
     * 待支付订单详情
     */
    public static function getPayDetail($orderNo)
    {
        $model = new static();
        return $model->where(['trade_no' => $orderNo, 'pay_status' => 10, 'is_delete' => 0])->with(['product', 'user', 'supplier'])->find();
    }

    /**
     * 构建支付请求的参数
     */
    public static function onOrderPayment($user, $order, $payType, $pay_source)
    {
        if ($payType == OrderPayTypeEnum::WECHAT) {
            return self::onPaymentByWechat($user, $order, $pay_source);
        }
        if ($pay_source == 'h5') {
            return [];
        }
        return [];
    }

    /**
     * 设置错误信息
     */
    protected function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     */
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * 主订单购买的数量
     * 未取消的订单
     */
    public static function getHasBuyOrderNum($user_id, $product_id)
    {
        $model = new static();
        return $model->alias('order')->where('order.user_id', '=', $user_id)
            ->join('order_product', 'order_product.order_id = order.order_id', 'left')
            ->where('order_product.product_id', '=', $product_id)
            ->where('order.order_source', '=', OrderSourceEnum::MASTER)
            ->where('order.order_status', '<>', 20)
            ->sum('total_num');
    }

    //查询桌号订单信息
    public static function getOrderInfo($table_id)
    {
        return (new static())->with('product')
            ->where('table_id', '=', $table_id)
            ->where('pay_status', '=', 10)
            ->where('order_status', '=', 10)
            ->where('is_delete', '=', 0)
            ->find();
    }

    /**
     * 创建新订单
     */
    public function OrderPay($params, $order, $user)
    {
        $payType = $params['payType'];
        $payment = '';
        $online_money = 0;
        $order->save(['balance' => 0, 'online_money' => 0, 'trade_no' => $this->tradeNo()]);
        if ($order['pay_price'] == 0) {
            $params['use_balance'] = 1;
        }
        // 余额支付标记订单已支付
        if ($params['use_balance'] == 1) {
            if ($user['balance'] >= $order['pay_price']) {
                $payType = 10;
                $order->save(['balance' => $order['pay_price']]);
                $this->onPaymentByBalance($order['trade_no']);
            } else {
                if ($payType <= 10) {
                    $this->error = '用户余额不足';
                    return false;
                }
                $online_money = round($order['pay_price'] - $user['balance'], 2);
                $order->save(['balance' => $user['balance'], 'online_money' => $online_money]);
            }
        } else {
            if ($payType <= 10) {
                $this->error = '请选择支付方式';
                return false;
            }
            $online_money = $order['pay_price'];
            $order->save(['online_money' => $order['pay_price']]);
        }
        $online_money > 0 && $payment = self::onOrderPayment($user, $order, $payType, $params['pay_source']);

        $result['order_id'] = $order['order_id'];
        $result['payType'] = $payType;
        $result['payment'] = $payment;
        return $result;
    }
}