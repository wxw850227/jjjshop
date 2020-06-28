<?php

namespace app\shop\model\app;

use app\common\model\app\AppWx as AppWxModel;
use think\facade\Cache;

/**
 * 微信小程序模型
 */
class AppWx extends AppWxModel
{
    /**
     * 更新小程序设置
     */
    public function edit($data)
    {
        $this->startTrans();
        try {
            // 删除app缓存
            self::deleteCache();
            if (!empty($data['cert_pem']) && !empty($data['key_pem'])) {
                // 写入微信支付证书文件
                $this->writeCertPemFiles($data['cert_pem'], $data['key_pem']);
            }
            $filter['app_id'] = self::$app_id;

            $count = $this->count($filter);
            // 更新小程序设置
            if ($count > 0) {
                self::update($data, $filter);
            }
            if ($count == 0) {
                $data['app_id'] = self::$app_id;
                self::create($data);
            }

            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    public function count($filter)
    {
        return $this->where($filter)->count();
    }

    /**
     * 写入cert证书文件
     */
    private function writeCertPemFiles($cert_pem = '', $key_pem = '')
    {
        if (empty($cert_pem) || empty($key_pem)) {
            return false;
        }
        // 证书目录
        $filePath = '../app/common/library/easywechat/cert/appwx/' . self::$app_id . '/';
        // 目录不存在则自动创建
        if (!is_dir($filePath)) {
            mkdir($filePath, 0755, true);
        }
        // 写入cert.pem文件
        if (!empty($cert_pem)) {
            file_put_contents($filePath . 'cert.pem', $cert_pem);
        }
        // 写入key.pem文件
        if (!empty($key_pem)) {
            file_put_contents($filePath . 'key.pem', $key_pem);
        }
        return true;
    }
    /**
     * 删除app缓存
     */
    public static function deleteCache()
    {
        return Cache::delete('app_wx_' . self::$app_id);
    }

}
