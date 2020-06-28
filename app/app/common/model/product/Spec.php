<?php

namespace app\common\model\product;

use app\common\model\BaseModel;
/**
 * 规格/属性(组)模型
 */
class Spec extends BaseModel
{
    //表名
    protected $name = 'spec';
    //主键字段名
    protected $pk = 'spec_id';
    //关闭更新时间
    protected $updateTime = false;

}
