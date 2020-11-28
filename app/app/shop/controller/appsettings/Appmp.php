<?php

namespace app\shop\controller\appsettings;

use app\shop\controller\Controller;
use app\shop\model\app\AppMp as AppMpModel;

/**
 * 公众号设置
 */
class Appmp extends Controller
{
    /**
     * 修改
     */
    public function index()
    {
        if($this->request->isGet()){
            return $this->fetchData();
        }
        $model = new AppMpModel;
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
        // 当前公众号信息
        $data = AppMpModel::detail();
        return $this->renderSuccess('', compact('data'));
    }

}
