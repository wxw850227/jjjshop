<?php

namespace app\shop\model\product;

use app\common\model\product\Spec as SpecModel;

/**
 * 规格/属性(组)模型
 */
class Spec extends SpecModel
{
    /**
     * 获取列表数据
     */
    public function getList($data)
    {
        $model = $this;
        return $model
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($data);
    }

    /**
     * 根据规格组名称查询规格id
     */
    public function getSpecIdByName($spec_name)
    {
        return self::where(compact('spec_name'))->value('spec_id');
    }

    /**
     * 新增规格组
     */
    public function add($spec_name)
    {
        $isExist = $this->where('spec_name', '=', $spec_name)->count();
        if ($isExist) {
            $this->error = '名称已存在';
            return false;
        }
        $app_id = self::$app_id;
        return $this->save(compact('spec_name', 'app_id'));
    }

    /**
     * 修改
     */
    public function edit($data)
    {
        $isExist = $this->where('spec_name', '=', $data['spec_name'])->where('spec_id', '<>', $this['spec_id'])->count();
        if ($isExist) {
            $this->error = '名称已存在';
            return false;
        }
        return $this->save($data);
    }

    /**
     * 删除
     */
    public function setDelete($spec_id)
    {
        return $this->where('spec_id', 'in', $spec_id)->delete();
    }
}
