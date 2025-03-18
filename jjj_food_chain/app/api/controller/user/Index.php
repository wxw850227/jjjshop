<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\user\User as UserModel;

/**
 * 个人中心主页
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     */
    public function detail()
    {
        // 当前用户信息
        $user = $this->getUser();
        return $this->renderSuccess('', [
            'userInfo' => $user,
            'menus' => UserModel::getMenus(),   // 个人中心菜单列表
        ]);
    }

    /**
     * 当前用户设置
     */
    public function setting()
    {
        // 当前用户信息
        $user = $this->getUser();
        return $this->renderSuccess('', [
            'userInfo' => $user
        ]);
    }
}