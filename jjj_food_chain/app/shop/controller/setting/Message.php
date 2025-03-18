<?php

namespace app\shop\controller\setting;

use app\common\enum\settings\GetPhoneTypeEnum;
use app\shop\controller\Controller;
use app\shop\model\settings\Message as MessageModel;
use app\shop\model\settings\MessageField as MessageFieldModel;
use app\shop\model\settings\MessageSettings as MessageSettingsModel;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 消息控制器
 */
class Message extends Controller
{
    /**
     * 消息首页
     */
    public function index($message_to = 10)
    {
        $model = new MessageModel;
        $list = $model->getAll($message_to);
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 消息字段
     */
    public function field($message_id, $message_type)
    {
        $model = new MessageFieldModel;
        $list = $model->getAll($message_id);
        $message_settings = MessageSettingsModel::detailByMessageId($message_id);
        //公众号设置
        $settings = null;
        if ($message_type == 'wx' && $message_settings) {
            $settings = $message_settings['wx_template'] ? json_decode($message_settings['wx_template'], true) : '';
        }
        //合并字段
        return $this->renderSuccess('', compact('list', 'settings'));
    }

    /**
     * 保存设置
     */
    public function saveSettings()
    {
        $data = $this->postData();
        // 新增记录
        $model = MessageSettingsModel::detailByMessageId($data['message_id']);
        if (!$model) {
            $model = new MessageSettingsModel();
        }
        if ($model->saveSettings($data)) {
            return $this->renderSuccess('保存成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }
    /**
     * 修改状态
     */
    public function updateSettingsStatus($message_settings_id, $message_type)
    {
        $model = MessageSettingsModel::detail($message_settings_id);
        if ($model->updateSettingsStatus($message_type)) {
            return $this->renderSuccess('保存成功');
        }
        return $this->renderError($model->getError() ?: '保存失败');
    }
}
