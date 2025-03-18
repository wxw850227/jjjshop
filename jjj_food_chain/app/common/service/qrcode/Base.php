<?php

namespace app\common\service\qrcode;

use app\common\library\easywechat\AppWx;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * 二维码服务基类
 */
class Base
{
    /**
     * 构造方法
     */
    public function __construct()
    {
    }

    /**
     * 保存小程序码到文件
     */
    protected function saveQrcodeToDir($app_id, $page, $savePath, $table_id, $shop_supplier_id)
    {
        // 小程序配置信息
        $app = AppWx::getApp($app_id);
        // 小程序码参数
        $scene = "tid:$table_id,sid:$shop_supplier_id";
        // 文件名称
        $fileName = 'qrcode_' . md5($app_id . $scene . $page) . '.png';
        // 请求api获取小程序码
        $response = $app->getClient()->postJson('/wxa/getwxacodeunlimit', [
            'scene' => $scene,
            'page' => $page,
            'width' => 430,
            'check_path' => false,
        ]);
        // 保存小程序码到文件
        $response->saveAs($savePath . '/' . $fileName);
        return true;
    }

    /**
     * 保存二维码码到文件
     */
    protected function saveMpQrcodeToDir($page, $savePath, $table_id, $app_id, $shop_supplier_id)
    {
        $qrcode = new QrCode(base_url() . $page . '?table_id=' . $table_id . '&app_id=' . $app_id . '&shop_supplier_id=' . $shop_supplier_id);
        $scene = "shop_supplier_id:{$shop_supplier_id},table_id:{$table_id}";
        // 文件名称
        $fileName = 'qrcode_' . md5($app_id . $scene) . '.png';
        // 保存二维码到文件
        $path = "{$savePath}{$fileName}";
        $write = new PngWriter();
        $write->write($qrcode)->saveToFile($path);
        return true;
    }
}