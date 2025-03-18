<?php

namespace app\shop\controller\supplier;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;
use app\shop\model\supplier\Supplier as SupplierModel;
use app\common\enum\settings\DeliveryTypeEnum;

/**
 * 商家控制器
 */
class Supplier extends Controller
{

    /**
     * 商家列表
     */
    public function index()
    {
        // 商家列表
        $model = new SupplierModel;
        $list = $model->getList($this->postData(), $this->store['user']);
        $is_chain = $this->store['app']['is_chain'];
        return $this->renderSuccess('', compact('list', 'is_chain'));
    }

    /**
     * 编辑供应商
     */
    public function edit($shop_supplier_id)
    {
        $model = SupplierModel::detail($shop_supplier_id, ['superUser', 'user']);
        if ($this->request->isGet()) {
            $model['deliver_set'] = array_keys(DeliveryTypeEnum::data());
            $key = SettingModel::getItem('map')['tx_key'];
            return $this->renderSuccess('', compact('model', 'key'));
        }
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 设置门店
     */
    public function setting($shop_supplier_id)
    {
        $model = SupplierModel::detail($shop_supplier_id, []);
        if ($this->request->isGet()) {
            return $this->renderSuccess('', compact('model'));
        }
        if ($model->setting($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
}
