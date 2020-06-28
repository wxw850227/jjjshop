<?php

namespace app\common\model\settings;

use app\common\model\BaseModel;

/**
 * 消息字段模型
 */
class MessageSettings extends BaseModel
{
    //表名
    protected $name = 'message_settings';
    //主键字段名
    protected $pk = 'message_settings_id';

    /**
     * 详情
     */
    public static function detail($message_settings_id)
    {
        return self::find($message_settings_id);
    }

    /**
     * 根据消息id查询详情
     */
    public static function detailByMessageId($message_id)
    {
        return self::where('message_id', '=', $message_id)->find();
    }

    
}
