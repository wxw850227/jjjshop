<?php

namespace app\api\model\order;

use app\api\model\product\ProductSku as ProductSkuModel;
use app\common\model\order\Cart as CartModel;
use app\api\model\supplier\Supplier as SupplierModel;
use app\api\model\product\Product as ProductModel;
use app\common\library\helper;

/**
 * 普通订单模型
 */
class Cart extends CartModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'app_id',
        'update_time'
    ];

    /**
     * 购物车列表 (含商品信息)
     */
    public function getList($data, $user)
    {
        // 获取购物车商品列表
        $model = $this;
        if (isset($data['product_id']) && $data['product_id']) {
            $model = $model->where('c.product_id', '=', $data['product_id']);
        }
        if (isset($data['table_id'])) {
            $model = $model->where('c.table_id', '=', $data['table_id']);
        }
        $list = $model->alias('c')
            ->with(['product', 'image.file'])
            ->join('product p', 'p.product_id=c.product_id')
            ->where('c.shop_supplier_id', '=', $data['shop_supplier_id'])
            ->where('cart_type', '=', $data['cart_type'])
            ->where('user_id', '=', $user['user_id'])
            ->where('p.is_delete', '=', 0)
            ->where('product_status', '=', 10)
            ->field('c.*')
            ->select();
        return $list;
    }

    /**
     * 购物车列表 (含商品信息)
     */
    public function getCartList($data, $user)
    {
        // 获取购物车商品列表
        $model = $this->alias('c');
        if (isset($data['table_id'])) {
            $model = $model->where('table_id', '=', $data['table_id']);
        }
        $list = $model->with(['product', 'sku', 'image.file'])
            ->join('product p', 'p.product_id=c.product_id')
            ->field("c.*,(c.bag_price*product_num) as total_bag_price,p.is_enable_grade")
            ->where('cart_id', 'in', $data['cart_ids'])
            ->where('c.shop_supplier_id', '=', $data['shop_supplier_id'])
            ->where('user_id', '=', $user['user_id'])
            ->where('p.is_delete', '=', 0)
            ->where('product_status', '=', 10)
            ->select();

        if (!$list->isEmpty()) {
            foreach ($list as &$item) {
                $money = $item['price'] * $item['product_num'];
                $item['total_price'] = $money;
                $item['is_points_gift'] = $item['product']['is_points_gift'];
                $item['total_line_money'] = $item['product_price'] * $item['product_num'];
            }
        } else {
            $list = [];
        }
        return $list;
    }

    /**
     * 加入购物车
     */
    public function add($data, $user)
    {
        //判断是否营业
        $business = (new SupplierModel)->supplierState($data['shop_supplier_id'], $data['dinner_type']);
        if (!$business) {
            return false;
        }
        //判断商品是否下架
        $product = $this->productState($data['product_id']);
        if (!$product) {
            $this->error = '商品已下架';
            return false;
        }
        //判断是否存在
        $cartInfo = $this->isExist($data, $user);
        if ($cartInfo) {
            $stockStatus = $this->getStockState($data, $data['product_num'] + $cartInfo['product_num']);
            if (!$stockStatus) {
                $this->error = '商品库存不足';
                return false;
            }
            return $this->where('cart_id', '=', $cartInfo['cart_id'])->inc('product_num', $data['product_num'])->update();
        } else {
            $totalNum = $this->cartSkuNum($data, $user);
            $stockStatus = $this->getStockState($data, $data['product_num'] + $totalNum);
            if (!$stockStatus) {
                $this->error = '商品库存不足';
                return false;
            }
            $data['describe'] = trim($data['describe'], ';');
            $data['user_id'] = $user['user_id'];
            $data['app_id'] = self::$app_id;
            return $this->save($data);
        }

    }

    /**
     * 判断购物车商品是否存在
     */
    public function isExist($data, $user)
    {
        $model = $this;
        if (isset($data['table_id'])) {
            $model = $model->where('table_id', '=', $data['table_id']);
        }
        $cartInfo = $model->where('user_id', '=', $user['user_id'])
            ->where('product_id', '=', $data['product_id'])
            ->where('shop_supplier_id', '=', $data['shop_supplier_id'])
            ->where('product_sku_id', '=', $data['product_sku_id'])
            ->where('feed', '=', $data['feed'])
            ->where('attr', '=', $data['attr'])
            ->find();
        return $cartInfo;
    }

    /**
     * 加减商品
     */
    public function sub($param)
    {
        //判断是否营业
        $business = (new SupplierModel)->supplierState($this['shop_supplier_id'], $param['dinner_type']);
        if (!$business) {
            return false;
        }
        //判断商品是否下架
        $product = $this->productState($this['product_id']);
        if (!$product) {
            $this->error = '商品已下架';
            return false;
        }
        if ($param['type'] == 'down') {
            if ($this['product_num'] <= 1) {
                return $this->delete();
            }
            return $this->where('cart_id', '=', $this['cart_id'])->dec('product_num', 1)->update();
        } else {
            $stockStatus = $this->getStockState($this, $this['product_num'] + 1);
            if (!$stockStatus) {
                $this->error = '商品库存不足';
                return false;
            }
            return $this->where('cart_id', '=', $this['cart_id'])->inc('product_num', 1)->update();
        }
    }

    /**
     *清空购物车
     */
    public function deleteAll($param, $user)
    {
        $model = $this;
        if (isset($param['table_id'])) {
            $model = $model->where('table_id', '=', $param['table_id']);
        }
        return $model->where('shop_supplier_id', '=', $param['shop_supplier_id'])
            ->where('cart_type', '=', $param['cart_type'])
            ->where('user_id', '=', $user['user_id'])
            ->delete();
    }

    /**
     * 获取当前用户购物车商品总数量(含件数)
     */
    public function getProductNum($param, $user)
    {
        $model = $this;
        if (isset($param['table_id'])) {
            $model = $model->where('c.table_id', '=', $param['table_id']);
        }
        $count = $model->alias('c')
            ->join('product p', 'p.product_id=c.product_id')
            ->where('c.shop_supplier_id', '=', $param['shop_supplier_id'])
            ->where('cart_type', '=', $param['cart_type'])
            ->where('user_id', '=', $user['user_id'])
            ->where('p.is_delete', '=', 0)
            ->where('p.product_status', '=', 10)
            ->sum('product_num');
        return $count ? $count : 0;
    }

    //获取购物车单个商品数量
    public function getSingleProductNum($param, $user)
    {
        $model = $this;
        if (isset($param['table_id'])) {
            $model = $model->where('table_id', '=', $param['table_id']);
        }
        $num = $model->where('product_id', '=', $param['product_id'])
            ->where('user_id', '=', $user['user_id'])
            ->sum('product_num');
        return $num ? $num : 0;
    }

    //获取购物车价格
    public function getCartInfo($param, $user)
    {
        $model = $this;
        if (isset($param['table_id'])) {
            $model = $model->where('c.table_id', '=', $param['table_id']);
        }
        $cartList = $model->alias('c')
            ->join('product p', 'p.product_id=c.product_id')
            ->where('c.shop_supplier_id', '=', $param['shop_supplier_id'])
            ->where('user_id', '=', $user['user_id'])
            ->where('cart_type', '=', $param['cart_type'])
            ->where('p.is_delete', '=', 0)
            ->where('product_status', '=', 10)
            ->field('c.*')
            ->select();
        $total_money = 0;
        $bag_money = 0;
        $total_line_money = 0;
        if ($cartList) {
            foreach ($cartList as $item) {
                $bag_money += $item['bag_price'] * $item['product_num'];
                $money = $item['price'] * $item['product_num'];
                $total_line_money += $item['product_price'] * $item['product_num'];
                $total_money += $money;
            }
        }
        $data['total_product_price'] = round($total_money, 2);
        $data['total_bag_price'] = round($bag_money, 2);
        $data['total_line_money'] = round($total_line_money, 2);
        return $data;
    }

    //更新购物车
    public function clearAll($car_ids)
    {
        return $this->where('cart_id', 'in', $car_ids)->delete();
    }

    //判断商品是否下架
    public function productState($product_id)
    {
        return (new ProductModel)->where('product_id', '=', $product_id)
            ->where('product_status', '=', 10)
            ->where('is_delete', '=', 0)
            ->count();
    }

    //判断商品库存
    public function getStockState($data, $product_num)
    {
        return (new ProductSkuModel)->where('product_id', '=', $data['product_id'])
            ->where('product_sku_id', '=', $data['product_sku_id'])
            ->where('stock_num', '>=', $product_num)
            ->count();
    }

    /**
     * 查询购物车商品规格数量
     */
    public function cartSkuNum($data, $user)
    {
        $totalNum = $this->where('user_id', '=', $user['user_id'])
            ->where('product_id', '=', $data['product_id'])
            ->where('shop_supplier_id', '=', $data['shop_supplier_id'])
            ->where('product_sku_id', '=', $data['product_sku_id'])
            ->sum('product_num');
        return $totalNum ? $totalNum : 0;
    }

    //获取购物车数据
    public function getCartPrice($param, $user)
    {
        //购物车价格
        $cartInfo = $this->getCartInfo($param, $user);
        // 购物车商品总数量
        $cartInfo['cart_total_num'] = $this->getProductNum($param, $user);
        //门店信息
        $supplier = SupplierModel::detail($param['shop_supplier_id']);
        //包装费
        if (isset($param['delivery']) && $param['delivery'] == 40) {
            //店内用餐
            $cartInfo['total_bag_price'] = 0;
        } else {
            if (isset($param['delivery']) && $param['delivery'] == 30) {
                //店内打包带走
                if ($supplier['storebag_type'] == 1) {
                    $cartInfo['total_bag_price'] = $supplier['storebag_price'];
                }
            } else {
                //外卖
                if ($supplier['bag_type'] == 1) {
                    $cartInfo['total_bag_price'] = $supplier['bag_price'];
                }
            }
        }
        //购物车总价
        $total_price = $cartInfo['total_product_price'];
        if ($cartInfo['total_bag_price'] > 0) {
            $total_price = $total_price + $cartInfo['total_bag_price'];
            $cartInfo['total_line_money'] = round($cartInfo['total_line_money'] + $cartInfo['total_bag_price'], 2);
        }
        //最低消费差额
        $min_money_diff = $supplier['min_money'] - $total_price;
        $min_money_diff = $supplier['min_money'] && $min_money_diff > 0 ? $min_money_diff : 0;
        $cartInfo['total_price'] = round($total_price, 2);
        $cartInfo['min_money_diff'] = round($min_money_diff, 2);
        //查询满减数据
        $cartInfo['total_pay_price'] = $cartInfo['total_price'];
        return $cartInfo;
    }

    //查询用户分类购物车商品数量
    public function getCategoryProductNum($param, $productList, $user)
    {
        $model = $this;
        if (isset($param['table_id'])) {
            $model = $model->where('table_id', '=', $param['table_id']);
        }
        $productIds = helper::getArrayColumn($productList, 'product_id');
        $num = $model->alias('c')
            ->join('product p', 'p.product_id=c.product_id')
            ->where('c.shop_supplier_id', '=', $param['shop_supplier_id'])
            ->where('cart_type', '=', $param['cart_type'])
            ->where('user_id', '=', $user['user_id'])
            ->where('p.is_delete', '=', 0)
            ->where('product_status', '=', 10)
            ->where('c.product_id', 'in', $productIds)
            ->sum('product_num');

        return $num ? $num : 0;
    }

    //购物车商品详情
    public function getCartDetail($data, $user)
    {
        $model = $this;
        if (isset($data['table_id'])) {
            $model = $model->where('table_id', '=', $data['table_id']);
        }
        return $model->where('product_id', '=', $data['product_id'])
            ->where('user_id', '=', $user['user_id'])
            ->find();
    }

}