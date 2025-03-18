<?php

namespace app\common\model\order;

use app\common\model\BaseModel;
use app\common\enum\settings\DeliverySourceEnum;

/**
 * 订单模型模型
 */
class OrderDeliver extends BaseModel
{
    protected $pk = 'deliver_id';
    protected $name = 'order_deliver';

    /**
     * 追加字段
     * @var string[]
     */
    protected $append = [
        'deliver_source_text',
        'deliver_status_text',
        'status_text',
    ];

    /**
     * 关联供应商表
     */
    public function supplier()
    {
        return $this->belongsTo('app\\common\\model\\supplier\\Supplier', 'shop_supplier_id', 'shop_supplier_id')->field(['shop_supplier_id', 'name', 'user_id', 'logo', 'city_id', 'region_id']);
    }

    /**
     * 关联供应商表
     */
    public function orders()
    {
        return $this->belongsTo('app\\common\\model\\order\\Order', 'order_id', 'order_id');
    }

    /**
     * 配送状态(待接单＝1,待取货＝2,配送中＝3,已完成＝4,已取消＝5)
     * @param $value
     * @return array
     */
    public function getDeliverStatusTextAttr($value, $data)
    {
        $status = [1 => '待接单', 2 => '待取货', 3 => '配送中', 4 => '已完成', 5 => '已取消'];
        return $status[$data['deliver_status']];
    }

    /**
     * 订单状态
     * @param $value
     * @return array
     */
    public function getStatusTextAttr($value, $data)
    {
        $status = [10 => '进行中', 20 => '已取消', 30 => '已完成'];
        return $status[$data['status']];
    }

    /**
     * 配送类型
     * @param $value
     * @return array
     */
    public function getDeliverSourceTextAttr($value, $data)
    {
        return DeliverySourceEnum::data()[$data['deliver_source']]['name'];
    }


    /**
     * 订单详情
     * @param $where
     * @param string[] $with
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($where, $with = ['orders', 'supplier'])
    {
        is_array($where) ? $filter = $where : $filter['deliver_id'] = (int)$where;
        return self::with($with)->where($filter)->find();
    }

    //更新配送状态为完成
    public function updateDeliver()
    {
        return $this->save(['deliver_status' => 4, 'deliver_time' => time(), 'status' => 30]);
    }

    //更新配送状态为取消
    public function DeliverCancel()
    {
        return $this->save(['deliver_status' => 5, 'status' => 20]);
    }
}