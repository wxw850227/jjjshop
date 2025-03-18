<?php

namespace app\common\library\easywechat;

use app\common\exception\BaseException;
use app\common\model\app\App as AppModel;
use app\common\model\app\AppWx as AppWxModel;
use EasyWeChat\MiniApp\Application;
use EasyWeChat\Pay\Application as payApp;

/**
 * 微信小程序
 */
class AppWx
{
    public static function getApp($app_id = null)
    {
        // 获取当前小程序信息
        $wxConfig = AppWxModel::getAppWxCache($app_id);
        // 验证appid和appsecret是否填写
        if (empty($wxConfig['wxapp_id']) || empty($wxConfig['wxapp_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-应用-小程序设置] 填写appid 和 appsecret']);
        }
        $config = [
            'app_id' => $wxConfig['wxapp_id'],
            'secret' => $wxConfig['wxapp_secret'],
        ];
        $app = new Application($config);
        return $app;
    }

    public static function getWxPayApp($app_id)
    {
        // 获取当前小程序信息
        $wxConfig = AppWxModel::getAppWxCache($app_id);
        // 验证appid和appsecret是否填写
        if (empty($wxConfig['wxapp_id']) || empty($wxConfig['wxapp_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-应用-小程序设置] 填写appid 和 appsecret']);
        }
        $app = AppModel::detail($app_id);
        if (empty($app['cert_pem']) || empty($app['key_pem'])) {
            throw new BaseException(['msg' => '请先到后台[应用->支付设置]填写微信支付证书文件']);
        }
        // cert目录
        $filePath = root_path() . 'runtime/cert/app/' . $wxConfig['app_id'] . '/';
        $config = [
            'app_id' => $wxConfig['wxapp_id'],
            'secret' => $wxConfig['wxapp_secret'],
            'mch_id' => $app['mchid'],
            'secret_key' => $app['apikey'],   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'certificate' => $filePath . 'cert.pem',
            'private_key' => $filePath . 'key.pem',
            'http' => [
                'throw' => true, // 状态码非 200、300 时是否抛出异常，默认为开启
                'timeout' => 5.0,
            ],
        ];
        $payApp = new payApp($config);
        return $payApp;
    }

    /**
     * 获取session_key
     * @param $code
     * @return array|mixed
     */
    public static function sessionKey($app, $code)
    {
        $utils = $app->getUtils();
        return $utils->codeToSession($code);
    }
}
