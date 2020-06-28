<?php

namespace app\shop\model\settings;

use app\common\model\settings\MessageField as MessageFieldModel;

/**
 * 退货地址模型
 */
class MessageField extends MessageFieldModel
{
    /**
     * 获取全部收货地址
     */
    public function getAll($message_id)
    {
        return $this->withoutGlobalScope()->where('message_id', '=', $message_id)
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc'])
            ->select();
    }
}