<?php

namespace app\common\model\shop;

use app\common\model\BaseModel;

class User extends BaseModel
{
    //表名
    protected $name = 'shop_user';
    //主键字段名
    protected $pk = 'shop_user_id';

    /**
     * 关联应用表
     */
    public function app()
    {
        return $this->belongsTo("app\\common\\model\\app\\App", 'app_id', 'app_id');
    }

    /**
     * 详情
     */
    public static function detail($where, $with = [])
    {
        !is_array($where) && $where = ['shop_user_id' => (int)$where];
        return static::where(array_merge(['is_delete' => 0], $where))->with($with)->find();
    }

    /**
     * 保存登录状态
     */
    public function loginState($user)
    {
        $app = $user['app'];
        // 保存登录状态
        $session = array(
            'user' => [
                'shop_user_id' => $user['shop_user_id'],
                'user_name' => $user['user_name'],
            ],
            'app' => $app->toArray(),
            'is_login' => true,
        );
        session('jjjshop_store', $session);
    }
}