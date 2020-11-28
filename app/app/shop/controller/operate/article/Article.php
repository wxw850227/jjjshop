<?php

namespace app\shop\controller\operate\article;

use app\shop\controller\Controller;
use app\shop\model\article\Article as ArticleModel;
use app\shop\model\article\Category as CategoryModel;

/**
 * 文章控制器
 */
class Article extends Controller
{
    /**
     * 文章列表
     */
    public function index()
    {
        $model = new ArticleModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加文章
     */
    public function add()
    {
        if($this->request->isGet()){
            // 文章分类
            $catgory = CategoryModel::getAll();
            return $this->renderSuccess('', compact('catgory'));
        }
        $model = new ArticleModel;
        // 新增记录
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     *文章详情
     */
    public function detail($article_id)
    {
        $model = new ArticleModel;
        return $this->renderSuccess('', $model->detail($article_id));
    }

    /**
     * 更新文章
     */
    public function edit($article_id)
    {
        if($this->request->isGet()){
            // 文章分类
            $catgory = CategoryModel::getAll();
            $model = ArticleModel::detail($article_id);
            return $this->renderSuccess('', compact('catgory', 'model'));
        }
        // 文章详情
        $model = ArticleModel::detail($article_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文章
     */
    public function delete($article_id)
    {
        // 文章详情
        $model = ArticleModel::detail($article_id);
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?:'删除失败');
    }

}