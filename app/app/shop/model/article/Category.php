<?php

namespace app\shop\model\article;

use think\facade\Cache;
use app\common\model\article\Category as CategoryModel;
use app\shop\model\article\Article as ArticleModel;

/**
 * 文章分类模型
 */
class Category extends CategoryModel
{
    /**
     * 分类详情
     */
    public static function detail($category_id)
    {
        return static::find($category_id);
    }

    /**
     * 添加新记录
     */
    public function add($data)
    {
        $data['app_id'] = self::$app_id;
        return $this->save($data);
    }

    /**
     * 编辑记录
     */
    public function edit($data)
    {
        $data['create_time'] = strtotime($data['create_time']);
        $data['update_time'] = time();
        return $this->save($data);
    }

    /**
     * 删除商品分类
     */
    public function remove()
    {
        // 判断是否存在文章
        $articleCount = ArticleModel::getArticleTotal(['category_id' => $this['category_id']]);
        if ($articleCount > 0) {
            $this->error = '该分类下存在' . $articleCount . '个文章，不允许删除';
            return false;
        }
        return $this->delete();
    }

    /**
     * 删除缓存
     */
    private function deleteCache()
    {
        return Cache::delete('article_category_' . self::$app_id);
    }

}