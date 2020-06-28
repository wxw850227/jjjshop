<?php

namespace app\common\model\settings;

use app\common\model\BaseModel;

/**
 * 配送模板区域及运费模型
 */
class DeliveryRule extends BaseModel
{
    //表名
    protected $name = 'delivery_rule';
    //主键字段名
    protected $pk = 'rule_id';
    //关闭更新时间
    protected $updateTime = false;

    //追加字段
    protected $append = [
        'region_data',  //地区集转为数组格式
    ];

    /**
     * 地区集转为数组格式
     */
    public function getRegionDataAttr($value, $data)
    {
        return explode(',', $data['region']);
    }
}
