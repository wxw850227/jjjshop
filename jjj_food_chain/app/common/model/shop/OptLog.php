<?php

namespace app\common\model\shop;

use app\common\model\BaseModel;

/**
 * 登录日志模型
 */
class OptLog extends BaseModel
{
    protected $name = 'shop_opt_log';
    protected $pk = 'opt_log_id';

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo('app\\common\\model\\shop\\User', 'shop_user_id', 'shop_user_id');
    }
}