<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\user\UserOpen as UserOpenModel;

/**
 * app用户管理
 */
class Useropen extends Controller
{
    /**
     * 手机号码登录
     */
    public function phonelogin()
    {
        $data = $this->request->post();
        $model = new UserOpenModel;
        $user_id = $model->phoneLogin($data);
        if ($user_id) {
            return $this->renderSuccess('', [
                'user_id' => $user_id,
                'token' => $model->getToken()
            ]);
        }
        return $this->renderError($model->getError() ?: '登录失败');
    }

    /**
     * 手机号码注册
     */
    public function register()
    {
        $data = $this->request->post();
        $model = new UserOpenModel;
        if ($user_id = $model->phoneRegister($data)) {
            return $this->renderSuccess('', [
                'user_id' => $user_id,
                'token' => $model->getToken()
            ]);
        }
        return $this->renderError($model->getError() ?: '注册失败');
    }
}
