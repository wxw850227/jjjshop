<?php

namespace app\shop\controller\auth;

use app\shop\model\shop\Access as AccessModel;
use app\shop\controller\Controller;

/**
 * 管理员
 */
class User extends Controller
{
    /**
     * 获取角色菜单信息
     */
    public function getRoleList()
    {
        $model = new AccessModel();
        $menus = $model->getList();

        return $this->renderSuccess('', compact('menus'));
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $store = session('jjjshop_store');
        $user = [];
        if (!empty($store)) {
            $user = $store['user'];
        }
        return $this->renderSuccess('', compact('user'));
    }
}
