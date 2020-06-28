<?php

namespace app\common\model\product;

use app\common\model\BaseModel;
/**
 * 商品规格关系模型
 */
class ProductSpecRel extends BaseModel
{
    //表名
    protected $name = 'product_spec_rel';
    //主键字段名
    protected $pk = 'id';
    //关闭更新时间
    protected $updateTime = false;

    /**
     * 关联规格组
     */
    public function spec()
    {
        return $this->belongsTo('Spec');
    }

}
