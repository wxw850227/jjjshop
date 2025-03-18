<?php

namespace app\api\controller\user;

use app\api\model\user\UserAddress;
use app\api\controller\Controller;

/**
 * 收货地址控制器
 */
class Address extends Controller
{
    /**
     * 收货地址列表
     */
    public function lists()
    {
        $user = $this->getUser();
        $model = new UserAddress;
        $list = $model->getList($user['user_id']);
        return $this->renderSuccess('', [
            'list' => $list,
            'default_id' => $user['address_id'],
        ]);
    }

    /**
     * 收货地址列表
     */
    public function list($shop_supplier_id)
    {
        $user = $this->getUser();
        $model = new UserAddress;
        $list = $model->list($user['user_id'], $shop_supplier_id);
        return $this->renderSuccess('', [
            'list' => $list,
            'default_id' => $user['address_id'],
        ]);
    }

    /**
     * 添加收货地址
     */
    public function add()
    {
        $data = $this->request->post();
        if ($data['phone'] == '') {
            return $this->renderError('手机号不正确');
        }
        if ($data['name'] == '') {
            return $this->renderError('收货人不能为空');
        }
        if ($data['detail'] == '') {
            return $this->renderError('收货地址不能为空');
        }
        if ($data['address'] == '') {
            return $this->renderError('门牌号不能为空');
        }
        $model = new UserAddress;
        if ($model->add($this->getUser(), $data)) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 收货地址详情
     */
    public function detail($address_id)
    {
        $user = $this->getUser();
        $detail = UserAddress::detail($user['user_id'], $address_id);
        return $this->renderSuccess('', compact('detail'));
    }

    /**
     * 编辑收货地址
     */
    public function edit($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->edit($this->request->post())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }

    /**
     * 设为默认地址
     */
    public function setDefault($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->setDefault($user)) {
            return $this->renderSuccess('设置成功');
        }
        return $this->renderError('设置失败');
    }

    /**
     * 删除收货地址
     */
    public function delete($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->remove($user)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }

}