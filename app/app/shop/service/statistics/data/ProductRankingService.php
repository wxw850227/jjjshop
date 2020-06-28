<?php

namespace app\shop\service\statistics\data;

use app\shop\model\order\OrderProduct as OrderProductModel;
use app\common\enum\order\OrderStatusEnum;
use app\common\enum\order\OrderPayStatusEnum;

/**
 * 数据统计-商品销售榜
 */
class ProductRankingService
{
    /**
     * 商品销售榜
     */
    public function getProductRanking()
    {
        return (new OrderProductModel)->alias('o_product')
            ->field([
                'o_product.product_id,o_product.product_name',
                'SUM(total_pay_price) AS sales_volume',
                'SUM(total_num) AS total_sales_num'
            ])
            ->join('order', 'order.order_id = o_product.order_id')
            ->where('order.pay_status', '=', OrderPayStatusEnum::SUCCESS)
            ->where('order.order_status', '<>', OrderStatusEnum::CANCELLED)
            ->group('o_product.product_id')
            ->order(['sales_volume' => 'DESC'])
            ->limit(10)
            ->select();
    }

}