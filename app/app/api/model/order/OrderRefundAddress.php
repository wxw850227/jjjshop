<?php

namespace app\api\model\order;

use app\common\model\order\OrderRefundAddress as OrderRefundAddressModel;

/**
 * 售后单退货地址模型
 */
class OrderRefundAddress extends OrderRefundAddressModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time'
    ];

}