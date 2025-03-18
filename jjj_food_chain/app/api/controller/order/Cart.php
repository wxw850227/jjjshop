<?php

namespace app\api\controller\order;

use app\api\controller\Controller;
use app\api\model\order\Cart as CartModel;

/**
 * 购物车控制器
 */
class Cart extends Controller
{
    private $user;

    // $model
    private $model;

    /**
     * 构造方法
     */
    public function initialize()
    {
        $this->user = $this->getUser();
        $this->model = new CartModel;
    }

    /**
     * 购物车列表
     */
    public function lists()
    {
        // 请求参数
        $param = $this->request->param();
        // 购物车商品列表
        $productList = $this->model->getList($param, $this->user);
        //购物车金额
        $cartInfo = $this->model->getCartPrice($param, $this->user);
        return $this->renderSuccess('', compact('productList', 'cartInfo'));
    }

    /**
     * 加入购物车
     * @param int $product_id 商品id
     * @param int $product_num 商品数量
     */
    public function add()
    {
        $data = $this->request->param();
        $model = $this->model;
        if (!$model->add($data, $this->user)) {
            return $this->renderError($model->getError() ?: '加入购物车失败');
        }
        //购物车金额
        $cartInfo = $this->model->getCartPrice($data, $this->user);
        return $this->renderSuccess('加入购物车成功', ['cartInfo' => $cartInfo]);
    }

    /**
     * 商品列表加减数量
     * @param $product_id
     */
    public function productSub()
    {
        $model = $this->model->getCartDetail($this->postData(), $this->user);
        if ($model && $model->sub($this->postData())) {
            //购物车金额
            $cartInfo = $this->model->getCartPrice($this->postData(), $this->user);
            return $this->renderSuccess('操作成功', ['cartInfo' => $cartInfo]);
        }
        return $this->renderError($model ? $model->getError() : '操作失败');
    }

    /**
     * 加减购物商品数量
     * @param $cart_id
     */
    public function sub($cart_id)
    {
        $model = $this->model::detail($cart_id);
        if ($model && $model->sub($this->postData())) {
            //购物车金额
            $cartInfo = $this->model->getCartPrice($this->postData(), $this->user);
            return $this->renderSuccess('操作成功', ['cartInfo' => $cartInfo]);
        }
        return $this->renderError($model ? $model->getError() : '操作失败');
    }

    /**
     * 清空购物车
     * @return array
     */
    public function delete()
    {
        $this->model->deleteAll($this->postData(), $this->user);
        return $this->renderSuccess('删除成功');
    }
}