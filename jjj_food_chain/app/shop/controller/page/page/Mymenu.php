<?php

namespace app\shop\controller\page\page;

use app\shop\controller\Controller;
use app\shop\model\page\CenterMenu as CenterMenuModel;

/**
 * 我的菜单控制器
 */
class Mymenu extends Controller
{
    /**
     * 广告数据
     */
    public function index()
    {
        $model = new CenterMenuModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加广告
     */
    public function add()
    {
        // 新增记录
        $model = new CenterMenuModel;
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 详情
     */
    public function detail($menu_id)
    {
        // 详情
        $detail = CenterMenuModel::detail($menu_id);
        return $this->renderSuccess('', compact('detail'));

    }

    /**
     * 修改
     */
    public function edit($menu_id)
    {
        $model = CenterMenuModel::detail($menu_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除记录
     */
    public function delete($menu_id)
    {
        $model = CenterMenuModel::detail($menu_id);
        if ($model->remove()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

}
