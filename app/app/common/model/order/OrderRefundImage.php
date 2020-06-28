<?php

namespace app\common\model\order;

use app\common\model\BaseModel;
/**
 * 售后单图片模型
 */
class OrderRefundImage extends BaseModel
{
    //表名
    protected $name = 'order_refund_image';
    //主键字段名
    protected $pk = 'id';
    //关闭更新时间
    protected $updateTime = false;

    /**
     * 关联文件库
     */
    public function file()
    {
        return $this->belongsTo('app\\common\\model\\file\\UploadFile', 'image_id', 'file_id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }

}
