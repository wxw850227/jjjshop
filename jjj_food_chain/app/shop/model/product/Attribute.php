<?php

namespace app\shop\model\product;

use app\common\model\product\Attribute as AttributeModel;

/**
 * 规格/属性(组)模型
 */
class Attribute extends AttributeModel
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
     * 添加
     */
    public function add($data)
    {
        $isExist = $this->where('attribute_name', '=', $data['attribute_name'])->count();
        if ($isExist) {
            $this->error = '名称已存在';
            return false;
        }
        $data['app_id'] = self::$app_id;
        return $this->save($data);
    }

    /**
     * 修改
     */
    public function edit($data)
    {
        $isExist = $this->where('attribute_name', '=', $data['attribute_name'])->where('attribute_id', '<>', $this['attribute_id'])->count();
        if ($isExist) {
            $this->error = '名称已存在';
            return false;
        }
        return $this->save($data);
    }

    /**
     * 删除
     */
    public function setDelete($attribute_id)
    {
        return $this->where('attribute_id', 'in', $attribute_id)->delete();
    }
}
