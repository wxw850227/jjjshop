<?php

namespace app\shop\controller\data;

use app\shop\controller\Controller;
use app\shop\model\product\Product as ProductModel;
use app\shop\model\product\Category as CategoryModel;

/**
 * 商品数据控制器
 */
class Product extends Controller
{
    // 模型
    private $model;

    /**
     * 构造方法
     */
    public function initialize()
    {
        $this->model = new ProductModel;
    }

    /**
     * 商品列表
     */
    public function lists()
    {
        // 商品分类
        $category = CategoryModel::getCacheTree();
        // 商品列表
        $data = $_POST;
        if (empty($data)) {
            $data = array(1);
        }
        $list = $this->model->getList($data);
        return $this->renderSuccess('', compact('list', 'category'));
    }

}
