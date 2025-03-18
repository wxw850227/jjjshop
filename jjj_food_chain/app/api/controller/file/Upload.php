<?php

namespace app\api\controller\file;

use app\api\controller\Controller;
use app\api\model\file\UploadFile as UploadFileModel;
use app\api\model\settings\Setting as SettingModel;
use app\common\library\storage\Driver as StorageDriver;

/**
 * 文件库管理
 */
class Upload extends Controller
{
    protected $config;
    protected $user;

    /**
     * 构造方法
     */
    public function initialize()
    {
        parent::initialize();
        // 存储配置信息
        $this->config = SettingModel::getItem('storage');
        // 验证用户
        $this->user = $this->getUser();
    }

    /**
     * 图片上传接口
     */
    public function image()
    {

        // 实例化存储驱动
        $StorageDriver = new StorageDriver($this->config);
        // 图片信息
        $fileInfo = request()->file('iFile');
        if(!$StorageDriver->validate('iFile', $fileInfo, 'image')){
            return json(['code' => 0, 'msg' => $StorageDriver->getError()]);
        }
        // 设置上传文件的信息
        $StorageDriver->setUploadFile('iFile');
        // 上传图片
        $saveName = $StorageDriver->upload();
        if ($saveName == '') {
            return json(['code' => 0, 'msg' => '图片上传失败' . $StorageDriver->getError()]);
        }
        $saveName = str_replace('\\', '/', $saveName);
        // 图片上传路径
        $fileName = $StorageDriver->getFileName();

        // 添加文件库记录
        $uploadFile = $this->addUploadFile($fileName, $fileInfo, 'image', $saveName);
        $data = [
            'file_id' => $uploadFile['file_id'],
            'file_path' => $uploadFile['file_path'],
        ];
        // 图片上传成功
        return json(['code' => 1, 'msg' => '图片上传成功', 'data' => $data]);
    }

    /**
     * 添加文件库上传记录
     */
    private function addUploadFile($fileName, $fileInfo, $fileType, $savename)
    {
        // 存储引擎
        $storage = $this->config['default'];
        // 存储域名
        $fileUrl = isset($this->config['engine'][$storage]['domain'])
            ? $this->config['engine'][$storage]['domain'] : '';
        // 添加文件库记录
        $model = new UploadFileModel;
        $data = $this->postData();
        $model->add([
            'storage' => $storage,
            'file_url' => $fileUrl,
            'file_name' => $fileName,
            'save_name' => $savename,
            'file_size' => $fileInfo->getSize(),
            'file_type' => $fileType,
            'extension' => $fileInfo->getOriginalExtension(),
            'real_name' => $fileInfo->getOriginalName(),
            'is_user' => 1,
            'app_id' => $data['app_id']
        ]);
        return $model;
    }

}