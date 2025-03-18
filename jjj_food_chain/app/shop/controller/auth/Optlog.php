<?php

namespace app\shop\controller\auth;

use app\shop\controller\Controller;
use app\shop\model\shop\OptLog as OptLogModel;
/**
 * 管理员操作日志
 */
class Optlog extends Controller
{
    /**
     * 操作日志
     */
    public function index()
    {
        $model = new OptLogModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }
}