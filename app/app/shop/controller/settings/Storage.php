<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 存储设置
 */
class Storage extends Controller
{
    /**
     * 存储设置首页
     */
    public function index()
    {
        $model = new SettingModel;
        $data = $this->postData();
        $values = [
            'default' => $data['radio'],
            'engine' => $data['engine']
        ];
        $key = 'storage';
        if ($model->edit($key, $values)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 商城设置进来请求的接口
     */
    public function fetchData()
    {
        $key = 'storage';
        $vars['values'] = SettingModel::getItem($key);
        return $this->renderSuccess('', compact('vars'));
    }


}
