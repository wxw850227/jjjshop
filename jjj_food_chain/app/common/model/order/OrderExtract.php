<?php

namespace app\common\model\order;
use app\common\model\BaseModel;
/**
 * 自提订单联系方式记录模型
 */
class OrderExtract extends BaseModel
{
    protected $name = 'order_extract';
    protected $pk = 'id';
    protected $updateTime = false;
}