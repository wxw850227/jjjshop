<?php

namespace app\common\service\message;

use app\common\enum\order\OrderTypeEnum;
use app\common\library\easywechat\AppWx;
use app\common\library\wechat\WxTplMsg;

/**
 * 微信小程序消息通知服务
 */
class WxMessageService
{
    /**
     * 订单支付成功后通知
     */
    public static function send($data, $wx_template, $touser, $app_id)
    {
        try{
            $wx_template = json_decode($wx_template, true);

            $var_data = $wx_template['var_data'];
            $send_data = [];
            foreach ($var_data as $key => $value){
                if(isset($data[$key])){
                    $send_data[$value['field_name']] = [
                        'value' => urldecode($data[$key])
                    ];
                }else{
                    $send_data[$value['field_name']] = [
                        'value' => urldecode($value['filed_value'])
                    ];
                }
            }


            foreach($send_data as $key => $value){
                if(mb_strlen($value['value']) > 20){
                    $send_data[$key]['value'] = mb_substr($value['value'],0, 20);
                }
            }

            $app = AppWx::getApp($app_id);
            $result = $app->subscribe_message->send([
                'template_id' => $wx_template['template_id'],
                'touser' => $touser,
                'page' => '/pages/index/index',
                'data' => $send_data,
            ]);

            log_write($result);
        }catch (\Exception $e){
            log_write('小程序订阅消息发送失败');
            log_write($e->getMessage());
        }
    }

}