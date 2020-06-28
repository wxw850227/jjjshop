<?php

namespace app\shop\controller;

use app\shop\service\ShopService;

/**
 * 后台首页控制器
 */
class Index extends Controller
{
    /**
     * 后台首页
     */
    public function index()
    {
        $service = new ShopService;
        return $this->renderSuccess('', ['data' => $service->getHomeData()]);
    }
}