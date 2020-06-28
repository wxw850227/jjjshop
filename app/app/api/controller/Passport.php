<?php

namespace app\api\controller;

use app\api\model\user\User as UserModel;

/**
 * 用户管理
 */
class Passport extends Controller
{
    /**
     * 用户自动登录,默认微信小程序
     */
    public function login()
    {
        $model = new UserModel;
        return $this->renderSuccess('',[
            'user_id' => $model->login($this->request->post()),
            'token' => $model->getToken()
        ]);
    }

}
