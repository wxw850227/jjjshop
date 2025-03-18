<?php

namespace app\common\model\shop;

use app\common\model\BaseModel;

/**
 * 商家用户模型
 */
class User extends BaseModel
{
    protected $name = 'shop_user';
    protected $pk = 'shop_user_id';

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
        return $this->hasMany('app\\common\\model\\shop\\UserRole', 'shop_user_id', 'shop_user_id');
    }

    /**
     * 关联门店表
     */
    public function supplier()
    {
        return $this->belongsTo('app\\common\\model\\supplier\\Supplier', 'shop_supplier_id', 'shop_supplier_id');
    }

    /**
     * 验证用户名是否重复
     */
    public static function checkExist($user_name)
    {
        return !!static::withoutGlobalScope()
            ->where('is_delete', '=', 0)
            ->where('user_name', '=', $user_name)
            ->value('shop_user_id');
    }

    /**
     * 商家用户详情
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
                'shop_supplier_id' => $user['shop_supplier_id'],
                'user_type' => $user['user_type'],
            ],
            'supplier' => [
                'name' => isset($user['supplier']) && $user['supplier'] ? $user['supplier']['name'] : '',
                'category_set' => isset($user['supplier']) && $user['supplier'] ? $user['supplier']['category_set'] : 10,
                'is_main' => isset($user['supplier']) && $user['supplier'] ? $user['supplier']['is_main'] : 1,
            ],
            'app' => $app->toArray(),
            'is_login' => true,
        );
        session('jjjshop_store', $session);
    }
}