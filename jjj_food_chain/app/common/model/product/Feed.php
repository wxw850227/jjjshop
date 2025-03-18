<?php

namespace app\common\model\product;

use app\common\model\BaseModel;

/**
 * 加料模型
 */
class Feed extends BaseModel
{
    protected $name = 'product_feed';
    protected $pk = 'feed_id';

    //更新加料库库
    public function updateFeed($data)
    {
        if ($data) {
            $addData = [];
            foreach ($data as $item) {
                $isExit = $this->where('feed_name', '=', $item['feed_name'])->count();
                if ($isExit == 0) {
                    $addData[] = [
                        'feed_name' => $item['feed_name'],
                        'price' => $item['price'],
                        'app_id' => self::$app_id
                    ];
                }
            }
            $addData && $this->saveAll($addData);
        }
    }

    /**
     * 获取列表数据
     */
    public function getAllList()
    {
        $model = $this;
        return $model
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 详情
     */
    public static function detail($feed_id)
    {
        return self::find($feed_id);
    }
}
