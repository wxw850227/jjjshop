<?php

namespace app\api\controller\order;

use app\api\controller\Controller;
use app\api\model\order\Cart as CartModel;
/**
 * 购物车控制器
 */
class Cart extends Controller
{
    // 当前用户
    private $user;
    // 购物车模型
    private $model;

    /**
     * 构造方法
     */
    public function initialize()
    {
        $this->user = $this->getUser();
        $this->model = new CartModel($this->user);
    }


    /**
     * 购物车列表
     */
    public function lists()
    {
        // 请求参数
        $param = $this->request->param();
        $cartIds = isset($param['cart_ids']) ? $param['cart_ids'] : '';
        // 购物车商品列表
        $productList = $this->model->getList($cartIds);
        return $this->renderSuccess('', $productList);
    }

    /**
     * 加入购物车
     */
    public function add()
    {
        $model = new CartModel;
        if (!$model->add($this->postData())) {
            return $this->renderError('加入购物车失败');
        }
        // 购物车商品总数量
        $totalNum = $model->getProductNum();
        return $this->renderSuccess('加入购物车成功', ['cart_total_num' => $totalNum]);
    }

    /**
     * 减少购物车商品数量
     */
    public function sub($product_id, $product_sku_id)
    {
        $this->model->sub($product_id, $product_sku_id);
        return $this->renderSuccess('');
    }

    /**
     * 删除购物车中指定商品
     * product_sku_id (支持字符串ID集)
     */
    public function delete($product_sku_id)
    {
        $this->model->delete($product_sku_id);
        return $this->renderSuccess('删除成功');
    }
}