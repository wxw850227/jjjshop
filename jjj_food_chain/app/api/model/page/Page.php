<?php

namespace app\api\model\page;

use app\api\model\product\Product as ProductModel;
use app\common\model\page\Page as PageModel;
use app\api\model\plus\coupon\Coupon;
use app\api\model\plus\live\WxLive;
use app\api\model\plus\article\Article;
use app\api\model\plus\group\Group;

/**
 * 首页模型
 */
class Page extends PageModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];

    /**
     * DIY页面详情
     */
    public static function getPageData($user, $page_id = 0)
    {
        // 页面详情
        $detail = $page_id > 0 ? parent::detail($page_id) : parent::getHomePage();

        // 页面diy元素
        $items = $detail['page_data']['items'];
        // 页面顶部导航
        isset($detail['page_data']['page']) && $items['page'] = $detail['page_data']['page'];
        // 获取动态数据
        $model = new self;
        foreach ($items as $key => $item) {
            unset($items[$key]['defaultData']);
            if ($item['type'] === 'window') {
                $items[$key]['data'] = array_values($item['data']);
            }
        }
        return ['page' => $items['page'], 'items' => $items];
    }

}