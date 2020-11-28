<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\user\UserMp as UserMpModel;
use app\common\library\easywechat\AppMp;

/**
 * 公众号用户管理
 */
class Usermp extends Controller
{

    /**
     * 用户自动登录
     */
    public function login($referee_id = '')
    {
        $app = AppMp::getApp($this->app_id);
        $redirect_uri = base_url()."index.php/api/user.usermp/login_callback?app_id={$this->app_id}&referee_id={$referee_id}";
        $app->oauth->scopes(['snsapi_userinfo'])->redirect($redirect_uri)->send();
    }

    /**
     * 用户自动登录
     */
    public function login_callback()
    {
        $app = AppMp::getApp($this->app_id);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $userInfo = $oauth->user();
        // 保存数据库
        $model = new UserMpModel;
        $referee_id = $this->request->param('referee_id');
        $user_id = $model->login($userInfo, $referee_id);
        return redirect(base_url().'h5/pages/login/mplogin?token='.$model->getToken().'&user_id='.$user_id);
    }
}
