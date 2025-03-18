<?php

namespace app\admin\controller;

use app\admin\model\admin\User as UserModel;
use app\common\exception\BaseException;
use app\JjjController;
use think\facade\Cache;

/**
 * 商户后台控制器基类
 */
class Controller extends JjjController
{

    // 商家登录信息
    protected $admin;

    // 当前控制器名称
    protected $controller = '';

    // 当前方法名称
    protected $action = '';

    // 当前路由uri
    protected $routeUri = '';

    // 当前路由：分组名称
    protected $group = '';

    // 登录验证白名单
    protected $allowAllAction = [
        // 登录页面
        'passport/login',
    ];

    /**
     * 后台初始化
     */
    public function initialize()
    {
        // 当前路由信息
        $this->getRouteinfo();
        // 验证登录
        $this->checkLogin();
    }

    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteinfo()
    {
        // 控制器名称
        $this->controller = toUnderScore(Request()->controller());
        // 方法名称
        $this->action = Request()->action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = $this->controller . '/' . $this->action;
    }

    /**
     * 验证登录状态
     */
    private function checkLogin()
    {
        // 验证当前请求是否在白名单
        if (in_array($this->routeUri, $this->allowAllAction)) {
            return true;
        }
        $token = Request()->header('token');
        if (!$token) {
            throw new BaseException(['msg' => '缺少必要的参数：token', 'code' => -1]);
        }
        $tokenStatus = Cache::get('admin_token_' . $token);
        if (!$tokenStatus) {
            throw new BaseException(['msg' => 'token失效', 'code' => -1]);
        }
        $data = checkToken($token, 'admin');
        if ($data['code'] != 1) {
            throw new BaseException(['msg' => $data['msg'], 'code' => -1]);
        }
        if ($data['data']['type'] != 'admin') {
            throw new BaseException(['msg' => '用户信息错误', 'code' => -1]);
        }
        if (!$user = UserModel::getUser($data['data'])) {
            throw new BaseException(['msg' => '没有找到用户信息', 'code' => -1]);
        }
        $this->admin = [
            'user' => [
                'admin_user_id' => $user['admin_user_id'],
                'user_name' => $user['user_name'],
            ],
        ];
        return true;
    }

}
