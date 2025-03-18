<?php

namespace app\shop\controller;

use app\common\exception\BaseException;
use app\common\model\settings\Setting;
use app\common\model\shop\OptLog as OptLogModel;
use app\JjjController;
use app\shop\model\shop\User as UserModel;
use app\shop\service\AuthService;
use think\facade\Cache;

/**
 * 商户后台控制器基类
 */
class Controller extends JjjController
{
    /** @var array $store 商家登录信息 */
    protected $store;

    /** @var string $route 当前控制器名称 */
    protected $controller = '';

    /** @var string $route 当前方法名称 */
    protected $action = '';

    /** @var string $route 当前路由uri */
    protected $routeUri = '';

    /** @var string $route 当前路由：分组名称 */
    protected $group = '';

    /** @var string $route 当前路由：分组名称 */
    protected $menu = '';
    /** @var array $allowAllAction 登录验证白名单 */
    protected $allowAllAction = [
        // 登录页面
        '/passport/login',
        /*登录信息*/
        '/index/base'
    ];

    /**
     * 后台初始化
     */
    public function initialize()
    {
        // 当前路由信息
        $this->getRouteinfo();
        //  验证登录状态
        $this->checkLogin();
        // 写入操作日志
        $this->saveOptLog();
        // 验证当前页面权限
        $this->checkPrivilege();
    }

    /**
     * 验证当前页面权限
     */
    private function checkPrivilege()
    {
        if ($this->store == null) {
            return false;
        }
        $AuthService = new AuthService($this->store);
        if (!$AuthService->checkPrivilege($this->routeUri)) {
            throw new BaseException(['msg' => '很抱歉，没有访问权限']);
        }
        return true;
    }

    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteinfo()
    {
        // 控制器名称
        $this->controller = strtolower($this->request->controller());
        $this->controller = str_replace(".", "/", $this->controller);
        // 方法名称
        $this->action = Request()->action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = '/' . $this->controller . '/' . $this->action;
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
            $token = Request()->param('token');
        }
        if (!$token) {
            throw new BaseException(['msg' => '缺少必要的参数：token', 'code' => -1]);
        }
        $tokenStatus = Cache::get('shop_token_' . $token);
        if (!$tokenStatus) {
            throw new BaseException(['msg' => 'token失效', 'code' => -1]);
        }
        $data = checkToken($token, 'shop');
        // 验证登录状态
        if ($data['code'] != 1) {
            throw new BaseException(['msg' => $data['msg'], 'code' => -1]);
        }
        if ($data['data']['type'] != 'shop') {
            throw new BaseException(['msg' => '用户信息错误', 'code' => -1]);
        }
        if (!$user = UserModel::getUser($data['data'])) {
            throw new BaseException(['msg' => '没有找到用户信息', 'code' => -1]);
        }
        // 保存登录状态
        $this->store = [
            'user' => [
                'shop_user_id' => $user['shop_user_id'],
                'user_name' => $user['user_name'],
                'shop_supplier_id' => $user['shop_supplier_id'],
                'user_type' => $user['user_type'],
            ],
            'supplier' => [
                'name' => isset($user['supplier']) && $user['supplier'] ? $user['supplier']['name'] : '',
                'category_set' => isset($user['supplier']) && $user['supplier'] ? $user['supplier']['category_set'] : 10,
                'is_main' => isset($user['supplier']) && $user['supplier'] ? $user['supplier']['is_main'] : 1,
            ],
            'app' => $user['app']->toArray(),
        ];
        return true;
    }

    /**
     * 操作日志
     */
    private function saveOptLog()
    {
        if ($this->store == null) {
            return;
        }
        $shop_user_id = $this->store['user']['shop_user_id'];
        if (!$shop_user_id) {
            return;
        }
        // 如果不记录查询日志
        $config = Setting::getItem('store');
        if (!$config || !$config['is_get_log']) {
            return;
        }
        $model = new OptLogModel();
        $model->save([
            'shop_user_id' => $shop_user_id,
            'ip' => \request()->ip(),
            'request_type' => $this->request->isGet() ? 'Get' : 'Post',
            'url' => $this->routeUri,
            'content' => json_encode($this->request->param(), JSON_UNESCAPED_UNICODE),
            'browser' => get_client_browser(),
            'agent' => $_SERVER['HTTP_USER_AGENT'],
            'title' => (new AuthService($this->store))::getAccessNameByPath($this->routeUri, $this->store['app']['app_id']),
            'app_id' => $this->store['app']['app_id']
        ]);
    }

}
