<?php

namespace app\shop\model\file;

use app\common\model\file\UploadFile as UploadFileModel;

use think\facade\Request;
use app\shop\model\settings\Setting as SettingModel;
use app\common\library\storage\Driver as StorageDriver;

/**
 * 图片模型
 */
class UploadFile extends UploadFileModel
{

    /**
     * 获取列表记录
     */
    public function getList($groupId = 0, $fileType = '', $pageSize = 30, $isRecycle = -1)
    {
        $model = $this;
//        // 文件分组
        if ($groupId != 0) {
            $model = $model->where('group_id', '=', (int)$groupId);
        }
//        // 文件类型
        !empty($fileType) && $model = $model->where('file_type', '=', trim($fileType));
//        // 是否在回收站
        $isRecycle > -1 && $model = $model->where('is_recycle', '=', (int)$isRecycle);
        // 查询列表数据
        return $model->with(['upload_group'])
            ->where(['is_user' => 0, 'is_delete' => 0])
            ->order(['file_id' => 'desc'])
            ->paginate($pageSize, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 软删除
     */
    public function softDelete($fileIds)
    {
        return $this->where('file_id', 'in', $fileIds)->update(['is_delete' => 1]);
    }

    /**
     * 批量移动文件分组
     */
    public function moveGroup($group_id, $fileIds)
    {
        return $this->where('file_id', 'in', $fileIds)->update(compact('group_id'));
    }
}
