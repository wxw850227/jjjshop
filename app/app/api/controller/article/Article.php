<?php

namespace app\api\controller\article;

use app\api\controller\Controller;
use app\api\model\article\Article as ArticleModel;
use app\api\model\article\Category as CategoryModel;

/**
 * 文章控制器
 */
class Article extends Controller
{
    /**
     *获取分类
     */
    public function category()
    {
        // 文章分类
        $category = CategoryModel::getAll();
        return $this->renderSuccess('', compact('category'));
    }

    /**
     * 文章列表
     */
    public function index($category_id = 0)
    {
        $model = new ArticleModel;
        $list = $model->getList($category_id, $this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     *文章详情
     */
    public function detail($article_id)
    {
        $detail = ArticleModel::detail($article_id);
        return $this->renderSuccess('', compact('detail'));
    }

}