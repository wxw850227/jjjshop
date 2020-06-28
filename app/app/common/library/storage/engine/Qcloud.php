<?php

namespace app\common\library\storage\engine;

use Qcloud\Cos\Client;

/**
 * 腾讯云存储引擎 (COS)
 */
class Qcloud extends Server
{
    private $config;
    private $cosClient;

    /**
     * 构造方法
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
        // 创建COS控制类
        $this->createCosClient();
    }

    /**
     * 创建COS控制类
     */
    private function createCosClient()
    {
        $this->cosClient = new Client([
            'region' => $this->config['region'],
            'credentials' => [
                'secretId' => $this->config['secret_id'],
                'secretKey' => $this->config['secret_key'],
            ],
        ]);
    }

    /**
     * 执行上传
     */
    public function upload()
    {
        // 上传文件
        // putObject(上传接口，最大支持上传5G文件)
        try {
            $this->cosClient->putObject([
                'Bucket' => $this->config['bucket'],
                'Key' => $this->fileName,
                'Body' => fopen($this->getRealPath(), 'rb')
            ]);
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 删除文件
     */
    public function delete($fileName)
    {
        try {
            $result = $this->cosClient->deleteObject(array(
                'Bucket' => $this->config['bucket'],
                'Key' => $fileName
            ));
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 返回文件路径
     */
    public function getFileName()
    {
        return $this->fileName;
    }

}
