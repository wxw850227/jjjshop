<?php

namespace app\shop\model\shop;

use app\admin\model\Access as AccessModel;
use app\common\model\shop\User as UserModel;
use app\shop\model\auth\RoleAccess;
use app\shop\model\auth\User as AuthUserModel;
use app\shop\model\auth\UserRole;

/**
 * 后台管理员登录模型
 */
class User extends UserModel
{
    /**
     *检查登录
     */
    public function checkLogin($user)
    {
        $where['user_name'] = $user['username'];
        $where['password'] = $user['password'];
        $where['is_delete'] = 0;

        if (!$user = $this->where($where)->with(['app'])->find()) {
            return false;
        }
        if (empty($user['app'])) {
            $this->error = '登录失败, 未找到小程序信息';
            return false;
        }
        if ($user['app']['is_recycle']) {
            $this->error = '登录失败, 当前小程序商城已删除';
            return false;
        }
        // 保存登录状态
        $this->loginState($user);
        return true;
    }


    /*
    * 修改密码
    */
    public function editPass($data, $user)
    {
        $user_info = User::detail($user['shop_user_id']);
        if ($data['password'] != $data['confirmPass']) {
            $this->error = '密码错误';
            return false;
        }
        if ($user_info['password'] != salt_hash($data['oldpass'])) {
            $this->error = '两次密码不相同';
            return false;
        }
        $date['password'] = salt_hash($data['password']);
        $this->where('shop_user_id', '=', $user['shop_user_id'])->update($date);
        return true;
    }

}