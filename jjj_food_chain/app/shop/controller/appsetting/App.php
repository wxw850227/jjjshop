<?php

namespace app\shop\controller\appsetting;

use app\shop\controller\Controller;
use app\shop\model\app\App as AppModel;

/**
 * 应用设置
 */
class App extends Controller
{
    /**
     * 修改
     */
    public function index()
    {
        if ($this->request->isGet()) {
            return $this->fetchData();
        }
        $model = new AppModel;
        $data = $this->postData();
        unset($data['data']);
        if ($model->edit($data)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取数据
     */
    public function fetchData()
    {
        // 当前App信息
        $data = AppModel::detail($this->store['app']['app_id']);
        return $this->renderSuccess('', compact('data'));
    }

    /**
     * 支付方式
     */
    public function pay()
    {
        if ($this->request->isGet()) {
            $app = AppModel::detail($this->store['app']['app_id']);
            return $this->renderSuccess('', compact('app'));
        }
        $model = AppModel::detail($this->store['app']['app_id']);
        $data = $this->postData();
        if ($model->editPay($data)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}
