<?php

namespace app\shop\model\article;

use app\common\model\article\Article as ArticleModel;

/**
 * 文章模型
 */
class Article extends ArticleModel
{
    /**
     * 获取文章列表
     */
    public function getList($params)
    {
        return $this->with(['image', 'category'])
            ->where('is_delete', '=', 0)
            ->order(['article_sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($params, false, [
                'query' => request()->request()
            ]);

    }

    /**
     * 新增记录
     */
    public function add($data)
    {
        if (empty($data['article_content'])) {
            $this->error = '请输入文章内容';
            return false;
        }

        $data['app_id'] = self::$app_id;
        return $this->save($data);
    }

    /**
     * 更新记录
     */
    public function edit($data)
    {
        if (empty($data['article_content'])) {
            $this->error = '请输入文章内容';
            return false;
        }
        return $this->save($data);
    }

    /**
     * 软删除
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 获取文章总数量
     */
    public static function getArticleTotal($where)
    {
        $model = new static;
        return $model->where($where)->where('is_delete', '=', 0)->count();
    }
}