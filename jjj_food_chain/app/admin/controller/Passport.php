<?php

namespace app\admin\controller;

use app\admin\model\admin\User as UserModel;
use think\facade\Cache;

class Passport extends Controller
{
    /**
     * 超管后台登录
     */
    public function login()
    {
        $model = new UserModel;
        if ($user = $model->login($this->postData())) {
            return $this->renderSuccess('登录成功', [
                'user_name' => $user['user_name'],
                'token' => $user['token']
            ]);
        }
        return $this->renderError('用户名或者密码错误！');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $token = Request()->header('token');
        Cache::delete('admin_token_' . $token);
        return $this->renderSuccess('退出成功');
    }
}