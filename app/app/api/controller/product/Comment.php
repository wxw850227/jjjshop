<?php

namespace app\api\controller\product;

use app\api\controller\Controller;
use app\api\model\product\Comment as CommentModel;

/**
 * 商品评价控制器
 */
class Comment extends Controller
{
    /**
     * 商品评价列表
     */
    public function lists($product_id, $scoreType = -1)
    {
        $model = new CommentModel;
        $list = $model->getProductCommentList($product_id, $scoreType, $this->postData());
        $total = $model->getTotal($product_id);
        return $this->renderSuccess('', compact('list', 'total'));
    }

}