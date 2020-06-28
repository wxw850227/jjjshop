<?php

namespace app\api\controller\product;

use app\api\model\product\Category as CategoryModel;
use app\api\controller\Controller;
use app\api\model\page\PageCategory as PageCategoryModel;

/**
 * 商品分类控制器
 */
class Category extends Controller
{
    /**
     * 分类页面
     */
    public function index()
    {
        // 分类模板
        $templet = PageCategoryModel::detail();
        // 商品分类列表
        $list = array_values(CategoryModel::getCacheTree());
        return $this->renderSuccess('', compact('templet','list'));
    }

}