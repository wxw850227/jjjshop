<?php

namespace app\api\controller;

use app\api\model\user\User as UserModel;
use app\api\model\App as AppModel;
use app\common\exception\BaseException;
use app\common\library\easywechat\AppMp;
use app\JjjController;
use think\facade\Env;

/**
 * API控制器基类
 */
class Controller extends JjjController
{

    // app_id
    protected $app_id;

    /**
     * 后台初始化
     */
    public function initialize()
    {
        // 当前小程序id
        $this->app_id = $this->getAppId();
        // 验证当前小程序状态
        $this->checkWxapp();
    }

    /**
     * 获取当前应用ID
     */
    private function getAppId()
    {
        if (!$app_id = $this->request->param('app_id')) {
            throw new BaseException(['msg' => '缺少必要的参数：app_id']);
        }
        return $app_id;
    }

    /**
     * 验证当前小程序状态
     */
    private function checkWxapp()
    {
        $app = AppModel::detail($this->app_id);
        if (empty($app)) {
            throw new BaseException(['msg' => '当前应用信息不存在']);
        }
        if ($app['is_recycle'] || $app['is_delete']) {
            throw new BaseException(['msg' => '当前应用已删除']);
        }
    }

    /**
     * 获取当前用户信息
     */
    protected function getUser($is_force = true)
    {
        if (!$token = $this->request->param('token')) {
            if ($is_force) {
                throw new BaseException(['msg' => '缺少必要的参数：token', 'code' => -1]);
            }
            return false;
        }
        if (!$user = UserModel::getUser($token)) {
            if ($is_force) {
                throw new BaseException(['msg' => '没有找到用户信息', 'code' => -1]);
            }
            return false;
        }
        return $user;
    }

}
