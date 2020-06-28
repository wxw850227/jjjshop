<?php

namespace app\api\model\product;

use app\common\model\product\Category as CategoryModel;

/**
 * Class Category
 */
class Category extends CategoryModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'update_time'
    ];

    public static function getList()
    {

    }

}