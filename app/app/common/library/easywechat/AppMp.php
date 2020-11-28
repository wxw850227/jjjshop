<?php

namespace app\common\library\easywechat;

use EasyWeChat\Factory;
use app\common\exception\BaseException;
use app\common\model\app\AppMp as AppMpModel;

/**
 * 微信公众号
 */
class AppMp
{
    public static function getApp($app_id = null){
        // 获取当前小程序信息
        $wxConfig = AppMpModel::getAppMpCache($app_id);
        // 验证appid和appsecret是否填写
        if (empty($wxConfig['mpapp_id']) || empty($wxConfig['mpapp_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-应用-公众号设置] mpapp_id 和 mpapp_secret']);
        }
        $config = [
            'app_id' => $wxConfig['mpapp_id'],
            'secret' => $wxConfig['mpapp_secret'],
            'response_type' => 'array',
        ];
        return Factory::officialAccount($config);
    }

    public static function getWxPayApp($app_id){
        // 获取当前小程序信息
        $wxConfig = AppMpModel::getAppMpCache($app_id);
        // 验证appid和appsecret是否填写
        if (empty($wxConfig['mpapp_id']) || empty($wxConfig['mpapp_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-应用-公众号设置] 填写appid 和 appsecret']);
        }

        if (empty($wxConfig['cert_pem']) || empty($wxConfig['key_pem'])) {
            throw new BaseException(['msg' => '请先到后台公众号设置填写微信支付证书文件']);
        }
        // cert目录
        $filePath = __DIR__ . '/cert/appmp/' . $wxConfig['app_id'] . '/';

        $config = [
            'app_id' => $wxConfig['mpapp_id'],
            'mch_id'             => $wxConfig['mchid'],
            'key'                => $wxConfig['apikey'],   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => $filePath . 'cert.pem',
            'key_path'           => $filePath . 'key.pem',
            'sandbox' => false, // 设置为 false 或注释则关闭沙箱模式
        ];
        return Factory::payment($config);
    }

}
