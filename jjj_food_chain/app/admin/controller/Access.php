<?php

namespace app\admin\controller;

use app\admin\model\Access as AccesscModel;

/**
 * 商家用户权限控制器
 */
class Access extends Controller
{
    /**
     * 权限列表
     */
    public function index()
    {
        $model = new AccesscModel;
        $list = $model->getList();
        return $this->renderSuccess('', $list);
    }

    /**
     * 添加权限
     */
    public function add()
    {
        $model = new AccesscModel;
        $data = $this->postData();

        if ($model->add($data)) {
            return $this->renderSuccess('添加成功', compact('model'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新权限
     */
    public function edit()
    {
        $data = $this->postData();
        // 权限详情
        $model = AccesscModel::detail($data['access_id']);
        // 更新记录
        if ($model->edit($data)) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除权限
     */
    public function delete($access_id)
    {
        $model = new  AccesscModel();
        $num = $model->getChildCount(['parent_id' => $access_id]);
        if ($num > 0) {
            return $this->renderError('当前菜单下存在子权限，请先删除');
        }
        if ($model->remove($access_id)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    /**
     * 权限状态
     */
    public function status($access_id, $status)
    {
        $model = AccesscModel::detail($access_id);
        if ($model->status($status)) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 门店菜单状态
     */
    public function supplier($access_id, $status)
    {
        $model = AccesscModel::detail($access_id);
        if ($model->supplier($status)) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }
}