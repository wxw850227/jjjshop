<?php

namespace app\common\model\settings;

use app\common\model\BaseModel;

/**
 * 消息字段模型
 */
class MessageSettings extends BaseModel
{
    protected $name = 'message_settings';
    protected $pk = 'message_settings_id';

    /**
     * 详情
     */
    public static function detail($message_settings_id)
    {
        return self::find($message_settings_id);
    }

    /**
     * 消息详情
     */
    public static function detailByMessageId($message_id, $app_id = null)
    {
        $model = new static();
        if ($app_id != null) {
            $model = $model->where('app_id', '=', $app_id);
        }
        return $model->where('message_id', '=', $message_id)->find();
    }
}
