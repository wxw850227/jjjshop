<?php

namespace app\api\service\order\checkpay;

use app\common\enum\order\OrderPayStatusEnum;
use app\common\enum\order\OrderStatusEnum;
use app\common\service\BaseService;

/**
 * 订单支付检查服务类
 */
abstract class CheckPayService extends BaseService
{
    /**
     * 判断订单是否允许付款
     */
    abstract public function checkOrderStatus($order);

    /**
     * 判断商品状态、库存 (未付款订单)
     */
    abstract protected function checkProductStatus($product);

    /**
     * 判断订单状态(公共)
     */
    protected function checkOrderStatusCommon($order)
    {
        // 判断订单状态
        if (
            $order['order_status']['value'] != OrderStatusEnum::NORMAL
            || $order['pay_status']['value'] != OrderPayStatusEnum::PENDING
        ) {
            $this->error = '很抱歉，当前订单不合法，无法支付';
            return false;
        }
        return true;
    }

}