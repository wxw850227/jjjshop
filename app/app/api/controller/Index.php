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
        return $this->renderSuccess('', $data);
    }

}
