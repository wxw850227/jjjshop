<?php

namespace app\shop\model\settings;

use app\common\model\settings\MessageSettings as MessageSettingsModel;

/**
 * 退货地址模型
 */
class MessageSettings extends MessageSettingsModel
{
    /**
     * 获取全部收货地址
     */
    public function saveSettings($data)
    {
        $this->startTrans();
        try {
            $var_data = [];
            foreach ($data['fieldList'] as $field){
                $var_data[$field['field_ename']] = [
                    'field_name' => $field['field_new_ename'],
                    'filed_value' => $field['filed_value']
                ];
            }
            if($data['message_type'] == 'mp'){
                $mp_data['template_id'] = $data['template_id'];
                $mp_data['var_data'] = $var_data;
                $this->save([
                    'mp_status' => 1,
                    'mp_template' => json_encode($mp_data),
                    'app_id' => self::$app_id,
                    'message_id' => $data['message_id']
                ]);
            }else if($data['message_type'] == 'wx'){
                $wx_data['template_id'] = $data['template_id'];
                $wx_data['var_data'] = $var_data;
                $this->save([
                    'wx_status' => 1,
                    'wx_template' => json_encode($wx_data),
                    'app_id' => self::$app_id,
                    'message_id' => $data['message_id']
                ]);
            }else if($data['message_type'] == 'sms'){
                $sms_data['template_id'] = $data['template_id'];
                $sms_data['var_data'] = $var_data;
                $this->save([
                    'sms_status' => 1,
                    'sms_template' => json_encode($sms_data),
                    'app_id' => self::$app_id,
                    'message_id' => $data['message_id']
                ]);
            }

            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    public function updateSettingsStatus($message_type){
        if($message_type == 'mp'){
            return $this->save([
                'mp_status' => !$this['mp_status'],
            ]);
        }else if($message_type == 'wx'){
            return $this->save([
                'wx_status' => !$this['wx_status'],
            ]);
        }else if($message_type == 'sms'){
            return $this->save([
                'sms_status' => !$this['sms_status'],
            ]);
        }
        return false;
    }
}