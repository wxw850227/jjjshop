<?php

namespace app\shop\controller\product\expand;

use app\shop\controller\Controller;
use app\shop\model\product\Unit as UnitModel;

/**
 * 单位控制器
 */
class Unit extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $model = new UnitModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加单位
     */
    public function add()
    {
        $model = new UnitModel();
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑
     */
    public function edit($unit_id)
    {
        // 详情
        $model = UnitModel::detail($unit_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除商品
     */
    public function delete($unit_id)
    {
        $model = new UnitModel;
        if (!$model->setDelete($unit_id)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
