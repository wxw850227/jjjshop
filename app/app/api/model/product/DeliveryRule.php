<?php

namespace app\api\model\product;

use app\common\model\settings\DeliveryRule as DeliveryRuleModel;
/**
 * 配送模板区域及运费模型
 */
class DeliveryRule extends DeliveryRuleModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];



}