<?php

namespace app\shop\service;

use app\shop\model\product\Product;
use app\shop\model\order\Order;
use app\shop\model\user\User;

/**
 * 商城模型
 */
class ShopService
{
    // 商品模型
    private $ProductModel;
    // 订单模型
    private $OrderModel;
    // 用户模型
    private $UserModel;

    /**
     * 构造方法
     */
    public function __construct()
    {
        /* 初始化模型 */
        $this->ProductModel = new Product();
        $this->OrderModel = new Order();
        $this->UserModel = new User();
    }

    /**
     * 后台首页数据
     */
    public function getHomeData($user, $param)
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $month = date('Y-m-01');
        $monthEnd = date('Y-m-d H:i:s');
        $lastMonthStart = date('Y-m-01', strtotime('-1 month', time()));
        $lastMonthEnd = date('Y-m-t', strtotime('-1 month', time()));
        $shop_supplier_id = $user['shop_supplier_id'];
        $data = [
            'top_data' => [
                // 用户总量
                'user_total' => $this->getUserTotal(),
                // 用户今天总量
                'user_total_today' => $this->getUserTotal($today),
                // 用户昨日总量
                'user_total_yesterday' => $this->getUserTotal($yesterday),
                // 订单总量
                'order_total' => $this->getOrderTotal(null, $shop_supplier_id),
                // 订单今日总量
                'order_total_today' => $this->getOrderTotal($today, $shop_supplier_id),
                // 订单昨日总量
                'order_total_yesterday' => $this->getOrderTotal($yesterday, $shop_supplier_id),
                // 营业额
                'total_money' => $this->getOrderTotalPrice(null, 'order_total_price', $shop_supplier_id),
                // 营业额
                'total_money_today' => $this->getOrderTotalPrice($today, 'order_total_price', $shop_supplier_id),
                // 营业额
                'total_money_yesterday' => $this->getOrderTotalPrice($yesterday, 'order_total_price', $shop_supplier_id),
                // 总退款
                'refund_money' => $this->getOrderTotalPrice(null, 'order_refund_money', $shop_supplier_id),
                // 今日退款
                'refund_money_today' => $this->getOrderTotalPrice($today, 'order_refund_money', $shop_supplier_id),
                // 昨日退款
                'refund_money_yesterday' => $this->getOrderTotalPrice($yesterday, 'order_refund_money', $shop_supplier_id),
            ],
            'wait_data' => [
                //订单
                'order' => [
                    //外卖订单
                    'takeout' => $this->getReviewOrderTotal($shop_supplier_id, 0),
                    //店内订单
                    'store' => $this->getReviewOrderTotal($shop_supplier_id, 1),
                ],
                //库存
                'stock' => [
                    //外卖商品
                    'takeaway' => $this->getProductStockTotal($shop_supplier_id, 0),
                    //店内商品
                    'store' => $this->getProductStockTotal($shop_supplier_id, 1),
                ],
            ],
            //当月营业数据
            'month_data' => [
                //用户
                'user_total' => $this->UserModel->getUserData($month, $monthEnd, 'user_total'),
                //上月用户
                'user_total_last' => $this->UserModel->getUserData($lastMonthStart, $lastMonthEnd, 'user_total'),
                //营业额
                'total_money' => $this->OrderModel->getOrderData($month, $monthEnd, 'order_total_price', $shop_supplier_id),
                //上月营业额
                'total_money_last' => $this->OrderModel->getOrderData($lastMonthStart, $lastMonthEnd, 'order_total_price', $shop_supplier_id),
                //订单数
                'order_total' => $this->OrderModel->getOrderData($month, $monthEnd, 'order_total', $shop_supplier_id),
                //上月订单数
                'order_total_last' => $this->OrderModel->getOrderData($lastMonthStart, $lastMonthEnd, 'order_total', $shop_supplier_id),
                //退款数
                'refund_money' => $this->OrderModel->getOrderData($month, $monthEnd, 'order_refund_money', $shop_supplier_id),
                //上月退款数
                'refund_money_last' => $this->OrderModel->getOrderData($lastMonthStart, $lastMonthEnd, 'order_refund_money', $shop_supplier_id),
            ],
        ];
        $data['top_data']['user_rate'] = $data['top_data']['user_total_yesterday'] > 0 ? round(($data['top_data']['user_total_today'] - $data['top_data']['user_total_yesterday']) / $data['top_data']['user_total_yesterday'] * 100, 2) : round($data['top_data']['user_total_today'] * 100, 2);
        $data['top_data']['order_rate'] = $data['top_data']['order_total_yesterday'] > 0 ? round(($data['top_data']['order_total_today'] - $data['top_data']['order_total_yesterday']) / $data['top_data']['order_total_yesterday'] * 100, 2) : round($data['top_data']['order_total_today'] * 100, 2);
        $data['top_data']['total_rate'] = $data['top_data']['total_money_yesterday'] > 0 ? round(($data['top_data']['total_money_today'] - $data['top_data']['total_money_yesterday']) / $data['top_data']['total_money_yesterday'] * 100, 2) : round($data['top_data']['total_money_today'] * 100, 2);
        $data['top_data']['refund_rate'] = $data['top_data']['refund_money_yesterday'] > 0 ? round(($data['top_data']['refund_money_today'] - $data['top_data']['refund_money_yesterday']) / $data['top_data']['refund_money_yesterday'] * 100, 2) : round($data['top_data']['refund_money_today'] * 100, 2);
        // 销量排行
        $data['salesNumRank'] = $this->OrderModel->getSaleTimeRanking($param, 0, $shop_supplier_id);
        // 销售额排行
        $data['salesMoneyRank'] = $this->OrderModel->getSaleTimeRanking($param, 1, $shop_supplier_id);
        //支付金额概况
        $data['saleData'] = $this->getSaleByDate($param['sale_time'], $shop_supplier_id);
        //数据更新时间
        $data['update_time'] = date('Y-m-d H:i:s');
        //订单概况
        $data['orderData'] = $this->getGeneralOrder($param, $shop_supplier_id);
        return $data;
    }

    //订单概况
    private function getGeneralOrder($param, $shop_supplier_id)
    {
        $data = [
            ['name' => '外送订单', 'value' => $this->OrderModel->getGeneralOrder($param, 1, $shop_supplier_id)],
            ['name' => '自提订单', 'value' => $this->OrderModel->getGeneralOrder($param, 2, $shop_supplier_id)],
            ['name' => '堂食订单', 'value' => $this->OrderModel->getGeneralOrder($param, 3, $shop_supplier_id)],
            ['name' => '打包订单', 'value' => $this->OrderModel->getGeneralOrder($param, 4, $shop_supplier_id)],
        ];
        return $data;
    }

    /**
     * 通过时间段查询订单数据
     */
    private function getSaleByDate($days, $shop_supplier_id)
    {
        $dateInfo = $this->getDays($days);
        $days = $dateInfo['date'];
        $data = [];
        $endTime = null;
        foreach ($days as $day) {
            $data[] = [
                'day' => $day,
                'total_money' => $this->OrderModel->getOrderData($day, null, 'order_total_price', $shop_supplier_id),
            ];
            $endTime = $day;
        }
        $startTime = $days[0];
        $detail['data'] = $data;
        $detail['days'] = $dateInfo['time'];
        $detail['saleMoney'] = $this->OrderModel->getOrderData($startTime, $endTime, 'order_total_price', $shop_supplier_id);
        return $detail;
    }

    /**
     * 获取具体日期数组
     */
    private function getDays($time_type = '')
    {
        if ($time_type == 1) {
            $end_time = date('Y-m-d');
            $start_time = date('Y-m-d');
        } elseif ($time_type == 2) {//近7天
            $end_time = date('Y-m-d', time());
            $start_time = date('Y-m-d', strtotime('-7 day', time()));
        } elseif ($time_type == 3) {//近15天
            $end_time = date('Y-m-d', time());
            $start_time = date('Y-m-d', strtotime('-15 day', time()));
        } elseif ($time_type == 4) {//近30天
            $end_time = date('Y-m-d', time());
            $start_time = date('Y-m-d', strtotime('-30 day', time()));
        }
        $dt_start = strtotime($start_time);
        $dt_end = strtotime($end_time);
        $date = [];
        $time = [];
        $date[] = date('Y-m-d', strtotime($start_time));
        $time[] = date('m-d', strtotime($start_time));
        while ($dt_start < $dt_end) {
            $date[] = date('Y-m-d', strtotime('+1 day', $dt_start));
            $time[] = date('m-d', strtotime('+1 day', $dt_start));
            $dt_start = strtotime('+1 day', $dt_start);
        }
        $data['date'] = $date;
        $data['time'] = $time;
        return $data;
    }

    /**
     * 获取商品库存告急总量
     */
    private function getProductStockTotal($shop_supplier_id, $product_type)
    {
        return number_format($this->ProductModel->getProductStockTotal($shop_supplier_id, $product_type));
    }

    /**
     * 获取用户总量
     */
    private function getUserTotal($day = null)
    {
        return number_format($this->UserModel->getUserTotal($day));
    }

    /**
     * 获取订单总量
     */
    private function getOrderTotal($day, $shop_supplier_id = 0)
    {
        return number_format($this->OrderModel->getOrderData($day, null, 'order_total', $shop_supplier_id));
    }

    /**
     * 获取待处理订单总量
     */
    private function getReviewOrderTotal($shop_supplier_id, $order_type)
    {
        return number_format($this->OrderModel->getReviewOrderTotal($shop_supplier_id, $order_type));
    }

    /**
     * 获取某天的总销售额
     */
    private function getOrderTotalPrice($day, $type, $shop_supplier_id = 0)
    {
        return sprintf('%.2f', $this->OrderModel->getOrderData($day, null, $type, $shop_supplier_id));
    }

}