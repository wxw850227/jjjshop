<?php

namespace app\shop\controller\appsettings;

use app\shop\controller\Controller;
use app\shop\model\app\App as AppModel;


class App extends Controller
{
    /**
     * 编辑
     */
    public function edit()
    {
        $model = new AppModel;
        $data = $this->postData();
        unset($data['data']);
        if ($model->edit($data)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 商城设置进来请求的接口
     */
    public function fetchData()
    {
        // 当前小程序信息
        $data = AppModel::detail($this->store['app']['app_id']);
        return $this->renderSuccess('', compact('data'));
    }


}
