<?php

namespace app\shop\model\settings;

use app\common\model\product\ProductSku;
use app\common\model\settings\Message as MessageModel;
use app\common\model\settings\MessageSettings as MessageSettingsModel;

/**
 * 退货地址模型
 */
class Message extends MessageModel
{
    /**
     * 获取全部收货地址
     */
    public function getAll($message_to)
    {
        //子查询先过滤条件
        $settings_model = new MessageSettingsModel;
        $subsql = $settings_model->where('app_id', '=', self::$app_id)->buildSql();

        return $this->withoutGlobalScope()->alias('message')->field(['message.*','settings.message_settings_id','settings.sms_status','settings.sms_template'
            ,'settings.mp_status','settings.mp_template','settings.wx_status','settings.wx_template'])
            ->where('message.message_to', '=', $message_to)
            ->where('message.is_delete', '=', 0)
            ->join([$subsql=> 'settings'], 'settings.message_id = message.message_id', 'left')
            ->order(['message.sort' => 'asc'])
            ->select();
    }
}