<?php

namespace app\common\service\order;

use app\common\enum\order\OrderTypeEnum;

/**
 * 订单服务类
 */
class OrderService
{
    //订单模型类
    private static $orderModelClass = [
        OrderTypeEnum::MASTER => 'app\common\model\order\Order'
    ];

    /**
     * 生成订单号
     */
    public static function createOrderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 整理订单列表 (根据order_type获取不同类型的订单记录)
     */
    public static function getOrderList($data, $orderIndex = 'order', $with = [])
    {
        // 整理订单id
        $orderIds = [];
        foreach ($data as &$item) {
            $orderIds[$item['order_type']['value']][] = $item['order_id'];
        }
        // 获取订单列表
        $orderList = [];
        foreach ($orderIds as $orderType => $values) {
            $model = self::model($orderType);
            $orderList[$orderType] = $model->getListByIds($values, $with);
        }
        // 格式化到数据源
        foreach ($data as $key => &$item) {
            if (!isset($orderList[$item['order_type']['value']][$item['order_id']])) {
                $item->delete();
                unset($data[$key]);
                continue;
            }
            $item[$orderIndex] = $orderList[$item['order_type']['value']][$item['order_id']];
        }
        return $data;
    }

    /**
     * 获取订单详情 (根据order_type获取不同类型的订单详情)
     */
    public static function getOrderDetail($orderId, $orderType = OrderTypeEnum::MASTER)
    {
        $model = self::model($orderType);
        return $model::detail($orderId);
    }

    /**
     * 根据订单类型获取对应的订单模型类
     */
    public static function model($orderType = OrderTypeEnum::MASTER)
    {
        static $models = [];

        if (!isset($models[$orderType])) {
            $models[$orderType] = new self::$orderModelClass[$orderType];
        }
        return $models[$orderType];
    }

}