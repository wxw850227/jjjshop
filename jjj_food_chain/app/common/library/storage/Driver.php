<?php

namespace app\common\library\storage;

use think\Exception;

/**
 * 存储模块驱动
 */
class Driver
{
    private $config;    // upload 配置
    private $engine;    // 当前存储引擎类
    protected $error;

    /**
     * 构造方法
     */
    public function __construct($config, $storage = null)
    {
        $this->config = $config;
        // 实例化当前存储引擎
        $this->engine = $this->getEngineClass($storage);
    }

    public function validate($name, $fileInfo, $sence = 'image'){
        if($sence == 'image'){
            // 文件校验
            try{
                validate([$name=>[
                    'fileSize' => $this->config['max_image'] * 1024 * 1024, //2M
                    'fileExt' => 'jpg,jpeg,png,gif,bmp',
                    'fileMime' => 'image/jpeg,image/png,image/gif,image/bmp',
                ]],
                    [
                        $name.'.fileSize' => '最大可上传'.$this->config['max_image'].'M图片',
                        $name.'.fileExt' => '只能上传jpg,jpeg,png,gif,bmp格式图片',
                        $name.'.fileMime' => '只能上传jpg,jpeg,png,gif,bmp格式图片'
                    ]
                )->check([$name => $fileInfo]);
                return true;
            }catch (\Exception $e){
                $this->engine->error = $e->getMessage();
                return false;
            }
        }
        return false;
    }

    /**
     * 设置上传的文件信息
     */
    public function setUploadFile($name = 'iFile')
    {
        return $this->engine->setUploadFile($name);
    }

    /**
     * 设置上传的文件信息
     */
    public function setUploadFileByReal($filePath)
    {
        return $this->engine->setUploadFileByReal($filePath);
    }

    /**
     * 执行文件上传
     */
    public function upload()
    {
        return $this->engine->upload();
    }

    /**
     * 执行文件删除
     */
    public function delete($fileName)
    {
        return $this->engine->delete($fileName);
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->engine->getError();
    }

    /**
     * 获取文件路径
     */
    public function getFileName()
    {
        return $this->engine->getFileName();
    }

    /**
     * 返回文件信息
     */
    public function getFileInfo()
    {
        return $this->engine->getFileInfo();
    }

    /**
     * 获取当前的存储引擎
     */
    private function getEngineClass($storage = null)
    {
        $engineName = is_null($storage) ? $this->config['default'] : $storage;
        $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst($engineName);
        if (!class_exists($classSpace)) {
            throw new Exception('未找到存储引擎类: ' . $engineName);
        }
        return new $classSpace($this->config['engine'][$engineName]);
    }

}
