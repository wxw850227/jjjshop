<?php

namespace app\common\model\article;

use think\facade\Cache;
use app\common\model\BaseModel;

/**
 * 文章分类模型
 */
class Category extends BaseModel
{
    protected $name = 'article_category';
    protected $pk = 'category_id';

    /**
     * 所有分类
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getALL()
    {
        $model = new static;
        return $model->order(['sort' => 'asc', 'create_time' => 'asc'])->select();
    }

}