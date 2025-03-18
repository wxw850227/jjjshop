<?php

namespace app\shop\controller;

use app\shop\service\ShopService;
use app\common\model\settings\Setting as SettingModel;

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
        return $this->renderSuccess('', ['data' => $service->getHomeData($this->store['user'], $this->postData())]);
    }

    /**
     * 登录数据
     */
    public function base()
    {
        $config = SettingModel::getSysConfig();
        $settings = [
            'shop_name' => $config['shop_name'],
            'shop_bg_img' => $config['shop_bg_img'],
            'shop_logo_img' => isset($config['shop_logo_img']) ? $config['shop_logo_img'] : '',
        ];
        return $this->renderSuccess('', compact('settings'));
    }
}