<?php

namespace app\common\library\storage\engine;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

/**
 * 七牛云存储引擎
 */
class Qiniu extends Server
{
    private $config;

    /**
     * 构造方法
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * 执行上传
     */
    public function upload()
    {
        // 要上传图片的本地路径
        $realPath = $this->getRealPath();

        // 构建鉴权对象
        $auth = new Auth($this->config['access_key'], $this->config['secret_key']);

        // 要上传的空间
        $token = $auth->uploadToken($this->config['bucket']);

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list(, $error) = $uploadMgr->putFile($token, $this->fileName, $realPath);

        if ($error !== null) {
            $this->error = $error->message();
            return false;
        }
        return true;
    }

    /**
     * 删除文件
     */
    public function delete($fileName)
    {
        // 构建鉴权对象
        $auth = new Auth($this->config['access_key'], $this->config['secret_key']);
        // 初始化 UploadManager 对象并进行文件的上传
        $bucketMgr = new BucketManager($auth);
        $error = $bucketMgr->delete($this->config['bucket'], $fileName);
        if ($error !== null) {
            $this->error = $error->message();
            return false;
        }
        return true;
    }

    /**
     * 返回文件路径
     */
    public function getFileName()
    {
        return $this->fileName;
    }

}
