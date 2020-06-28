<?php

namespace app\common\model\file;

use app\common\model\BaseModel;
/**
 * 文件库模型
 */
class UploadFile extends BaseModel
{
    //表名
    protected $name = 'upload_file';
    //主键字段名
    protected $pk = 'file_id';
    //关闭更新时间
    protected $updateTime = false;
    //追加字段
    protected $append = [
        'file_path',    //文件完整地址
    ];

    /**
     * 关联文件库分组表
     */
    public function uploadGroup()
    {
        return $this->belongsTo('UploadGroup', 'group_id');
    }

    /**
     * 获取图片完整路径
     */
    public function getFilePathAttr($value, $data)
    {
        if ($data['storage'] === 'local') {
            return self::$base_url . 'uploads/' . $data['save_name'];
        }
        return $data['file_url'] . '/' . $data['file_name'];
    }

    /**
     * 文件详情
     */
    public static function detail($file_id)
    {
        return self::find($file_id);
    }

    /**
     * 根据文件名查询文件id
     */
    public static function getFildIdByName($fileName)
    {
        return (new static)->where(['file_name' => $fileName])->value('file_id');
    }

    /**
     * 查询文件id
     */
    public static function getFileName($fileId)
    {
        return (new static)->where(['file_id' => $fileId])->value('file_name');
    }

    /**
     * 添加新记录
     */
    public function add($data)
    {
        return $this->save($data);
    }

}
