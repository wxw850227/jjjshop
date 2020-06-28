<?php

namespace app\api\controller\order;

use app\api\model\order\Cart as CartModel;
use app\api\model\order\Order as OrderModel;
use app\api\service\order\settled\MasterOrderSettledService;
use app\api\controller\Controller;
use app\api\model\settings\Message as MessageModel;

/**
 * 普通订单
 */
class Order extends Controller
{
    /**
     * 订单确认-立即购买
     */
    public function buy()
    {
        // 立即购买：获取订单商品列表
        $params = $this->request->param();
        $productList = OrderModel::getOrderProductListByNow($params);
        $user = $this->getUser();
        // 实例化订单service
        $orderService = new MasterOrderSettledService($user, $productList, $params);
        // 获取订单信息
        $orderInfo = $orderService->settlement();
        if ($this->request->isGet()) {
            return $this->renderSuccess('', $orderInfo);
        }
        // 订单结算提交
        if ($orderService->hasError()) {
            return $this->renderError($orderService->getError());
        }
        // 创建订单
        $order_id = $orderService->createOrder($orderInfo);
        if(!$order_id){
            return $this->renderError($orderService->getError() ?: '订单创建失败');
        }
        // 构建支付请求
        $payment = OrderModel::onOrderPayment($user, $orderService->model, $params['pay_type'], $params['pay_source']);
        // 如果来源是小程序, 则获取小程序订阅消息id.获取支付成功,发货通知.
        $template_arr = [];

        $template_list = MessageModel::getMessageByNameArr(['order_pay_user']);
        foreach($template_list as $template){
            $json = json_decode($template['wx_template'], true);
            array_push($template_arr, $json['template_id']);
        }
        // 返回结算信息
        return $this->renderSuccess(['success' => '支付成功', 'error' => '订单未支付'], [
            'order_id' => $order_id,   // 订单id
            'pay_type' => $params['pay_type'],  // 支付方式
            'payment' => $payment,               // 微信支付参数
            'template_arr' => $template_arr,    // 小程序申请的消息模板id
        ]);
    }

    /**
     * 订单确认-立即购买
     */
    public function cart()
    {
        // 立即购买：获取订单商品列表
        $params = $this->postData();
        $user = $this->getUser();
        // 商品结算信息
        $CartModel = new CartModel($user);
        // 购物车商品列表
        $productList = $CartModel->getList($params['cart_ids']);
        // 实例化订单service
        $orderService = new MasterOrderSettledService($user, $productList, $params);
        // 获取订单信息
        $orderInfo = $orderService->settlement();
        if ($this->request->isGet()) {
            return $this->renderSuccess('', $orderInfo);
        }
        // 订单结算提交
        if ($orderService->hasError()) {
            return $this->renderError($orderService->getError());
        }
        // 创建订单
        $order_id = $orderService->createOrder($orderInfo);
        if(!$order_id){
            return $this->renderError($orderService->getError() ?: '订单创建失败');
        }
        // 移出购物车中已下单的商品
        $CartModel->clearAll($params['cart_ids']);
        // 构建支付请求
        $payment = OrderModel::onOrderPayment($user, $orderService->model, $params['pay_type'], $params['pay_source']);
        // 获取小程序订阅消息id.获取支付成功,发货通知.
        $template_arr = [];
        $template_list = MessageModel::getMessageByNameArr(['order_pay_user']);
        foreach($template_list as $template){
            $json = json_decode($template['wx_template'], true);
            array_push($template_arr, $json['template_id']);
        }
        // 返回结算信息
        return $this->renderSuccess('', [
            'order_id' => $orderService->model['order_id'],   // 订单id
            'pay_type' => $params['pay_type'],  // 支付方式
            'payment' => $payment               // 微信支付参数
        ], ['success' => '支付成功', 'error' => '订单未支付']);
    }
}