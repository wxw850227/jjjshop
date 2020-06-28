<?php

namespace app\api\service\order\settled;

use app\api\model\order\Order as OrderModel;
use app\common\enum\order\OrderSourceEnum;
use app\common\model\product\ProductSku as ProductSkuModel;
use app\api\service\order\settled\OrderSettledService;

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
        //订单来源
        $this->orderSource = [
            'source' => OrderSourceEnum::MASTER,
            'source_id' => 0,
        ];
    }


    /**
     * 验证订单商品的状态
     */
    public function validateProductList()
    {
        foreach ($this->productList as $product) {
            // 判断商品是否下架
            if ($product['product_status']['value'] != 10) {
                $this->error = "很抱歉，商品 [{$product['product_name']}] 已下架";
                return false;
            }
            // 判断商品库存
            if ($product['total_num'] > $product['product_sku']['stock_num']) {
                $this->error = "很抱歉，商品 [{$product['product_name']}] 库存不足";
                return false;
            }
        }
        return true;
    }
}