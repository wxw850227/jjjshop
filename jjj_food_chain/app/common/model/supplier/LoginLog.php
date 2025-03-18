<?php

namespace app\common\model\supplier;

use app\common\model\BaseModel;

/**
 * 登录日志模型
 */
class LoginLog extends BaseModel
{
    protected $name = 'supplier_login_log';
    protected $pk = 'login_log_id';

    /**
     * 新增登录日志
     */
    public static function add($user, $ip, $result){
        $model = new self();
        $model->save([
            'username' => $user['user_name'],
            'shop_supplier_id' => $user['shop_supplier_id'],
            'ip' => $ip,
            'result' => $result,
            'app_id' => self::$app_id
        ]);
    }
}