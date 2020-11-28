<?php

namespace app\shop\controller\operate\article;

use app\shop\controller\Controller;
use app\shop\model\article\Category as CategoryModel;

/**
 * 分类控制器
 */
class Category extends Controller
{
    /**
     * 获取分类
     */
    public function index()
    {
        // 文章分类
        $model = new CategoryModel;
        $category = $model->getAll();
        return $this->renderSuccess('', compact('category'));
    }

    /**
     * 添加文章分类
     */
    public function add()
    {
        $model = new CategoryModel;
        // 新增记录
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑文章分类
     */
    public function edit($category_id)
    {
        // 分类详情
        $model = CategoryModel::detail($category_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文章分类
     */
    public function delete($category_id)
    {
        $model = CategoryModel::detail($category_id);
        if (!$model->remove()) {
            return $this->renderError('该分类下存在文章，删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
}