<?php

namespace app\common\model\settings;

use app\common\model\BaseModel;

/**
 * 消息字段模型
 */
class MessageField extends BaseModel
{
    protected $name = 'message_field';
    protected $pk = 'message_field_id';

    /**
     * 详情
     */
    public static function detail($message_field_id)
    {
        return self::find($message_field_id);
    }


}
