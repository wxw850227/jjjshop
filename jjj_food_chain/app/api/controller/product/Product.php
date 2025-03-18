<?php

namespace app\api\controller\product;

use app\api\model\product\Product as ProductModel;
use app\api\controller\Controller;
use app\api\model\supplier\Supplier as SupplierModel;

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
            'product_status' => 10,
        ]);

        // 获取列表数据
        $model = new ProductModel;
        $list = $model->getList($param, $this->getUser(false));
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 商品详情
     */
    public function detail()
    {
        // 获取列表数据
        $model = new ProductModel;
        $detail = $model->getDetails($this->postData(), $this->getUser());
        $supplier = SupplierModel::detail($detail['shop_supplier_id']);
        return $this->renderSuccess('', compact('detail', 'supplier'));
    }
}