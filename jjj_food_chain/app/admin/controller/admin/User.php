<?php

namespace app\admin\controller\admin;

use app\admin\controller\Controller;
use think\facade\Session;
use app\admin\model\admin\User as AdminUserModel;

/**
 * 超管后台管理员控制器
 */
class User extends Controller
{
    /**
     * 更新当前管理员信息
     */
    public function renew()
    {
        $model = AdminUserModel::detail($this->admin['user']['admin_user_id']);
        if ($model->renew($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
}