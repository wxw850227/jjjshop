<?php

namespace app\api\model\page;

use app\common\model\page\PageCategory as PageCategoryModel;

/**
 * app分类页模板模型
 */
class PageCategory extends PageCategoryModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];

}