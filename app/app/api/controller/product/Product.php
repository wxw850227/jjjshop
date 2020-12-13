<?php

namespace app\api\controller\product;

use app\api\model\product\Product as ProductModel;
use app\api\model\order\Cart as CartModel;
use app\api\controller\Controller;
use app\common\service\qrcode\ProductService;

/**
 * 商品控制器
 */
class Product extends Controller
{
    /**
     * 商品列表
     */
    public function lists()
    {
        // 整理请求的参数
        $param = array_merge($this->postData(), [
            'product_status' => 10
        ]);

        // 获取列表数据
        $model = new ProductModel;
        $list = $model->getList($param, $this->getUser(false));
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 获取商品详情
     */
    public function detail($url)
    {
        $product_id = $_GET['product_id'];
        // 用户信息
        $user = $this->getUser(false);
        // 商品详情
        $model = new ProductModel;
        $product = $model->getDetails($product_id, $this->getUser(false));
        if ($product === false) {
            return $this->renderError($model->getError() ?: '商品信息不存在');
        }
        // 多规格商品sku信息
        $specData = $product['spec_type'] == 20 ? $model->getManySpecData($product['spec_rel'], $product['sku']) : null;

        return $this->renderSuccess('', [
            // 商品详情
            'detail' => $product,
            // 购物车商品总数量
            'cart_total_num' => $user ? (new CartModel($user))->getProductNum() : 0,
            // 多规格商品sku信息
            'specData' => $specData,
            // 微信公众号分享参数
            'share' => $this->getShareParams($url, $product['product_name'], $product['product_name'], '/pages/product/detail/detail', $product['image'][0]['file_path']),
        ]);
    }

    /**
     * 生成商品海报
     */
    public function poster($product_id, $source)
    {
        // 商品详情
        $detail = ProductModel::detail($product_id);
        $Qrcode = new ProductService($detail, $this->getUser(false), $source);
        return $this->renderSuccess('', [
            'qrcode' => $Qrcode->getImage(),
        ]);
    }
}