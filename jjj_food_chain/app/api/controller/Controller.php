<?php

namespace app\api\controller;

use app\api\model\user\User as UserModel;
use app\api\model\App as AppModel;
use app\common\exception\BaseException;
use app\common\library\easywechat\AppMp;
use app\JjjController;
use think\facade\Env;
use think\facade\Cache;

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
        if ($app['expire_time'] != 0 && $app['expire_time'] < time()) {
            throw new BaseException(['msg' => '当前应用已过期']);
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
        if ($user['is_delete'] == 1) {
            throw new BaseException(['msg' => '没有找到用户信息', 'code' => -2]);
        }
        return $user;
    }

    protected function getShareParams($url, $title = '', $desc = '', $link = '', $imgUrl = '')
    {
        $signPackage = '';
        $shareParams = '';
        if (Env::get('APP_DEBUG')) {
            return [
                'signPackage' => $signPackage,
                'shareParams' => $shareParams
            ];
        }
        if ($url != '') {
            $app = AppMp::getApp($this->app_id);
            $utils = $app->getUtils();
            $signPackage = $utils->buildJsSdkConfig(
                url: $url,
                jsApiList: ['updateAppMessageShareData', 'updateTimelineShareData'],
                openTagList: [],
                debug: false,
            );
            $shareParams = [
                'title' => $title,
                'desc' => $desc,
                'link' => $link,
                'imgUrl' => $imgUrl,
            ];
            $signPackage = json_encode($signPackage);
        }
        return [
            'signPackage' => $signPackage,
            'shareParams' => $shareParams
        ];
    }

    protected function getScanParams($url)
    {
        $signPackage = '';
        if (Env::get('APP_DEBUG')) {
            return [
                'signPackage' => $signPackage
            ];
        }
        if ($url != '') {
            $app = AppMp::getApp($this->app_id);
            $utils = $app->getUtils();
            $signPackage = $utils->buildJsSdkConfig(
                url: $url,
                jsApiList: ['scanQRCode'],
                openTagList: [],
                debug: false,
            );
            $signPackage = json_encode($signPackage);
        }
        return [
            'signPackage' => $signPackage
        ];
    }
}
