<?php

namespace app\common\model\product;

use app\common\model\BaseModel;

/**
 * 规格/属性(值)模型
 */
class SpecValue extends BaseModel
{
    //表名
    protected $name = 'spec_value';
    //主键字段名
    protected $pk = 'spec_value_id';
    //关闭更新时间
    protected $updateTime = false;

    /**
     * 关联规格组表
     */
    public function spec()
    {
        return $this->belongsTo('Spec');
    }

}
