<?php

namespace app\shop\controller\setting;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 存储控制器
 */
class Storage extends Controller
{
    /**
     * 存储设置
     */
    public function index()
    {
        if ($this->request->isGet()) {
            return $this->fetchData();
        }
        $model = new SettingModel;
        $data = $this->postData();
        $arr['max_image'] = $data['max_image'];
        $arr['default'] = $data['radio'];
        $arr['engine'] = $data['engine'];
        if ($model->edit('storage', $arr)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取存储配置
     */
    public function fetchData()
    {
        $key = 'storage';
        $vars['values'] = SettingModel::getItem($key);
        return $this->renderSuccess('', compact('vars'));
    }

}
