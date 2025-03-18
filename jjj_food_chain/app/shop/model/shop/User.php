<?php

namespace app\shop\model\shop;

use app\common\model\shop\LoginLog as LoginLogModel;
use app\common\model\shop\User as UserModel;
use think\facade\Cache;

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
        if (!$user = $this->where($where)->with(['app', 'supplier'])->find()) {
            $this->error = '账号或密码错误，请重新输入';
            return false;
        }
        if ($user['is_status'] == 1) {
            $this->error = '账号被禁用，请联系平台';
            return false;
        }
        if (empty($user['app'])) {
            $this->error = '登录失败, 未找到应用信息';
            return false;
        }
        if ($user['app']['is_delete']) {
            $this->error = '登录失败, 当前应用已删除';
            return false;
        }
        if ($user['app']['is_recycle']) {
            $this->error = '登录失败, 当前应用已禁用';
            return false;
        }
        if ($user['app']['expire_time'] != 0 && $user['app']['expire_time'] < time()) {
            $this->error = '登录失败, 当前应用已过期，请联系平台续费';
            return false;
        }
        // 保存登录状态
        $user['token'] = signToken($user['shop_user_id'], 'shop');
        Cache::tag('cache')->set('shop_token_' . $user['token'], $user['shop_user_id'], 86400 * 30);
        // 写入登录日志
        LoginLogModel::add($where['user_name'], \request()->ip(), '登录成功', $user['app']['app_id'], $user['shop_supplier_id']);
        return $user;
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
        $user_info->save($date);
        return true;
    }

    /**
     * 获取用户信息
     */
    public static function getUser($data)
    {
        return (new static())->where('shop_user_id', '=', $data['uid'])->with(['app'])->find();
    }

}