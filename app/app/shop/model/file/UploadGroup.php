<?php

namespace app\shop\model\file;

use app\common\model\file\UploadGroup as UploadGroupModel;

/**
 * 文件库分组模型
 */
class UploadGroup extends UploadGroupModel
{
    /**
     * 获取列表记录
     */
    public function getList($groupType = '')
    {
        !empty($groupType) && $this->where('group_type', '=', trim($groupType));
        return $this->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 添加新记录
     */
    public function add($data)
    {
        return $this->save(array_merge([
            'app_id' => self::$app_id,
            'sort' => 100
        ], $data));
    }

    /**
     * 更新记录
     */
    public function edit($data)
    {
        return $this->save($data) !== false;
    }

    /**
     * 删除记录
     */
    public function remove()
    {
        // 更新该分组下的所有文件
        (new UploadFile)->where('group_id', '=', $this['group_id'])
            ->update(['group_id' => 0]);
        // 删除分组
        return $this->save(['is_delete' => 1]);
    }

}
