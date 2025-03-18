<?php

namespace app\shop\controller\store\table;

use app\shop\controller\Controller;
use app\shop\model\store\Table as TableModel;
use app\shop\model\store\TableArea as TableAreaModel;
use app\shop\model\store\TableType as TableTypeModel;
use app\common\service\qrcode\TableService;

/**
 * 桌位控制器
 */
class Table extends Controller
{

    /**
     * 桌位列表
     */
    public function index()
    {
        // 桌位列表
        $model = new TableModel;
        $list = $model->getList($this->postData(), $this->store['user']['shop_supplier_id']);
        // 区域列表
        $area_list = TableAreaModel::getAllList($this->store['user']['shop_supplier_id']);
        // 类型列表
        $type_list = TableTypeModel::getAllList($this->store['user']['shop_supplier_id']);
        return $this->renderSuccess('', compact('list', 'area_list', 'type_list'));
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = new TableModel;
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
    public function edit($table_id)
    {
        $model = TableModel::detail($table_id);
        //编辑店员的数据
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('', '更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');

    }

    /**
     * 删除
     */
    public function delete($table_id)
    {
        // 详情
        $model = TableModel::detail($table_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('', $model->getError() ?: '删除成功');
    }

    /**
     * 二维码
     */
    public function qrcode($id, $source)
    {
        $Qrcode = new TableService($id, $source);
        $Qrcode->getImage();
    }

}
