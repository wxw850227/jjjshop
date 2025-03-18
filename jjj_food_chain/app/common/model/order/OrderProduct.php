<?php

namespace app\common\model\order;

use app\common\model\BaseModel;

/**
 * 订单商品模型
 */
class OrderProduct extends BaseModel
{
    protected $name = 'order_product';
    protected $pk = 'order_product_id';

    /**
     * 订单商品列表
     * @return \think\model\relation\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo('app\\common\\model\\file\\UploadFile', 'image_id', 'file_id');
    }

    /**
     * 关联商品表
     * @return \think\model\relation\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('app\\common\\model\\product\\Product');
    }

    /**
     * 关联商品sku表
     * @return \think\model\relation\BelongsTo
     */
    public function sku()
    {
        return $this->belongsTo('app\\common\\model\\product\\ProductSku', 'spec_sku_id', 'spec_sku_id');
    }

    /**
     * 关联订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderM()
    {
        return $this->belongsTo('Order', 'order_id', 'order_id');
    }

    /**
     * 关联分销商
     * @return \think\model\relation\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('app\\common\\model\\agent\\Apply', 'agent_user_id', 'user_id');
    }

    /**
     * 订单商品详情
     * @param $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($where)
    {
        return static::with(['image', 'orderM'])->find($where);
    }

    /**
     * 获取商品数据 (可指定某天)
     */
    public function getProductData($startDate, $endDate, $type, $shop_supplier_id)
    {
        $model = $this;
        if ($shop_supplier_id > 0) {
            $model = $model->where('order.shop_supplier_id', '=', $shop_supplier_id);
        }
        $model = $model->alias('order_product')
            ->join('order order', 'order_product.order_id = order.order_id', 'left');

        $model = $model->where('order.create_time', '>=', strtotime($startDate));
        if (is_null($endDate)) {
            $model = $model->where('order.create_time', '<', strtotime($startDate) + 86400);
        } else {
            $model = $model->where('order.create_time', '<', strtotime($endDate) + 86400);
        }

        if ($type == 'no_pay') {
            // 未支付
            return $model->where('order.pay_status', '=', 10)->sum('order_product.total_num');
        } else if ($type == 'pay') {
            // 已支付
            return $model->where('order.pay_status', '=', 20)->sum('order_product.total_num');
        }
        return 0;
    }
}