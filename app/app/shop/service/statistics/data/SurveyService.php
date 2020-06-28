<?php

namespace app\shop\service\statistics\data;

use app\common\library\helper;
use app\shop\model\user\User as UserModel;
use app\shop\model\order\Order as OrderModel;
use app\shop\model\product\Product as ProductModel;
use app\common\enum\order\OrderStatusEnum;
use app\common\enum\order\OrderPayStatusEnum;

/**
 * 数据概况
 */
class SurveyService
{
    /**
     * 获取数据概况
     */
    public function getSurveyData($startDate = null, $endDate = null)
    {
        return [
            // 用户数量
            'user_total' => $this->getUserTotal($startDate, $endDate),
            // 消费人数
            'consume_users' => $this->getConsumeUsers($startDate, $endDate),
            // 付款订单数
            'order_total' => $this->getOrderTotal($startDate, $endDate),
            // 付款订单总额
            'order_total_money' => $this->getOrderTotalMoney($startDate, $endDate),
            // 商品总量
            'product_total' => $this->getProductTotal($startDate, $endDate),
        ];
    }

    /**
     * 获取用户总量
     */
    private function getUserTotal($startDate = null, $endDate = null)
    {
        $model = new UserModel;
        if (!is_null($startDate) && !is_null($endDate)) {
            $model = $model->where('create_time', '>=', strtotime($startDate))
                ->where('create_time', '<', strtotime($endDate) + 86400);
        }
        $value = $model->where('is_delete', '=', '0')->count();
        return number_format($value);
    }

    /**
     * 消费人数
     */
    public function getConsumeUsers($startDate = null, $endDate = null)
    {
        $model = new OrderModel;
        if (!is_null($startDate) && !is_null($endDate)) {
            $model = $model->where('pay_time', '>=', strtotime($startDate))
                ->where('pay_time', '<', strtotime($endDate) + 86400);
        }
        $value = $model->field('user_id')
            ->where('pay_status', '=', OrderPayStatusEnum::SUCCESS)
            ->where('order_status', '<>', OrderStatusEnum::CANCELLED)
            ->where('is_delete', '=', '0')
            ->group('user_id')
            ->count();
        return number_format($value);
    }

    /**
     * 获取订单总量
     */
    private function getOrderTotal($startDate = null, $endDate = null)
    {
        return number_format((new OrderModel)->getPayOrderTotal($startDate, $endDate));
    }

    /**
     * 付款订单总额
     */
    private function getOrderTotalMoney($startDate = null, $endDate = null)
    {
        return helper::number2((new OrderModel)->getOrderTotalPrice($startDate, $endDate));
    }

    /**
     * 获取商品总量
     */
    private function getProductTotal($startDate = null, $endDate = null)
    {
        $model = new ProductModel;
        if (!is_null($startDate) && !is_null($endDate)) {
            $model = $model->where('create_time', '>=', strtotime($startDate))
                ->where('create_time', '<', strtotime($endDate) + 86400);
        }
        $value = $model->where('is_delete', '=', 0)->count();
        return number_format($value);
    }
}