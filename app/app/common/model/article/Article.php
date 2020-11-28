<?php

namespace app\common\model\article;

use app\common\model\BaseModel;

/**
 * 文章模型
 */
class Article extends BaseModel
{
    protected $name = 'article';
    protected $pk = 'article_id';

    /**
     * 关联文章封面图
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('app\\common\\model\\file\\UploadFile', 'file_id', 'image_id');
    }

    /**
     * 关联文章分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\article\\Category", 'category_id', 'category_id');
    }

    /**
     * 展示的浏览次数
     * @param $data
     * @return mixed
     */
    public function getShowViewsAttr($data)
    {
        return $data['virtual_views'] + $data['actual_views'];
    }

    /**
     * 文章详情
     * @param $article_id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($article_id)
    {
        return self::with(['image', 'category'])->find($article_id);
    }
}
