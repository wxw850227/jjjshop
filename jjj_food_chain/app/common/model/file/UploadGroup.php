<?php

namespace app\common\model\file;

use app\common\model\BaseModel;
use app\shop\model\file\UploadFile;

/**
 * 文件库分组模型
 */
class UploadGroup extends BaseModel
{
    protected $name = 'upload_group';
    protected $pk = 'group_id';

    /**
     * 分组详情
     */
    public static function detail($group_id, $shop_supplier_id = 0)
    {
        return self::where('shop_supplier_id', '=', $shop_supplier_id)->find($group_id);
    }

    /**
     * 获取列表记录
     */
    public function getList($groupType, $shop_supplier_id = 0)
    {
        !empty($groupType) && $this->where('group_type', '=', trim($groupType));
        return $this->where('group_type', '=', trim($groupType))
            ->where('shop_supplier_id', '=', $shop_supplier_id)
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 添加新记录
     */
    public function add($data)
    {
        return $this->save(array_merge([
            'app_id' => self::$app_id,
            'sort' => 100
        ], $data));
    }

    /**
     * 更新记录
     */
    public function edit($data)
    {
        return $this->save($data) !== false;
    }

    /**
     * 删除记录
     */
    public function remove()
    {
        // 更新该分组下的所有文件
        (new UploadFile())->where('group_id', '=', $this['group_id'])
            ->update(['group_id' => 0]);
        // 删除分组
        return $this->save(['is_delete' => 1]);
    }
}
