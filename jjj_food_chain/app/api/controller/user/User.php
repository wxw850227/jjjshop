<?php

namespace app\api\controller\user;

use app\api\model\user\User as UserModel;
use app\api\controller\Controller;
use app\common\library\easywechat\AppWx;

/**
 * 用户管理模型
 */
class User extends Controller
{
    /**
     * 用户自动登录,默认微信小程序
     */
    public function login()
    {
        $model = new UserModel;
        $userInfo = $model->login($this->request->post());
        return $this->renderSuccess('', [
            'user_id' => $userInfo['user_id'],
            'mobile' => $userInfo['mobile']
        ]);
    }

    /**
     * 有手机号用户登录
     */
    public function userLogin($code)
    {
        $model = new UserModel;
        $user_id = $model->userLogin($code);
        return $this->renderSuccess('', [
            'user_id' => $user_id,
            'token' => $model->getToken()
        ]);
    }

    public function getSession($code)
    {
        // 微信登录 获取session_key
        $app = AppWx::getApp();
        $session_key = null;
        $session = AppWx::sessionKey($app, $code);
        if ($session != null) {
            $session_key = $session['session_key'];
        }
        return $this->renderSuccess('', compact('session_key'));
    }

    /**
     * 绑定手机号
     */
    public function bindMobile()
    {
        $model = (new UserModel());
        $user_id = $model->bindMobile($this->request->post());
        if ($user_id) {
            return $this->renderSuccess('', [
                'token' => $model->getToken(),
                'user_id' => $user_id
            ]);
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 修改用户资料
     */
    public function updateInfo()
    {
        $model = $this->getUser();
        if ($model->updateInfo($this->request->post())) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 退出登录
     */
    public function logOut($token)
    {
        $model = $this->getUser();
        if ($model->logOut($token)) {
            return $this->renderSuccess('退出成功');
        }
        return $this->renderError($model->getError() ?: '退出失败');
    }
}