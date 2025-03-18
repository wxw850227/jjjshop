<?php

namespace app\admin\controller\plus;

use app\admin\controller\Controller;
use app\admin\model\plus\Category as CategoryModel;
use app\admin\model\Access as AccessModel;
/**
 * 插件控制器
 */
class Plus extends Controller
{
    /**
     *插件列表
     */
    public function index()
    {
        $accessList = CategoryModel::getAll();
        return $this->renderSuccess('', compact('accessList'));
    }

    /**
     *添加插件
     */
    public function add()
    {
        if($this->request->isGet()){
            //查找所有插件
            $accessList = AccessModel::getAllPlus();
            return $this->renderSuccess('', compact('accessList'));
        }
        $model = new AccessModel();
        if ($model->addPlus($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     *删除插件
     */
    public function delete($plus_id)
    {
        $model = AccessModel::detail($plus_id);
        if ($model->removePlus()) {
            return $this->renderSuccess(' 删除成功');
        };
    }

    /**
     *删除分类
     */
    public function deleteCategory($plus_category_id)
    {
        $model = CategoryModel::detail($plus_category_id);
        if ($model->remove()) {
            return $this->renderSuccess(' 删除成功');
        };
    }
}