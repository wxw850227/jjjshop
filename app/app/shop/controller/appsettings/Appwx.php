<?php

namespace app\shop\controller\appsettings;

use app\shop\controller\Controller;
use app\shop\model\app\AppWx as AppWxModel;

class Appwx extends Controller
{
    /**
     * 编辑
     */
    public function edit()
    {
        $model = new AppWxModel;
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
        $data = AppWxModel::detail();
        return $this->renderSuccess('', compact('data'));
    }


}
