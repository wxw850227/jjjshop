<?php

namespace app\common\model\order;

use app\common\model\BaseModel;

/**
 * 售后管理模型
 */
class OrderRefund extends BaseModel
{
    //表名
    protected $name = 'order_refund';
    //主键字段名
    protected $pk = 'order_refund_id';

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo('app\\common\\model\\user\\User');
    }

    /**
     * 关联订单主表
     */
    public function orderMaster()
    {
        return $this->belongsTo('app\\common\\model\\order\\Order');
    }

    /**
     * 关联订单商品表
     */
    public function orderproduct()
    {
        return $this->belongsTo('app\\common\\model\\order\\OrderProduct', 'order_product_id', 'order_product_id');
    }

    /**
     * 关联图片记录表
     */
    public function image()
    {
        return $this->hasMany('app\\common\\model\\order\\OrderRefundImage');
    }

    /**
     * 关联物流公司表
     */
    public function express()
    {
        return $this->belongsTo('app\\api\\model\\settings\\Express');
    }

    /**
     * 关联用户表
     */
    public function address()
    {
        return $this->hasOne('app\\api\\model\\order\\OrderRefundAddress');
    }

    /**
     * 售后类型
     */
    public function getTypeAttr($value)
    {
        $status = [10 => '退货退款', 20 => '换货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 商家是否同意售后
     */
    public function getIsAgreeAttr($value)
    {
        $status = [0 => '待审核', 10 => '已同意', 20 => '已拒绝'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 售后单状态
     */
    public function getStatusAttr($value)
    {
        $status = [0 => '进行中', 10 => '已拒绝', 20 => '已完成', 30 => '已取消'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 售后单详情
     */
    public static function detail($where)
    {
        return static::with(['orderMaster', 'image.file', 'orderproduct.image', 'express', 'address', 'user'])->find($where);
    }

}