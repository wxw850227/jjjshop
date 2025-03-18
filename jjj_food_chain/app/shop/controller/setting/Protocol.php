<?php

namespace app\shop\controller\setting;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 协议控制器
 */
class Protocol extends Controller
{
    /**
     * 协议设置
     */
    public function index()
    {
        if ($this->request->isGet()) {
            return $this->fetchData();
        }
        $model = new SettingModel;
        $data = $this->postData();
        $editData[$data['type']] = $data['value'];
        if ($model->edit($data['type'], $editData)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取协议设置
     */
    public function fetchData()
    {
        $vars['service'] = SettingModel::getItem('service');
        $vars['privacy'] = SettingModel::getItem('privacy');
        return $this->renderSuccess('', compact('vars'));
    }

}
