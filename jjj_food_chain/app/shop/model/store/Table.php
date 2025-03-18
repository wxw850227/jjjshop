<?php


namespace app\shop\model\store;

use app\common\model\store\Table as TableModel;
use app\shop\controller\store\table\Area;
use app\shop\controller\store\table\Type;

/**
 * 桌位模型
 */
class Table extends TableModel
{

    const FORM_SCENE_ADD = 'add';
    const FORM_SCENE_EDIT = 'edit';

    /**
     * 获取列表数据
     */
    public function getList($params,$shop_supplier_id)
    {
        $model = $this;
        if (isset($params['area_id']) && $params['area_id']) {
            $model = $model->where('area_id', '=', $params['area_id']);
        }
        if (isset($params['type_id']) && $params['type_id']) {
            $model = $model->where('type_id', '=', $params['type_id']);
        }
        if (!empty($params['search']) && $params['search']) {
            $model = $model->where('table_no', 'like', '%' . $params['search'] . '%');
        }
        // 查询列表数据
        return $model->where('shop_supplier_id', '=', $shop_supplier_id)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($params);
    }

    /**
     * 新增记录
     */
    public function add($data)
    {
        // 表单验证
        if (!$this->validateForm($data, self::FORM_SCENE_ADD)) {
            return false;
        }
        $data = $this->sortData($data);
        $data['app_id'] = self::$app_id;
        return self::create($data);
    }

    /**
     * 编辑记录
     */
    public function edit($data)
    {
        // 表单验证
        if (!$this->validateForm($data, self::FORM_SCENE_EDIT)) {
            return false;
        }
        $data = $this->sortData($data);
        return $this->save($data);
    }

    /**
     * 删除
     */
    public function setDelete()
    {
        return $this->delete();
    }

    /**
     * 表单验证
     */
    private function validateForm($data, $scene = self::FORM_SCENE_ADD)
    {
        if ($scene === self::FORM_SCENE_ADD) {
            //查询桌号是否存在
            $count = $this->where('shop_supplier_id', '=', $data['shop_supplier_id'])
                ->where('area_id', '=', $data['area_id'])
                ->where('table_no', '=', $data['table_no'])
                ->count();
            if ($count) {
                $this->error = '桌号已存在';
                return false;
            }
        } else {
            $count = $this->where('shop_supplier_id', '=', $this['shop_supplier_id'])
                ->where('area_id', '=', $data['area_id'])
                ->where('table_no', '=', $data['table_no'])
                ->where('table_id', '<>', $data['table_id'])
                ->count();
            if ($count) {
                $this->error = '桌号已存在';
                return false;
            }
        }
        return true;
    }
    //整理数据
    public function sortData($data){
        $data['area_name'] = (new TableArea)->where('area_id','=',$data['area_id'])->value('area_name');
        $typeInfo = (new TableType)->where('type_id','=',$data['type_id'])->field('type_name,min_num,max_num')->find();
        $data['min_num']=$typeInfo['min_num'];
        $data['max_num']=$typeInfo['max_num'];
        $data['type_name']=$typeInfo['type_name'];
        return $data;
    }

}