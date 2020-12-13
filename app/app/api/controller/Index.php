<?php

namespace app\api\controller;

use app\api\model\page\Page as AppPage;
use app\api\model\settings\Setting as SettingModel;
use think\facade\Cache;

/**
 * 页面控制器
 */
class Index extends Controller
{
    /**
     * 首页
     */
    public function index($page_id = null)
    {
        // 页面元素
        $data = AppPage::getPageData($this->getUser(false), $page_id);
        $data['setting'] = array(
            'collection' => SettingModel::getItem('collection'),
            'officia' => SettingModel::getItem('officia'),
        );
        return $this->renderSuccess('', $data);
    }

    // 公众号客服
    public function mpService()
    {
        $mp_service = SettingModel::getItem('mp_service');
        return $this->renderSuccess('', compact('mp_service'));
    }

}
