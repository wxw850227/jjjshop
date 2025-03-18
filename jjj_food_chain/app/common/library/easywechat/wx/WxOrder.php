<?php

namespace app\common\library\easywechat\wx;

/**
 * 直播房间
 */
class WxOrder extends WxBase
{
    /**
     * 上传物流单号
     */
    public function uploadExpress($params_arr)
    {
        // 获取 access token 实例
        $accessToken = $this->app->getAccessToken();
        $token = $accessToken->getToken();
        // 微信接口url
        $apiUrl = "https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info?access_token=$token";

        $params = json_encode($params_arr, JSON_UNESCAPED_UNICODE);
        // 执行请求
        $result = $this->post($apiUrl, $params);
        // 返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = '请求错误';
            return false;
        }
        if ($response['errcode'] != 0) {
            if ($response['errcode'] == '9410000') {
                $this->error = 'empty';
            } else {
                if ($response['errcode'] == 40001) {
                    //防止token过期或更换,重新获取
                    $accessToken->getToken(true);
                }
                $this->error = $response['errmsg'];
            }
            return false;
        }
        return true;
    }
}