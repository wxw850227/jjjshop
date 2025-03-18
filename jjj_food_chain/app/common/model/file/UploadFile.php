<?php

namespace app\common\model\file;

use app\common\model\BaseModel;
use think\facade\Request;

/**
 * 文件库模型
 */
class UploadFile extends BaseModel
{
    protected $pk = 'file_id';
    protected $name = 'upload_file';
    protected $updateTime = false;
    protected $deleteTime = false;
    protected $append = ['file_path'];

    /**
     * 关联文件库分组表
     */
    public function uploadGroup()
    {
        return $this->belongsTo('UploadGroup', 'group_id');
    }

    /**
     * 获取图片完整路径
     * @param $value
     * @param $data
     * @return string
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

    /**
     * 获取列表记录
     */
    public function getList($groupId, $fileType, $pageSize, $isRecycle, $shop_supplier_id = 0)
    {
        $model = $this;
        // 文件分组
        if ($groupId != 0) {
            $model = $model->where('group_id', '=', (int)$groupId);
        }
        // 文件类型
        !empty($fileType) && $model = $model->where('file_type', '=', trim($fileType));
        // 是否在回收站
        $isRecycle > -1 && $model = $model->where('is_recycle', '=', (int)$isRecycle);
        // 查询列表数据
        return $model->with(['upload_group'])
            ->where(['is_user' => 0, 'is_delete' => 0])
            ->where('shop_supplier_id', '=', $shop_supplier_id)
            ->order(['file_id' => 'desc'])
            ->paginate($pageSize);
    }
}
