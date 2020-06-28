<?php

namespace app\common\model\order;

use app\common\model\settings\Region;
use app\common\model\BaseModel;

/**
 * Class OrderAddress
 */
class OrderAddress extends BaseModel
{
    //表名
    protected $name = 'order_address';
    //主键字段名
    protected $pk = 'order_address_id';
    //关闭更新时间
    protected $updateTime = false;

    /**
     * 追加字段
     */
    protected $append = [
        'region',   //完整地址
    ];

    /**
     * 地区名称
     */
    public function getRegionAttr($value, $data)
    {
        return [
            'province' => Region::getNameById($data['province_id']),
            'city' => Region::getNameById($data['city_id']),
            'region' => $data['region_id'] == 0 ? '' : Region::getNameById($data['region_id']),
        ];
    }

    /**
     * 获取完整地址
     */
    public function getFullAddress()
    {
        return $this['region']['province'] . $this['region']['city'] . $this['region']['region'] . $this['detail'];
    }

}