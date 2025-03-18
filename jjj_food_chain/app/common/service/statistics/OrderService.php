<?php

namespace app\common\service\statistics;

use app\common\library\helper;
use app\common\model\order\Order as OrderModel;
use app\common\model\product\Product as ProductModel;
use app\common\model\order\OrderProduct as OrderProductModel;

/**
 * 订单数据概况
 */
class OrderService
{
    // 商户id
    private $shop_supplier_id;

    public function __construct($shop_supplier_id)
    {
        $this->shop_supplier_id = $shop_supplier_id;
    }

    /**
     * 获取数据概况
     */
    public function getData()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $data = [
            // 成交额(元)
            'order_total_price' => [
                'today' => helper::number2($this->getOrderData($today, null, 'order_total_price')),
                'yesterday' => helper::number2($this->getOrderData($yesterday, null, 'order_total_price'))
            ],
            // 支付订单数
            'order_total' => [
                'today' => number_format($this->getOrderData($today, null, 'order_total')),
                'yesterday' => number_format($this->getOrderData($yesterday, null, 'order_total'))
            ],
            // 下单用户数
            'order_user_total' => [
                'today' => number_format($this->getOrderData($today, null, 'order_user_total')),
                'yesterday' => number_format($this->getOrderData($yesterday, null, 'order_user_total'))
            ],
            // 退款成功总金额
            'order_refund_money' => [
                'today' => $this->getOrderData($today, null, 'order_refund_money'),
                'yesterday' => $this->getOrderData($yesterday, null, 'order_refund_money')
            ],
            // 退款成功订单数
            'income_price' => [
                'today' => $this->getOrderData($today, null, 'income_price'),
                'yesterday' => $this->getOrderData($yesterday, null, 'income_price')
            ],
        ];
        // 客单价
        $data['order_per_price'] = [
            'today' => $data['order_user_total']['today'] == 0 ? 0 : helper::number2($data['order_total_price']['today'] / $data['order_user_total']['today']),
            'yesterday' => $data['order_user_total']['yesterday'] == 0 ? 0 : helper::number2($data['order_total_price']['yesterday'] / $data['order_user_total']['yesterday'])
        ];
        return $data;
    }

    /**
     * 通过时间段查询订单数据
     */
    public function getDataByDate($days)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = [
                'day' => $day,
                'total_money' => $this->getOrderData($day, null, 'order_total_price'),
                'total_num' => $this->getOrderData($day, null, 'order_total')
            ];
        }
        return $data;
    }

    /**
     * 通过时间段查询订单数据
     */
    public function getOrderDataByDate($days, $order_type)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = [
                'day' => $day,
                'total_money' => $this->getOrderData($day, null, 'order_total_price', $order_type),
                'refund_money' => $this->getOrderData($day, null, 'order_refund_money', $order_type),
                'income_price' => $this->getOrderData($day, null, 'income_price', $order_type),
                'order_total' => $this->getOrderData($day, null, 'order_total', $order_type),
            ];
        }
        return $data;
    }

    /**
     * 通过时间段查询退款订单数据
     */
    public function getRefundByDate($days)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = [
                'day' => $day,
                'total_money' => $this->getOrderRefundData($day, null, 'order_refund_money'),
                'total_num' => $this->getOrderRefundData($day, null, 'order_refund_total')
            ];
        }
        return $data;
    }

    /**
     * 获取订单数据
     */
    private function getOrderData($startDate, $endDate, $type, $order_type = -1)
    {
        return (new OrderModel)->getOrderData($startDate, $endDate, $type, $this->shop_supplier_id, $order_type);
    }

    /**
     * 获取订单退款数据
     */
    private function getOrderRefundData($startDate, $endDate, $type)
    {
        return (new OrderModel)->getOrderData($startDate, $endDate, $type, $this->shop_supplier_id);
    }

    /**
     * 获取数据概况
     */
    public function getProductData()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $data = [
            // 在售商品
            'sale' => [
                'today' => number_format((new ProductModel)->getProductTotal(['product_status' => 10])),
                'yesterday' => '--'
            ],
            // 未付款商品(件)
            'no_pay' => [
                'today' => number_format($this->getOrderProductData($today, null, 'no_pay')),
                'yesterday' => number_format($this->getOrderProductData($yesterday, null, 'no_pay'))
            ],
            // 已付款商品(件)
            'pay' => [
                'today' => number_format($this->getOrderProductData($today, null, 'pay')),
                'yesterday' => number_format($this->getOrderProductData($yesterday, null, 'pay'))
            ],
        ];
        return $data;
    }

    /**
     * 获取订单商品数据
     */
    private function getOrderProductData($startDate, $endDate, $type)
    {
        return (new OrderProductModel)->getProductData($startDate, $endDate, $type, $this->shop_supplier_id);
    }


    /**
     * 通过时间段查询商品订单数据
     */
    public function getProductDataByDate($days)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = [
                'day' => $day,
                'total_num' => $this->getOrderProductData($day, null, 'pay')
            ];
        }
        return $data;
    }
}