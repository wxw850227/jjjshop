<?php

namespace app\shop\controller\auth;

use app\shop\model\shop\Access as AccessModel;
use app\common\model\settings\Setting as SettingModel;
use app\shop\controller\Controller;
use app\shop\model\auth\User as UserModel;
use app\shop\model\auth\Role;
use app\shop\model\auth\User as AuthUserModel;

/**
 * 管理员
 */
class User extends Controller
{
    /**
     * 首页列表
     * @return \think\response\Json
     */
    public function index()
    {
        $model = new UserModel();
        $list = $model->getList($this->postData());
        $roleModel = new Role();
        // 角色列表
        $roleList = $roleModel->getTreeData();
        return $this->renderSuccess('', compact('list', 'roleList'));
    }

    /**
     * 新增信息
     * @return \think\response\Json
     */
    public function addInfo()
    {
        $model = new Role();
        // 角色列表
        $roleList = $model->getTreeData();
        return $this->renderSuccess('', compact('roleList'));
    }

    /**
     * 新增
     * @return \think\response\Json
     */
    public function add()
    {
        $data = $this->postData();
        $model = new UserModel();
        $num = $model->getUserName(['user_name' => $data['user_name']]);
        if ($num > 0) {
            return $this->renderError('用户名已存在');
        }
        if (!isset($data['role_id'])) {
            return $this->renderError('请选择所属角色');
        }
        if ($data['confirm_password'] != $data['password']) {
            return $this->renderError('确认密码和登录密码不一致');
        }
        $model = new UserModel();
        if ($model->add($data, $this->store['user'])) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 修改信息
     * @param $shop_user_id
     * @return \think\response\Json
     */
    public function editInfo($shop_user_id)
    {
        $info = UserModel::detail(['shop_user_id' => $shop_user_id], ['UserRole']);

        $role_arr = array_column($info->toArray()['UserRole'], 'role_id');

        $model = new Role();
        // 角色列表
        $roleList = $model->getTreeData();
        return $this->renderSuccess('', compact('info', 'roleList', 'role_arr'));
    }

    /**
     * 编辑
     * @param $shop_user_id
     * @return \think\response\Json
     */
    public function edit($shop_user_id)
    {
        $data = $this->postData();
        if ($this->request->isGet()) {
            return $this->editInfo($shop_user_id);
        }

        $model = new UserModel();
        $num = $model->getUserName(['user_name' => $data['user_name']], $data['shop_user_id']);
        if ($num > 0) {
            return $this->renderError('用户名已存在');
        }
        if (!isset($data['access_id'])) {
            return $this->renderError('请选择所属角色');
        }
        if (isset($data['password']) && !empty($data['password'])) {
            if (!isset($data['confirm_password'])) {
                return $this->renderError('请输入确认密码');
            } else {
                if ($data['confirm_password'] != $data['password']) {
                    return $this->renderError('确认密码和登录密码不一致');
                }
            }
        }
        if (empty($data['password'])) {
            if (isset($data['confirm_password']) && !empty($data['confirm_password'])) {
                return $this->renderError('请输入登录密码');
            }
        }

        // 更新记录
        if ($model->edit($data)) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');


    }

    /**
     * 删除
     */
    public function delete($shop_user_id)
    {
        $model = new UserModel();
        if ($model->del($shop_user_id, $this->store['user'])) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    /**
     * 获取角色菜单信息
     */
    public function getRoleList()
    {
        $user = $this->store['user'];
        $supplier = $this->store['supplier'];
        $menus = [];
        $user_info = AuthUserModel::find($user['shop_user_id']);

        if ($user_info['is_super'] == 1) {
            $model = new AccessModel();
            $menus = $model->getList($user['user_type'], $supplier);
        } else {
            $model = new AccessModel();
            $menus = $model->getListByUser($user['shop_user_id'], $user['user_type'], $supplier);

            foreach ($menus as $key => $val) {
                if ($val['redirect_name'] != $val['children'][0]['path']) {
                    $menus[$key]['redirect_name'] = $menus[$key]['children'][0]['path'];
                }
            }
        }
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
        // 商城名称
        $shop_name = SettingModel::getItem('store')['name'];
        //当前系统版本
        $version = get_version();
        //门店名称
        $supplier_name = $store['supplier']['name'];
        return $this->renderSuccess('', compact('supplier_name', 'user', 'shop_name', 'version'));
    }

    /**
     * 权限状态
     */
    public function setStatus($shop_user_id, $status)
    {
        $model = UserModel::detail($shop_user_id);
        if ($model->setStatus($status)) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }
}
