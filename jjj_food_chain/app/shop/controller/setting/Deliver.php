<?php

namespace app\shop\controller\setting;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 配送控制器
 */
class Deliver extends Controller
{
    /**
     * 配送设置
     */
    public function index()
    {
        if($this->request->isGet()){
            $vars['values'] = SettingModel::getSupplierItem('deliver',$this->store['user']['shop_supplier_id']);
            return $this->renderSuccess('', compact('vars'));
        }
        $model = new SettingModel;
        $data = $this->postData();
        $arr['default'] = $data['radio'];
        $arr['engine'] = $data['engine'];
        if ($model->edit('deliver', $arr,$this->store['user']['shop_supplier_id'])) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}
