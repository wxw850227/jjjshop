<?php

namespace app\common\model\supplier;

use app\common\model\BaseModel;

/**
 * 商家用户模型
 */
class User extends BaseModel
{
    protected $name = 'supplier_user';
    protected $pk = 'supplier_user_id';

    /**
     * 关联应用表
     */
    public function app()
    {
        return $this->belongsTo('app\\common\\model\\app\\App', 'app_id', 'app_id');
    }

    /**
     * 关联用户角色表表
     */
    public function role()
    {
        return $this->belongsToMany('app\\common\\model\\auth\\Role', 'app\\common\\model\\auth\\UserRole');
    }

    public function userRole()
    {
        return $this->hasMany('app\\common\\model\\supplier\\UserRole', 'supplier_user_id', 'supplier_user_id');
    }

    /**
     * 关联应用表
     */
    public function user()
    {
        return $this->belongsTo('app\\common\\model\\user\\User', 'user_id', 'user_id');
    }

    /**
     * 验证用户名是否重复
     */
    public static function checkExist($user_name)
    {
        return !!static::withoutGlobalScope()
            ->where('user_name', '=', $user_name)
            ->value('supplier_user_id');
    }

    /**
     * 商家用户详情
     */
    public static function detail($where, $with = [])
    {
        !is_array($where) && $where = ['supplier_user_id' => (int)$where];
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
                'supplier_user_id' => $user['supplier_user_id'],
                'user_name' => $user['user_name'],
                'shop_supplier_id' => $user['shop_supplier_id'],
                'app_id' => $user['app_id'],
                'user_id'=>$user['user_id'],
            ],
            'app' => $app->toArray(),
            'is_login' => true,
        );
        session('jjjshop_supplier', $session);
    }
}