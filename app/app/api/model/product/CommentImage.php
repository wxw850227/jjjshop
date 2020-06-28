<?php

namespace app\api\model\product;

use app\common\model\product\CommentImage as CommentImageModel;

/**
 * 商品图片模型
 */
class CommentImage extends CommentImageModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
    ];

}
