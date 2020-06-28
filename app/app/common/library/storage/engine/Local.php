<?php

namespace app\common\library\storage\engine;

use think\facade\Filesystem;

/**
 * 本地文件驱动
 */
class Local extends Server
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 上传图片文件
     */
    public function upload()
    {
        return $this->isInternal ? $this->uploadByInternal() : $this->uploadByExternal();
    }

    /**
     * 外部上传(指用户上传,需验证文件类型、大小)
     */
    private function uploadByExternal()
    {
        $saveName = '';
        // 验证文件并上传
        try{
            $saveName = Filesystem::disk('public')->putFile( '', $this->file);
        }catch (\Exception $e){
            log_write('文件上传异常:'.$e->getMessage());
        }
        return $saveName;
    }

    /**
     * 内部上传(指系统上传,信任模式)
     */
    private function uploadByInternal()
    {
        // 上传目录
        $uplodDir = WEB_PATH . 'uploads';
        // 要上传图片的本地路径
        $realPath = $this->getRealPath();
        if (!rename($realPath, "{$uplodDir}/$this->fileName")) {
            $this->error = 'upload write error';
            return false;
        }
        return true;
    }

    /**
     * 删除文件
     */
    public function delete($fileName)
    {
        // 文件所在目录
        $filePath = WEB_PATH . "uploads/{$fileName}";
        return !file_exists($filePath) ?: unlink($filePath);
    }

    /**
     * 返回文件路径
     */
    public function getFileName()
    {
        return $this->fileName;
    }

}
