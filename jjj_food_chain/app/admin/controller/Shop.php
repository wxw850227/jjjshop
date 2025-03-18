<?php

namespace app\admin\controller;

use app\admin\model\app\App as AppModel;
use app\admin\model\Shop as ShopModel;

class Shop extends Controller
{
    /**
     * 小程序列表
     */
    public function index()
    {
        $model = new AppModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 进入商城
     */
    public function enter($app_id)
    {
        $model = new ShopModel;
        $model->login($app_id);
        return redirect('/shop#/home?from=admin');
    }

    /**
     * 添加应用
     */
    public function add()
    {
        $model = new AppModel;
        // 新增记录
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 添加应用
     */
    public function edit($app_id)
    {
        $model = AppModel::detail($app_id);
        // 新增记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 删除小程序
     */
    public function delete($app_id)
    {
        // 小程序详情
        $model = AppModel::detail($app_id);
        if (!$model->setDelete()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /*
     *启用禁用
    */
    public function updateStatus($app_id)
    {
        $model = AppModel::detail($app_id);
        if (!$model->updateStatus()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /*
     *启用禁用
    */
    public function updateWxStatus($app_id)
    {
        $model = AppModel::detail($app_id);
        if (!$model->updateWxStatus()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /*
     *启用连锁
    */
    public function updateChain($app_id)
    {
        $model = AppModel::detail($app_id);
        if (!$model->updateChain()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }
}