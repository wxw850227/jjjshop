<?php

namespace app\common\model\file;

use app\common\model\BaseModel;

/**
 * 文件库分组模型
 */
class UploadGroup extends BaseModel
{
    //表名
    protected $name = 'upload_group';
    //主键字段名
    protected $pk = 'group_id';

    /**
     * 分组详情
     */
    public static function detail($group_id) {
        return self::find($group_id);
    }

}
