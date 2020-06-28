<?php

namespace app\shop\model\product;

use app\common\model\product\SpecValue as SpecValueModel;

/**
 * 规格/属性(值)模型
 */
class SpecValue extends SpecValueModel
{

    /**
     * 根据规格组名称查询规格id
     */
    public function getSpecValueIdByName($spec_id, $spec_value)
    {
        return self::where(compact('spec_id', 'spec_value'))->value('spec_value_id');
    }

    /**
     * 新增规格值
     */
    public function add($spec_id, $spec_value)
    {
        $app_id = self::$app_id;
        return $this->save(compact('spec_value', 'spec_id', 'app_id'));
    }

}
