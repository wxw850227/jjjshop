<?php

namespace app\common\model\order;

use app\common\model\settings\Region;
use app\common\model\BaseModel;

/**
 * Class OrderAddress
 */
class OrderAddress extends BaseModel
{
    protected $name = 'order_address';
    protected $pk = 'order_address_id';
    protected $updateTime = false;

    /**
     * 追加字段
     * @var string[]
     */
    protected $append = ['region'];

    /**
     * 地区名称
     * @param $value
     * @param $data
     * @return array
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
     * @return string
     */
    public function getFullAddress()
    {
        return $this['detail'].$this['address'];
    }
    
    //修改收货人信息
    public function updateAddress($data){
        return $this->save($data);
    }

}