<?php

namespace app\api\model\file;

use app\common\model\file\UploadFile as UploadFileModel;

/**
 * 文件库模型
 */
class UploadFile extends UploadFileModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'app_id',
        'create_time',
    ];

}
