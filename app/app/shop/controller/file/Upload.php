<?php

namespace app\shop\controller\file;

use app\JjjController;
use app\shop\model\file\UploadFile;
use app\common\library\storage\Driver as StorageDriver;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 文件库管理
 */
class Upload extends JjjController
{
    /**
     * 图片上传接口
     */
    public function image($group_id = -1)
    {
        // 实例化存储驱动
        $config = SettingModel::getItem('storage');

        $StorageDriver = new StorageDriver($config);

        // 设置上传文件的信息
        $StorageDriver->setUploadFile('iFile');

        // 上传图片
        $saveName = $StorageDriver->upload();
        if ($saveName == '') {
            return json(['code' => 0, 'msg' => '图片上传失败' . $StorageDriver->getError()]);
        }

        $saveName = str_replace('\\','/',$saveName);

        // 图片上传路径
        $fileName = $StorageDriver->getFileName();
        // 图片信息
        $fileInfo = request()->file('iFile');

        // 添加文件库记录
        $uploadFile = $this->addUploadFile($group_id, $fileName, $fileInfo, 'image', $saveName);
        // 图片上传成功
        return json(['code' => 1, 'msg' => '图片上传成功', 'data' => $uploadFile]);
    }

    /**
     * 添加文件库上传记录
     */
    private function addUploadFile($group_id, $fileName, $fileInfo, $fileType, $savename)
    {
        // 存储引擎
        $config = SettingModel::getItem('storage');
        $storage = $config['default'];
        // 存储域名
        $fileUrl = isset($config['engine'][$storage]['domain'])
            ? $config['engine'][$storage]['domain'] : '';
        // 添加文件库记录
        $model = new UploadFile;
        $model->save([
            'group_id' => $group_id > 0 ? (int)$group_id : 0,
            'storage' => $storage,
            'file_url' => $fileUrl,
            'file_name' => $fileName,
            'save_name' => $savename,
            'file_size' => $fileInfo->getSize(),
            'file_type' => $fileType,
            'extension' => $fileInfo->getOriginalExtension(),
            'real_name' => $fileInfo->getOriginalName(),
            'app_id' => $model::$app_id
        ]);
        return $model;
    }
    /**
     * 批量移动文件分组
     */
    public function moveFiles($group_id, $fileIds)
    {
        $model = new UploadFile;
        if ($model->moveGroup($group_id, $fileIds) !== false) {
            return $this->renderSuccess('移动成功');
        }
        return $this->renderError($model->getError() ?: '移动失败');
    }
}
