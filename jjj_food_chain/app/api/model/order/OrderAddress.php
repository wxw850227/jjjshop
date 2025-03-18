<?php

namespace app\api\model\order;

use app\common\model\order\OrderAddress as OrderAddressModel;

/**
 * 订单地址模型
 */
class OrderAddress extends OrderAddressModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
    ];

}