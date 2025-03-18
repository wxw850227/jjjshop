<?php

namespace app\api\service\order\settled;

use app\common\enum\order\OrderSourceEnum;
use app\api\model\order\Order as OrderModel;
use app\api\model\supplier\Supplier as SupplierModel;

/**
 * 普通订单结算服务类
 */
class MasterOrderSettledService extends OrderSettledService
{
    /**
     * 构造函数
     */
    public function __construct($user, $productList, $params)
    {
        parent::__construct($user, $productList, $params);
        if (isset($params['table_id']) && ($params['table_id'])) {
            $source = OrderSourceEnum::SCAN;
        } else {
            $source = OrderSourceEnum::MASTER;
        }
        //订单来源
        $this->orderSource = [
            'source' => $source,
            'activity_id' => 0
        ];
        //自身构造,差异化规则
    }

    /**
     * 验证订单商品的状态
     */
    public function validateProductList()
    {
        if (count($this->productList) <= 0) {
            $this->error = "商品不能为空";
            return false;
        }
        $supplier = SupplierModel::detail($this->params['shop_supplier_id']);
        if ($this->params['delivery'] == 10) {
            if (!$this->user['address']) {
                $this->error = "请添加地址";
                return false;
            }
            $distance = OrderModel::getDistance($supplier['longitude'], $supplier['latitude'], $this->user['address']['longitude'], $this->user['address']['latitude']);
            if ($distance > $supplier['delivery_distance'] * 1000 && $supplier['delivery_distance'] > 0) {
                $this->error = "超出配送范围{$supplier['delivery_distance']}km";
                return false;
            }
            if ($supplier['delivery_time'] && strtotime($this->params['mealtime']) > strtotime($supplier['delivery_time'][1])) {
                $this->error = "配送时间不在营业时间范围";
                return false;
            }
        } elseif ($this->params['delivery'] == 20) {
            if ($supplier['pick_time'] && strtotime($this->params['mealtime']) > strtotime($supplier['pick_time'][1])) {
                $this->error = "自提时间不在营业时间范围";
                return false;
            }
        } else {
            if ($supplier['store_time'] && strtotime($this->params['mealtime']) > strtotime($supplier['store_time'][1])) {
                $this->error = "店内用餐时间不在营业时间范围";
                return false;
            }
        }
        foreach ($this->productList as $product) {
            // 判断商品是否下架
            if ($product['product']['product_status']['value'] != 10) {
                $this->error = "很抱歉，商品 [{$product['product']['product_name']}] 已下架";
                return false;
            }
            !isset($productData[$product['product_id']]) && $productData[$product['product_id']] = 0;
            $productData[$product['product_id']] += $product['product_num'];
            // 判断商品库存
            if ($productData[$product['product_id']] > $product['sku']['stock_num']) {
                $this->error = "很抱歉，商品 [{$product['product']['product_name']}] 库存不足";
                return false;
            }
            // 判断是否超过限购数量
            if ($product['product']['limit_num'] > 0) {
                $hasNum = OrderModel::getHasBuyOrderNum($this->user['user_id'], $product['product_id']);
                if ($hasNum + $productData[$product['product_id']] > $product['product']['limit_num']) {
                    $this->error = "很抱歉，购买超过此商品最大限购数量";
                    return false;
                }
            }
        }
        return true;
    }
}