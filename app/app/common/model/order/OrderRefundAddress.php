<?php

namespace app\common\model\order;

use app\common\model\BaseModel;

class OrderRefundAddress extends BaseModel
{
    //表名
    protected $name = 'order_refund_address';
    //主键字段名
    protected $pk = 'id';
    //关闭更新时间
    protected $updateTime = false;

}

