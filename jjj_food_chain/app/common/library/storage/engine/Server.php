<?php

namespace app\common\library\storage\engine;

use think\facade\Request;
use think\Exception;

/**
 * 存储引擎抽象类
 */
abstract class Server
{
    protected $file;
    public $error;
    protected $fileName;
    protected $fileInfo;

    // 是否为内部上传
    protected $isInternal = false;

    /**
     * 构造函数
     */
    protected function __construct()
    {
    }

    /**
     * 设置上传的文件信息
     */
    public function setUploadFile($name)
    {
        // 接收上传的文件
        $this->file = Request::file($name);
        if (empty($this->file)) {
            throw new Exception('未找到上传文件的信息');
        }
        // 生成保存文件名
        $this->fileName = $this->buildSaveName();
    }

    /**
     * 设置上传的文件信息
     */
    public function setUploadFileByReal($filePath)
    {
        // 设置为系统内部上传
        $this->isInternal = true;
        // 文件信息
        $this->fileInfo = [
            'name' => basename($filePath),
            'size' => filesize($filePath),
            'tmp_name' => $filePath,
            'error' => 0,
        ];
        // 生成保存文件名
        $this->fileName = $this->buildSaveName();
    }

    /**
     * 文件上传
     */
    abstract protected function upload();

    /**
     * 文件删除
     */
    abstract protected function delete($fileName);

    /**
     * 返回上传后文件路径
     */
    abstract public function getFileName();

    /**
     * 返回文件信息
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    protected function getRealPath()
    {
        $fileInfo = request()->file('iFile');
        return $fileInfo->getRealPath();
    }

    /**
     * 返回错误信息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 生成保存文件名
     */
    private function buildSaveName()
    {
        // 要上传图片的本地路径
        $realPath = $this->file->getPathname();
        // 扩展名
        $ext = $this->file->getOriginalExtension();

        // 自动生成文件名
        return date('YmdHis') . substr(md5($realPath), 0, 5)
            . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . ".{$ext}";
    }

}
