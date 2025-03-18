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
