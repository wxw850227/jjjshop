<?php

namespace app\shop\controller\product;

use app\shop\controller\Controller;
use app\shop\model\product\Category as CategoryModel;

/**
 * 商品分类
 */
class Category extends Controller
{
    /**
     * 商品分类列表
     */
    public function index()
    {
        $model = new CategoryModel;
        $list = $model->getCacheTree();
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 删除商品分类
     */
    public function delete($category_id)
    {
        $model = CategoryModel::find($category_id);
        if (!$model->remove($category_id)) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 添加商品分类
     */
    public function add()
    {
        $model = new CategoryModel;
        // 新增记录
        if ($model->add($this->request->post())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 编辑商品分类
     */
    public function edit($category_id)
    {
        // 模板详情
        $model = CategoryModel::detail($category_id);
        // 更新记录
        if ($model->edit($this->request->post())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 得到修改图片
     */
    public function image($category_id)
    {
        $model = new CategoryModel;
        $detail = $model->detail($category_id);
        return $this->renderSuccess('', compact('detail'));
    }


}
