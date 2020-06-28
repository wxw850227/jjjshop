<?php

namespace app\api\model\settings;

use app\common\model\settings\Message as MessageModel;
use app\common\model\settings\MessageSettings as MessageSettingsModel;
use spec\League\Flysystem\Cached\CachedAdapterSpec;

/**
 * 退货地址模型
 */
class Message extends MessageModel
{
    /**
     * 获取消息
     */
    public static function getMessageByNameArr($message_ename_arr)
    {
        $model = new self();
        //子查询先过滤条件
        $settings_model = new MessageSettingsModel;
        $subsql = $settings_model->where('wx_status', '=', 1)
            ->where('app_id', '=', self::$app_id)
            ->buildSql();

        return $model->withoutGlobalScope()->alias('message')->field(['message.*','settings.wx_status','settings.wx_template'])
            ->where('message.message_ename', 'in', $message_ename_arr)
            ->where('message.is_delete', '=', 0)
            ->join([$subsql=> 'settings'], 'settings.message_id = message.message_id', 'left')
            ->order(['message.sort' => 'asc'])
            ->select();
    }
}