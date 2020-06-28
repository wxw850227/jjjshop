<?php

namespace app\api\model\order;

use think\facade\Cache;
use app\api\model\product\Product as ProductModel;

use app\common\library\helper;

/**
 * 购物车管理
 */
class Cart
{
    // 错误信息
    public $error = '';

    private $user;

    // 用户id
    private $user_id;

    // 应用id
    private $app_id;

    // 购物车列表
    private static $cart = [];

    // 是否清空购物车
    private $clear = false;

    /**
     * 构造方法
     */
    public function __construct()
    {
        static::$cart = Cache::get('cart_' . $this->user_id) ?: [];
    }

    /**
     * 购物车列表 (含商品信息)
     */
    public function getList($cartIds = null)
    {
        // 获取购物车商品列表
        return $this->getOrderProductList($cartIds);
    }

    /**
     * 获取购物车列表
     */
    public function getCartList($cartIds = null)
    {
        if (empty($cartIds)) return static::$cart;
        $cartList = [];
        $indexArr = (strpos($cartIds, ',') !== false) ? explode(',', $cartIds) : [$cartIds];
        foreach ($indexArr as $index) {
            isset(static::$cart[$index]) && $cartList[$index] = static::$cart[$index];
        }
        return $cartList;
    }

    /**
     * 获取购物车中的商品列表
     */
    public function getOrderProductList($cartIds)
    {
        // 购物车商品列表
        $productList = [];
        // 获取购物车列表
        $cartList = $this->getCartList($cartIds);
        if (empty($cartList)) {
            $this->setError('当前购物车没有商品');
            return $productList;
        }
        // 购物车中所有商品id集
        $productIds = array_unique(helper::getArrayColumn($cartList, 'product_id'));
        // 获取并格式化商品数据
        $sourceData = (new ProductModel)->getListByIds($productIds);
        $sourceData = helper::arrayColumn2Key($sourceData, 'product_id');
        // 格式化购物车数据列表
        foreach ($cartList as $key => $item) {
            // 判断商品不存在则自动删除
            // 商品信息
            $product = clone $sourceData[$item['product_id']];
            // 判断商品是否已删除
            // 商品sku信息
            $product['product_sku'] = ProductModel::getProductSku($product, $item['product_sku_id']);
            $product['product_sku_id'] = $item['product_sku_id'];
            $product['spec_sku_id'] = $product['product_sku']['spec_sku_id'];
            // 商品sku不存在则自动删除
            if (empty($product['product_sku'])) {
                $this->delete($key);
                continue;
            }
            // 商品单价
            $product['product_price'] = $product['product_sku']['product_price'];
            // 购买数量
            $product['total_num'] = $item['product_num'];
            // 商品总价
            $product['total_price'] = bcmul($product['product_price'], $item['product_num'], 2);

            $productList[] = $product->hidden(['category', 'content', 'image']);
        }
        return $productList;
    }

    /**
     * 加入购物车
     */
    public function add($params)
    {
        // 商品id
        $productId = $params['product_id'];
        // 商品数量
        $productNum = $params['total_num'];
        // 商品sku索引
        $productSkuId = $params['product_sku_id'];
        // 购物车商品索引
        $index = "{$productId}_{$productSkuId}";
        // 加入购物车后的商品数量
        $cartProductNum = $productNum + (isset(static::$cart[$index]) ? static::$cart[$index]['product_num'] : 0);
        // 获取商品信息
        $product = ProductModel::detail($productId);
        // 验证商品能否加入
        if (!$this->checkProduct($product, $productSkuId, $cartProductNum)) {
            return false;
        }
        // 记录到购物车列表
        static::$cart[$index] = [
            'product_id' => $productId,
            'product_num' => $cartProductNum,
            'product_sku_id' => $productSkuId,
            'create_time' => time()
        ];
        return true;
    }

    /**
     * 验证购物车中是否存在某商品
     */
    private function isExistProductId($productId)
    {
        foreach (static::$cart as $item) {
            if ($item['product_id'] == $productId) return true;
        }
        return false;
    }

    /**
     * 验证商品是否可以购买
     */
    private function checkProduct($product, $productSkuId, $cartProductNum)
    {
        // 判断商品是否下架
        if (!$product || $product['is_delete'] || $product['product_status']['value'] != 10) {
            $this->setError('很抱歉，商品信息不存在或已下架');
            return false;
        }
        // 商品sku信息
        $product['product_sku'] = ProductModel::getProductSku($product, $productSkuId);
        // 判断商品库存
        if ($cartProductNum > $product['product_sku']['stock_num']) {
            $this->setError('很抱歉，商品库存不足');
            return false;
        }
        return true;
    }

    /**
     * 减少购物车中某商品数量
     */
    public function sub($productId, $productSkuId)
    {
        $index = "{$productId}_{$productSkuId}";
        static::$cart[$index]['product_num'] > 1 && static::$cart[$index]['product_num']--;
    }

    /**
     * 删除购物车中指定商品
     * cartIds (支持字符串ID集)
     */
    public function delete($cartIds)
    {
        $indexArr = array_filter(trim(strpos($cartIds, ','), ',') !== false ? explode(',', $cartIds) : [$cartIds]);
        foreach ($indexArr as $index) {
            if (isset(static::$cart[$index])) unset(static::$cart[$index]);
        }
    }

    /**
     * 获取当前用户购物车商品总数量(含件数)
     */
    public function getTotalNum()
    {
        return helper::getArrayColumnSum(static::$cart, 'product_num');
    }

    /**
     * 获取当前用户购物车商品总数量(不含件数)
     */
    public function getProductNum()
    {
        return count(static::$cart);
    }

    /**
     * 析构方法
     * 将cart数据保存到缓存文件
     */
    public function __destruct()
    {
        $this->clear !== true && Cache::set('cart_' . $this->user_id, static::$cart, 86400 * 15);
    }

    /**
     * 清空当前用户购物车
     */
    public function clearAll($cartIds = null)
    {
        if (empty($cartIds)) {
            $this->clear = true;
            Cache::delete('cart_' . $this->user_id);
        } else {
            $this->delete($cartIds);
        }
    }

    /**
     * 设置错误信息
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->error;
    }

}