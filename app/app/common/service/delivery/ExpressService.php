<?php

namespace app\common\service\delivery;

use app\common\library\helper;
use app\common\model\settings\Setting as SettingModel;
use app\common\enum\order\OrderTypeEnum;

/**
 * 快递配送服务类
 */
class ExpressService
{
    private $appId;   // 商城id
    private $cityId;    // 用户收货城市id
    private $productList;  // 订单商品列表
    private $orderType;  // 订单类型 (主商城、拼团)

    /**
     * 配送服务类构造方法
     */
    public function __construct(
        $appId,
        $cityId,
        $productList,
        $orderType = OrderTypeEnum::MASTER
    )
    {
        $this->appId = $appId;
        $this->cityId = $cityId;
        $this->productList = $productList;
        $this->orderType = $orderType;
    }

    /**
     * 根据用户收货城市id 验证是否在商品配送规则中
     */
    public function getNotInRuleProduct()
    {
        if ($this->cityId) {
            foreach ($this->productList as &$product) {
                $cityIds = [];
                foreach ($product['delivery']['rule'] as $item)
                    $cityIds = array_merge($cityIds, $item['region_data']);
                if (!in_array($this->cityId, $cityIds))
                    return $product;
            }

        }
        return false;
    }

    /**
     * 设置订单商品的运费
     */
    public function setExpressPrice()
    {
        // 订单商品总金额
        $orderTotalPrice = helper::getArrayColumnSum($this->productList, 'total_price');
        foreach ($this->productList as &$product) {
            $product['express_price'] = $this->onCalcProductfreight($product, $orderTotalPrice);
        }
        return true;
    }

    /**
     * 获取订单最终运费
     */
    public function getTotalFreight()
    {
        if (empty($this->productList)) {
            return 0.00;
        }
        // 所有商品的运费金额
        $expressPriceArr = helper::getArrayColumn($this->productList, 'express_price');
        if (empty($expressPriceArr)) {
            return 0.00;
        }
        // 计算最终运费
        return $this->freightRule($expressPriceArr);
    }

    /**
     * 计算商品的配送费用
     */
    private function onCalcProductfreight(&$product, $orderTotalPrice)
    {
        // 当前收货城市配送规则
        $rule = $this->getCityDeliveryRule($product);
        // 商品总重量
        $totalWeight = helper::bcmul($product['product_sku']['product_weight'], $product['total_num']);
        // 商品总数量or总重量
        $total = $product['delivery']['method']['value'] == 10 ? $product['total_num'] : $totalWeight;
        if ($total <= $rule['first']) {
            return helper::number2($rule['first_fee']);
        }
        // 续件or续重 数量
        $additional = $total - $rule['first'];
        if ($additional <= $rule['additional']) {
            return helper::number2(helper::bcadd($rule['first_fee'], $rule['additional_fee']));
        }
        // 计算续重/件金额
        if ($rule['additional'] < 1) {
            // 配送规则中续件为0
            $additionalFee = 0.00;
        } else {
            $additionalFee = helper::bcdiv($rule['additional_fee'], $rule['additional']) * $additional;
        }
        return helper::number2(helper::bcadd($rule['first_fee'], $additionalFee));
    }

    /**
     * 根据城市id获取规则信息
     */
    private function getCityDeliveryRule(&$product)
    {
        foreach ($product['delivery']['rule'] as $item) {
            if (in_array($this->cityId, $item['region_data'])) {
                return $item;
            }
        }
        return false;
    }

    /**
     * 根据运费组合策略 计算最终运费
     */
    private function freightRule($expressPriceArr)
    {
        $expressPrice = 0.00;
        switch (SettingModel::getItem('trade', $this->appId)['freight_rule']) {
            case '10':    // 策略1: 叠加
                $expressPrice = array_sum($expressPriceArr);
                break;
            case '20':    // 策略2: 以最低运费结算
                $expressPrice = min($expressPriceArr);
                break;
            case '30':    // 策略3: 以最高运费结算
                $expressPrice = max($expressPriceArr);
                break;
        }
        return $expressPrice;
    }

}