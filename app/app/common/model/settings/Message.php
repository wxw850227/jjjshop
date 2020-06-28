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
    //表名
    protected $name = 'message';
    //主键字段名
    protected $pk = 'message_id';


    /**
     * 消息发送对象
     */
    public function getMessageToAttr($value)
    {
        return ['text' => MessageToEnum::data()[$value]['name'], 'value' => $value];
    }


    /**
     * 消息类型
     */
    public function getMessageTypeAttr($value)
    {
        return ['text' => MessageTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 详情
     */
    public static function detail($message_id)
    {
        return self::find($message_id);
    }

    /**
     * 根据名称查询详情
     */
    public static function detailByEname($message_ename)
    {
        return self::withoutGlobalScope()->where('message_ename', '=', $message_ename)->find();
    }
}
