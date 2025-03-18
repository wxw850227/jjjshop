<?php

namespace app\api\model\product;

use app\common\model\product\Product as ProductModel;
use app\common\service\product\BaseProductService;
use app\common\library\helper;
use app\api\model\order\Cart as CartModel;

/**
 * 商品模型
 */
class Product extends ProductModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'spec_rel',
        'delivery',
        'sales_initial',
        'sales_actual',
        'is_delete',
        'app_id',
        'create_time',
        'update_time'
    ];

    /**
     * 商品详情：HTML实体转换回普通字符
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 获取商品列表
     */
    public function getList($param, $userInfo = false)
    {
        // 获取商品列表
        $data = parent::getList($param, 1);

        // 隐藏api属性
        !$data->isEmpty() && $data->hidden(['content', 'image']);
        // 整理列表数据并返回
        $param['userInfo'] = $userInfo;
        return $this->setProductListDataFromApi($data, true, $param);
    }

    /**
     * 商品详情
     */
    public static function detail($product_id)
    {
        // 商品详情
        $detail = parent::detail($product_id);
        // 多规格商品sku信息
        $detail['product_multi_spec'] = BaseProductService::getSpecData($detail);
        return $detail;
    }

    /**
     * 获取商品详情页面
     */
    public function getDetails($param, $userInfo = false)
    {
        // 获取商品详情
        $detail = $this->with([
            'category',
            'image' => ['file'],
            'sku' => ['image']])
            ->where('product_id', '=', $param['product_id'])
            ->find();
        // 判断商品的状态
        if (empty($detail) || $detail['is_delete'] || $detail['product_status']['value'] != 10) {
            $this->error = '很抱歉，商品信息不存在或已下架';
            return false;
        }
        // 更新访问数据
        $this->where('product_id', '=', $detail['product_id'])->inc('view_times')->update();
        // 设置商品展示的数据
        $param['userInfo'] = $userInfo;
        $detail = $this->setProductListDataFromApi($detail, false, $param);
        return $detail;
    }

    /**
     * 设置商品展示的数据 api模块
     */
    private function setProductListDataFromApi(&$data, $isMultiple, $param)
    {
        return parent::setProductListData($data, $isMultiple, function ($product) use ($param) {
            // 计算并设置商品会员价
            $this->setProductGradeMoney($param, $product);
        });
    }

    /**
     * 设置商品的会员价
     */
    public function setProductGradeMoney($param, &$product)
    {
        $user = $param['userInfo'];
        //商品购物车数量
        $product['cart_num'] = 0;
        if ($user && isset($param['order_type'])) {
            $param['product_id'] = $product['product_id'];
            $num = (new CartModel())->getSingleProductNum($param, $user);
            $product['cart_num'] = $num;
        }
        if ($product['product_attr'] || $product['product_feed'] || $product['spec_type'] == 20) {
            $product['spec_types'] = 20;
        }
    }
}