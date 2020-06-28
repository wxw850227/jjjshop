<?php

namespace app\common\model\settings;

use app\common\model\BaseModel;

/**
 * 消息字段模型
 */
class MessageField extends BaseModel
{
    //表名
    protected $name = 'message_field';
    //主键字段名
    protected $pk = 'message_field_id';

    /**
     * 详情
     */
    public static function detail($message_field_id)
    {
        return self::find($message_field_id);
    }


}
