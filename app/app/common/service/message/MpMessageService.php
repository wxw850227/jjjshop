<?php

namespace app\common\service\message;

use app\common\enum\order\OrderTypeEnum;
use app\common\library\easywechat\AppMp;
use app\common\library\wechat\WxTplMsg;

/**
 * 公众号消息通知服务
 */
class MpMessageService
{
    /**
     * 订单支付成功后通知
     */
    public static function send($data, $mp_template, $touser, $app_id)
    {
        try{
            $mp_template = json_decode($mp_template, true);

            $var_data = $mp_template['var_data'];
            $send_data = [];
            foreach ($var_data as $key => $value){
                if(isset($data[$key])){
                    $send_data[$value['field_name']] = $data[$key];
                }else{
                    $send_data[$key] = $value['filed_value'];
                }
            }

            $app = AppMp::getApp($app_id);
            $app->template_message->send([
                'touser' => $touser,
                'template_id' => $mp_template['template_id'],
                'url' => '',
                'data' => $send_data,
            ]);
        }catch (\Exception $e){
            log_write('公众号消息发送失败');
            log_write($e->getMessage());
        }
    }
}