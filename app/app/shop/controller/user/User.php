<?php

namespace app\shop\controller\user;

use app\shop\controller\Controller;
use app\shop\model\user\User as UserModel;

/**
 * 用户管理
 */
class User extends Controller
{
    /**
     * 商户列表
     */
    public function lists()
    {
        $model = new UserModel();
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }


    /**
     * 删除用户
     */
    public function delete($user_id)
    {
        // 用户详情
        $model = UserModel::detail($user_id);
        if ($model && $model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }


}
