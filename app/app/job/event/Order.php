<?php

namespace app\job\event;

use app\job\model\order\Order as OrderModel;
use think\facade\Cache;
use app\common\model\settings\Setting as SettingModel;
use app\job\service\OrderService;
use app\common\enum\order\OrderTypeEnum;
use app\common\service\order\OrderCompleteService;
use app\common\library\helper;

/**
 * 订单事件管理
 */
class Order
{
    // 订单模型
    private $model;

    // 服务类
    private $service;

    // 应用id
    private $appId;

    /**
     * 执行函数
     */
    public function handle($model)
    {
        if (!$model instanceof OrderModel)
            return new OrderModel and false;
        if (!$model::$app_id) return false;
        // 绑定订单模型
        $this->model = $model;
        $this->appId = $model::$app_id;
        // 普通订单行为管理
        $this->master();
        return true;
    }

    /**
     * 普通订单行为管理
     */
    private function master()
    {
        $key = "task_space__order__{$this->appId}";
        if (Cache::has($key)) return true;
        // 获取商城交易设置
        $this->service = new OrderService;
        $config = SettingModel::getItem('trade');
        $this->model->transaction(function () use ($config) {
            // 未支付订单自动关闭
            $this->close($config['order']['close_days']);
            // 已发货订单自动确认收货
            $this->receive($config['order']['receive_days']);
            // 已完成订单结算
            $this->settled($config['order']['refund_days']);
        });
        Cache::set($key, time(), 3600);
        return true;
    }

    /**
     * 未支付订单自动关闭
     */
    private function close($closeDays)
    {
        // 取消n天以前的的未付款订单
        if ($closeDays < 1) return false;
        // 截止时间
        $deadlineTime = time() - ((int)$closeDays * 86400);
        // 执行自动关闭
        $this->service->close($deadlineTime);
        // 记录日志
        $this->dologs('close', [
            'close_days' => (int)$closeDays,
            'deadline_time' => $deadlineTime,
            'orderIds' => json_encode($this->service->getCloseOrderIds()),
        ]);
        return true;
    }

    /**
     * 已发货订单自动确认收货
     */
    private function receive($receiveDays)
    {
        // 截止时间
        if ($receiveDays < 1) return false;
        $deadlineTime = time() - ((int)$receiveDays * 86400);
        // 条件
        // 订单id集
        $orderId_arr = $this->model->where('pay_status', '=', 20)
            ->where('delivery_status', '=', 20)
            ->where('receipt_status', '=', 10)
            ->where('delivery_time', '<=', $deadlineTime)
            ->column('order_id');
        $orderIds = helper::getArrayColumnIds($orderId_arr);
        if (!empty($orderIds)) {
            // 更新订单收货状态
            $this->model->onBatchUpdate($orderIds, [
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 批量处理已完成的订单
            $this->onReceiveCompleted($orderIds);
        }
        // 记录日志
        $this->dologs('receive', [
            'receive_days' => (int)$receiveDays,
            'deadline_time' => $deadlineTime,
            'orderIds' => json_encode($orderIds),
        ]);
        return true;
    }

    /**
     * 已完成订单结算
     */
    private function settled($refundDays)
    {
        // 获取已完成的订单（未累积用户实际消费金额）
        // 条件1：订单状态：已完成
        // 条件2：超出售后期限
        // 条件3：is_settled 为 0
        // 截止时间
        $deadlineTime = time() - ((int)$refundDays * 86400);
        // 查询订单列表
        $orderList = $this->model->getSettledList($deadlineTime, [
            'product' => ['refund'],  // 用于计算售后退款金额
        ]);
        // 订单id集
        $orderIds = helper::getArrayColumn($orderList, 'order_id');
        // 订单结算服务
        $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
        !empty($orderIds) && $OrderCompleteService->settled($orderList);
        // 记录日志
        $this->dologs('settled', [
            'refund_days' => (int)$refundDays,
            'deadline_time' => $deadlineTime,
            'orderIds' => json_encode($orderIds),
        ]);
    }

    /**
     * 批量处理已完成的订单
     */
    private function onReceiveCompleted($orderIds)
    {
        // 获取已完成的订单列表
        $list = $this->model->getReceiveList($orderIds, [
            'product' => ['refund'],  // 用于发放分销佣金
            'user', 'address', 'product', 'express',  // 用于同步微信好物圈
        ]);
        if ($list->isEmpty()) return false;
        // 执行订单完成后的操作
        $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
        $OrderCompleteService->complete($list, $this->appId);
        return true;
    }

    /**
     * 记录日志
     */
    private function dologs($method, $params = [])
    {
        $value = 'behavior Order --' . $method;
        foreach ($params as $key => $val)
            $value .= ' --' . $key . ' ' . $val;
        return log_write($value);
    }

}
