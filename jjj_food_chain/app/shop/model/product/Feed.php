<?php

namespace app\shop\model\product;

use app\common\model\product\Feed as FeedModel;

/**
 * 加料模型
 */
class Feed extends FeedModel
{
    /**
     * 获取列表数据
     */
    public function getList($data)
    {
        $model = $this;
        return $model
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($data);
    }

    /**
     * 添加
     */
    public function add($data)
    {
        $isExist = $this->where('feed_name', '=', $data['feed_name'])->count();
        if ($isExist) {
            $this->error = '名称已存在';
            return false;
        }
        $data['app_id'] = self::$app_id;
        return $this->save($data);
    }

    /**
     * 修改
     */
    public function edit($data)
    {
        $isExist = $this->where('feed_name', '=', $data['feed_name'])->where('feed_id', '<>', $this['feed_id'])->count();
        if ($isExist) {
            $this->error = '名称已存在';
            return false;
        }
        return $this->save($data);
    }

    /**
     * 删除
     */
    public function setDelete($feed_id)
    {
        return $this->where('feed_id', 'in', $feed_id)->delete();
    }
}
