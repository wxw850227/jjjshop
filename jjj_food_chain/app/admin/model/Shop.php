<?php

namespace app\admin\model;

use app\common\exception\BaseException;
use app\common\model\shop\User as ShopModel;

class Shop extends ShopModel
{
    /**
     * 新增商家用户记录
     */
    public function add($app_id, $data)
    {
        if (self::checkExist($data['user_name'])) {
            $this->error = '商家用户名已存在';
            return false;
        }
        return $this->save([
            'user_name' => $data['user_name'],
            'password' => salt_hash($data['password']),
            'app_id' => $app_id,
            'is_super' => 1
        ]);
    }

    /**
     * 商家用户登录
     */
    public function login($app_id)
    {
        // 验证用户名密码是否正确
        $user = self::detail(['app_id' => $app_id], ['app']);
        if (empty($user)) {
            throw new BaseException(['msg' => '超级管理员用户信息不存在']);
        }
        $this->loginState($user);
    }
}