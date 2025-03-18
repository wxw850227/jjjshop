<?php

namespace app\common\model\app;

use app\common\model\BaseModel;

/**
 * app升级模型
 */
class AppUpdate extends BaseModel
{
    protected $name = 'app_update';
    protected $pk = 'update_id';

    /**
     * 详情
     */
    public static function detail($update_id)
    {
        return self::find($update_id);
    }

    /**
     * 最新一个详情
     */
    public static function getLast()
    {
        return self::where('is_delete', '=', 0)
            ->order(['create_time' => 'desc'])->find();
    }
}
