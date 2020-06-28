<?php

namespace app\common\model\settings;

use app\common\model\BaseModel;

/**
 * 退货地址模型
 */
class ReturnAddress extends BaseModel
{
    //表名
    protected $name = 'return_address';
    //主键字段名
    protected $pk = 'address_id';

    /**
     * 退货地址详情
     */
    public static function detail($address_id)
    {
        return self::find($address_id);
    }

}