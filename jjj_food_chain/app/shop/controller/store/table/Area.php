<?php

namespace app\shop\controller\store\table;

use app\shop\controller\Controller;
use app\shop\model\store\TableArea as TableAreaModel;

/**
 * 区域控制器
 */
class Area extends Controller
{

    /**
     * 区域列表
     */
    public function index()
    {
        $model = new TableAreaModel;
        $list = $model->getList($this->postData(), $this->store['user']['shop_supplier_id']);
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = new TableAreaModel;
        //传过来的信息
        $data = $this->postData();
        $data['shop_supplier_id'] = $this->store['user']['shop_supplier_id'];
        // 新增记录
        if ($model->add($data)) {
            return $this->renderSuccess('', '添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑
     */
    public function edit($area_id)
    {
        $model = TableAreaModel::detail($area_id);
        //编辑
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('', '更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');

    }

    /**
     * 删除
     */
    public function delete($area_id)
    {
        // 详情
        $model = TableAreaModel::detail($area_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('', $model->getError() ?: '删除成功');
    }

}
