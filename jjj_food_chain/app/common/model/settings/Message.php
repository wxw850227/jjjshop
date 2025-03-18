<?php

namespace app\common\model\settings;

use app\common\enum\message\MessageToEnum;
use app\common\enum\message\MessageTypeEnum;
use app\common\model\BaseModel;

/**
 * 消息模型
 */
class Message extends BaseModel
{
    protected $name = 'message';
    protected $pk = 'message_id';


    /**
     * 付款状态
     */
    public function getMessageToAttr($value)
    {
        return ['text' => MessageToEnum::data()[$value]['name'], 'value' => $value];
    }


    /**
     * 付款状态
     */
    public function getMessageTypeAttr($value)
    {
        return ['text' => MessageTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 运费模板详情
     */
    public static function detail($message_id)
    {
        return self::find($message_id);
    }

    /**
     * 详情
     */
    public static function detailByEname($message_ename)
    {
        return self::withoutGlobalScope()->where('message_ename', '=', $message_ename)->find();
    }
}
