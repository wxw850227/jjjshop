<?php

namespace app\common\service\statistics;

use app\common\model\order\OrderProduct as OrderProductModel;
use app\common\enum\order\OrderStatusEnum;
use app\common\enum\order\OrderPayStatusEnum;
use app\common\model\product\Product as ProductModel;

/**
 * 数据统计-商品销售榜
 */
class ProductRankingService
{
    /**
     * 商品销售榜
     */
    public function getSaleRanking($shop_supplier_id = 0)
    {
        $model = new OrderProductModel();
        if ($shop_supplier_id > 0) {
            $model = $model->where('order.shop_supplier_id', '=', $shop_supplier_id);
        }
        return $model->alias('o_product')
            ->with(['image'])
            ->field([
                '*',
                'SUM(total_pay_price) AS sales_volume',
                'SUM(total_num) AS total_sales_num'
            ])->hidden(['content'])
            ->join('order', 'order.order_id = o_product.order_id')
            ->where('order.pay_status', '=', OrderPayStatusEnum::SUCCESS)
            ->where('order.order_status', '<>', OrderStatusEnum::CANCELLED)
            ->group('o_product.product_id')
            ->having('total_sales_num>0')
            ->order(['total_sales_num' => 'DESC'])
            ->limit(10)
            ->select();
    }

    /**
     * 商品浏览榜
     */
    public function getViewRanking($shop_supplier_id = 0)
    {
        $model = new ProductModel();
        if ($shop_supplier_id > 0) {
            $model = $model->where('shop_supplier_id', '=', $shop_supplier_id);
        }
        return $model->with(['image.file'])
            ->hidden(['content'])
            ->where('view_times', '>', 0)
            ->where('is_delete', '=', 0)
            ->order(['view_times' => 'DESC'])
            ->limit(10)
            ->select();
    }

    /**
     * 商品销售额榜
     */
    public function getSaleMoneryRanking($shop_supplier_id = 0)
    {
        $model = new OrderProductModel();
        if ($shop_supplier_id > 0) {
            $model = $model->where('order.shop_supplier_id', '=', $shop_supplier_id);
        }
        return $model->alias('o_product')
            ->with(['image'])
            ->field([
                '*',
                'SUM(total_pay_price) AS sales_volume',
                'SUM(total_num) AS total_sales_num'
            ])->hidden(['content'])
            ->join('order', 'order.order_id = o_product.order_id')
            ->where('order.pay_status', '=', OrderPayStatusEnum::SUCCESS)
            ->where('order.order_status', '<>', OrderStatusEnum::CANCELLED)
            ->group('o_product.product_id')
            ->having('sales_volume>0')
            ->order(['sales_volume' => 'DESC'])
            ->limit(10)
            ->select();
    }
}