<?php

namespace app\shop\controller\appsetting;

use app\shop\controller\Controller;
use app\shop\model\app\AppWx as AppWxModel;

/**
 * 微信小程序设置
 */
class Appwx extends Controller
{
    /**
     * 修改
     */
    public function index()
    {
        if($this->request->isGet()){
            return $this->fetchData();
        }
        $model = new AppWxModel;
        $data = $this->postData();
        unset($data['data']);
        if ($model->edit($data)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取微信小程序设置
     */
    public function fetchData()
    {
        // 当前微信小程序信息
        $data = AppWxModel::detail();
        return $this->renderSuccess('', compact('data'));
    }

}
