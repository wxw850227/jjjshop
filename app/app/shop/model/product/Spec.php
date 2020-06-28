<?php

namespace app\shop\model\product;

use app\common\model\product\Spec as SpecModel;

/**
 * 规格/属性(组)模型
 */
class Spec extends SpecModel
{
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
        $app_id = self::$app_id;
        return $this->save(compact('spec_name', 'app_id'));
    }

}
