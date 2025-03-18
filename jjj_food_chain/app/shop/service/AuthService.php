<?php

namespace app\shop\service;

use app\common\model\shop\Access;
use think\facade\Cache;
use think\facade\Session;
use app\shop\model\auth\User;
use app\shop\model\auth\UserRole;
use app\shop\model\auth\RoleAccess;

/**
 * 商家后台权限业务
 */
class AuthService
{
    // 存放实例
    static public $instance;

    // 商家登录信息
    private $store;

    // 商家用户信息
    private $user;

    // 权限验证白名单
    protected $allowAllAction = [
        // 用户登录
        '/passport/login',
        // 退出登录
        '/passport/logout',
        // 首页
        '/index/index',
        /*登录信息*/
        '/index/base',
        // 修改当前用户信息
        '/passport/editPass',
        // 图片上传
        '/file/file/*',
        '/file/upload/image',
        // 数据选择
        '/data/*',
        // 添加商品规格
        '/product/spec/*',
        // 用户信息
        '/auth/user/getUserInfo',
        // 角色列表
        '/auth/user/getRoleList',
        // 统计
        '/statistics/sales/order',
        '/statistics/sales/product',
        '/statistics/user/scale',
        '/statistics/user/new_user',
        '/statistics/user/pay_user'
    ];

    /** @var array $accessUrls 商家用户权限url */
    private $accessUrls = [];

    /**
     * 公有化获取实例方法
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof AuthService)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 私有化构造方法
     */
    public function __construct($store)
    {
        // 商家登录信息
        $this->store = $store;
        // 当前用户信息
        $this->user = User::detail($this->store['user']['shop_user_id']);
    }

    /**
     * 私有化克隆方法
     */
    private function __clone()
    {
    }

    /**
     * 验证指定url是否有访问权限
     */
    public function checkPrivilege($url, $strict = true)
    {
        if (!is_array($url)):
            return $this->checkAccess($url);
        else:
            foreach ($url as $val):
                if ($strict && !$this->checkAccess($val)) {
                    return false;
                }
                if (!$strict && $this->checkAccess($val)) {
                    return true;
                }
            endforeach;
        endif;
        return true;
    }

    /**
     * @param string $url
     */
    private function checkAccess($url)
    {
        // 超级管理员无需验证
        if ($this->user['is_super']) {
            return true;
        }
        // 验证当前请求是否在白名单
        if (in_array($url, $this->allowAllAction)) {
            return true;
        }

        // 通配符支持
        foreach ($this->allowAllAction as $action) {
            if (strpos($action, '*') !== false
                && preg_match('/^' . str_replace('/', '\/', $action) . '/', $url)
            ) {
                return true;
            }
        }
        // 获取当前用户的权限url列表
        if (!in_array($url, $this->getAccessUrls())) {
            return false;
        }
        return true;
    }

    /**
     * 获取当前用户的权限url列表
     */
    private function getAccessUrls()
    {
        if (empty($this->accessUrls)) {
            // 获取当前用户的角色集
            $roleIds = UserRole::getRoleIds($this->user['shop_user_id']);
            // 根据已分配的权限
            $accessIds = RoleAccess::getAccessIds($roleIds);
            // 获取当前角色所有权限链接
            $this->accessUrls = Access::getAccessUrls($accessIds, $this->user['user_type'], $this->store['supplier']);
        }
        return $this->accessUrls;
    }

    public static function getAccessNameByPath($path, $app_id)
    {
        $arr = Cache::get('path_access_' . $app_id);
        if (!$arr) {
            // 查找访问资源
            $list = (new Access())->withoutGlobalScope()->field(['name', 'path'])->select();
            foreach ($list as $access) {
                $arr[$access['path']] = $access['name'];
            }
            Cache::tag('cache')->set('path_access_' . $app_id, $arr, 3600);
        }
        $url = isset($arr[$path]) ? $arr[$path] : '';
        return $url;
    }
}