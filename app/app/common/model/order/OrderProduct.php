<?php

namespace app\common\model\order;

use app\common\model\BaseModel;

/**
 * 订单商品模型
 */
class OrderProduct extends BaseModel
{
    //表名
    protected $name = 'order_product';
    //主键字段名
    protected $pk = 'order_product_id';

    /**
     * 订单商品列表
     */
    public function image()
    {
        $model = "app\\common\\model\\file\\UploadFile";
        return $this->belongsTo($model, 'image_id', 'file_id');
    }

    /**
     * 关联商品表
     */
    public function product()
    {
        return $this->belongsTo('app\\common\\model\\product\\Product');
    }

    /**
     * 关联商品sku表
     */
    public function sku()
    {
        return $this->belongsTo('app\\common\\model\\product\\ProductSku', 'spec_sku_id', 'spec_sku_id');
    }

    /**
     * 关联订单主表
     */
    public function orderMaster()
    {
        return $this->belongsTo('Order','order_id','order_id');
    }

    /**
     * 售后单记录表
     */
    public function refund()
    {
        return $this->hasOne('app\\common\\model\\order\\OrderRefund');
    }

    /**
     * 订单商品详情
     */
    public static function detail($where)
    {
        return static::with(['image', 'refund'])->find($where);
    }

}