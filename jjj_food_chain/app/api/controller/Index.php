<?php

namespace app\api\controller;

use app\api\model\page\Page as AppPage;
use app\api\model\settings\Setting as SettingModel;
use app\api\model\supplier\Supplier as SupplierModel;

/**
 * 页面控制器
 */
class Index extends Controller
{
    /**
     * 首页
     */
    public function index($page_id = null, $url = '')
    {
        // 页面元素
        $data = AppPage::getPageData($this->getUser(false), $page_id);
        // 页面元素
        $user = $this->getUser(false);
        //获取默认门店id
        $supplier = (new SupplierModel)->getDefault($this->postData());
        return $this->renderSuccess('', compact('data', 'user', 'supplier'));
    }

    /**
     * 首页
     */
    public function diy($page_id = 0)
    {
        // 页面元素
        $data = AppPage::getPageData($this->getUser(false), $page_id);
        return $this->renderSuccess('', $data);
    }

    //用户信息
    public function userInfo()
    {
        $user = $this->getUser(false);
        return $this->renderSuccess('', compact('user'));
    }

    /**
     * 用户注册登录设置
     */
    public function loginSetting()
    {
        $settingDetail = SettingModel::getItem('store');
        $service = SettingModel::getItem('service');
        $privacy = SettingModel::getItem('privacy');
        $setting = [
            'name' => $settingDetail['name'],
            'login_logo' => $settingDetail['login_logo'],
            'login_desc' => $settingDetail['login_desc'],
            'wx_phone' => $settingDetail['wx_phone'],
            'service' => $service['service'],
            'privacy' => $privacy['privacy'],
        ];
        return $this->renderSuccess('', compact('setting'));
    }

    //主题
    public function nav()
    {
        $data['theme'] = 2;
        $data['key'] = SettingModel::getItem('map')['tx_key'];
        return $this->renderSuccess('', $data);
    }
}
