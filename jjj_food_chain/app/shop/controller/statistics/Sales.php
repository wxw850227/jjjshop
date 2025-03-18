<?php

namespace app\shop\controller\statistics;

use app\shop\controller\Controller;
use app\common\service\statistics\OrderService;
use app\common\service\statistics\ProductRankingService;
use app\shop\model\supplier\Supplier as SupplierModel;

/**
 * 销售数据控制器
 */
class Sales extends Controller
{
    /**
     * 销售数据统计
     */
    public function index($shop_supplier_id, $search_time = '')
    {
        $user = $this->store['user'];
        if ($user['user_type'] == 1) {
            $shop_supplier_id = $user['shop_supplier_id'];
        }
        //商家数据
        $supplierList = SupplierModel::getAll();

        $days = $this->getDays($search_time);
        //订单数据
        $orderData = (new OrderService($shop_supplier_id))->getDataByDate($days);
        // 数据
        $productData = (new OrderService($shop_supplier_id))->getProductDataByDate($days);
        return $this->renderSuccess('', [
            // 成交订单统计
            'order' => (new OrderService($shop_supplier_id))->getData(),
            // 成交商品统计
            'product' => (new OrderService($shop_supplier_id))->getProductData(),
            // 销量top10
            'productSaleRanking' => (new ProductRankingService())->getSaleRanking($shop_supplier_id),
            // 浏览top10
            'productViewRanking' => (new ProductRankingService())->getViewRanking($shop_supplier_id),
            //商家列表
            'supplierList' => $supplierList,
            //订单数
            'orderData' => $orderData,
            //商品数
            'productData' => $productData,
            //日期
            'days' => $days
        ]);
    }

    /**
     * 通过时间段查询本期上期金额
     * $type类型：order refund
     */
    public function order($search_time)
    {
        $days = $this->getDays($search_time);
        $user = $this->store['user'];
        $shop_supplier_id = 0;
        if ($user['user_type'] == 1) {
            $shop_supplier_id = $user['shop_supplier_id'];
        }
        $data = (new OrderService($shop_supplier_id))->getDataByDate($days);
        return $this->renderSuccess('', [
            // 日期
            'days' => $days,
            // 数据
            'data' => $data,
        ]);
    }

    /**
     * 通过时间段查询本期上期金额
     */
    public function product($search_time)
    {
        $days = $this->getDays($search_time);
        return $this->renderSuccess('', [
            // 日期
            'days' => $days,
            // 数据
            'data' => (new OrderService(0))->getProductDataByDate($days),
        ]);
    }

    /**
     * 获取具体日期数组
     */
    private function getDays($search_time)
    {
        //搜索时间段
        if (!isset($search_time) || empty($search_time)) {
            //没有传，则默认为最近7天
            $end_time = date('Y-m-d', time());
            $start_time = date('Y-m-d', strtotime('-7 day', time()));
        } else {
            $start_time = array_shift($search_time);
            $end_time = array_pop($search_time);
        }

        $dt_start = strtotime($start_time);
        $dt_end = strtotime($end_time);
        $date = [];
        $date[] = date('Y-m-d', strtotime($start_time));
        while ($dt_start < $dt_end) {
            $date[] = date('Y-m-d', strtotime('+1 day', $dt_start));
            $dt_start = strtotime('+1 day', $dt_start);
        }
        return $date;
    }
}