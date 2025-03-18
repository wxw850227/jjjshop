<?php

namespace app\job\event;

use app\job\model\order\Order as OrderModel;
use think\facade\Cache;
use app\common\model\settings\Setting as SettingModel;
use app\job\service\OrderService;
use app\common\enum\order\OrderTypeEnum;
use app\common\service\order\OrderCompleteService;
use app\common\library\helper;
use app\common\model\order\OrderDeliver as OrderDeliverModel;

/**
 * 订单事件管理
 */
class Order
{
    // 模型
    private $model;

    // 应用id
    private $appId;

    /**
     * 执行函数
     */
    public function handle($app_id)
    {
        try {
            $this->appId = $app_id;
            $this->model = new OrderModel();
            // 普通订单行为管理
            $this->master();
            // 未支付订单自动关闭
            $this->close();
            //自动配送订单
            $this->sellerDeliver();
        } catch (\Throwable $e) {
            echo 'ERROR ORDER: ' . $e->getMessage() . PHP_EOL;
            log_write('ORDER TASK : ' . $app_id . '__ ' . $e->getMessage(), 'task');
        }
        return true;
    }

    /**
     * 普通订单行为管理
     * 1分钟执行一次
     */
    private function master()
    {
        $key = "task_space__order__{$this->appId}";
        if (Cache::has($key)) return true;
        // 获取商城交易设置
        $config = SettingModel::getItem('trade', $this->appId);
        $this->model->transaction(function () use ($config) {
            // 已支付订单自动核销
            $this->receive($config['order']['receive_days']);
        });
        Cache::set($key, time(), 60);
        return true;
    }

    /**
     * 未支付订单自动关闭
     */
    private function close()
    {
        $config = SettingModel::getItem('trade', $this->appId);
        if ($config['order']['close_days'] <= 0) {
            return false;
        }
        $service = new OrderService();
        // 执行自动关闭
        $service->close($this->appId);
        // 记录日志
        $this->dologs('close', [
            'orderIds' => json_encode($service->getCloseOrderIds()),
            'close_days' => $config['order']['close_days']
        ]);
        return true;
    }

    /**
     * 已支付订单自动核销
     */
    private function receive($receiveDays)
    {
        // 截止时间
        if ($receiveDays <= 0) return false;
        $deadlineTime = time() - ($receiveDays * 60);
        // 条件
        // 订单id集
        $orderId_arr = $this->model->where('pay_status', '=', 20)
            ->where('order_status', '=', 10)
            ->where('app_id', '=', $this->appId)
            ->where('pay_time', '<=', $deadlineTime)
            ->column('order_id');
        $orderIds = helper::getArrayColumnIds($orderId_arr);
        if (!empty($orderIds)) {
            // 更新订单状态
            $this->model->onBatchUpdate($orderIds, [
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            $this->model->onBatchUpdateStatus($orderIds, [
                'delivery_status' => 20,
                'delivery_time' => time(),
            ]);
            //批量更新配送状态
            OrderDeliverModel::where('order_id', 'in', $orderIds)
                ->where('deliver_source', '=', 10)
                ->where('status', '=', 10)
                ->update(['deliver_status' => 4, 'deliver_time' => time(), 'status' => 30]);
            // 批量处理已完成的订单
            $this->onReceiveCompleted($orderIds);
        }
        // 记录日志
        $this->dologs('receive', [
            'receive_days' => $receiveDays,
            'deadline_time' => $deadlineTime,
            'orderIds' => json_encode($orderIds),
        ]);
        return true;
    }

    /**
     * 批量处理已完成的订单
     */
    private function onReceiveCompleted($orderIds)
    {
        // 获取已完成的订单列表
        $list = $this->model->getReceiveList($orderIds, ['user']);
        if ($list->isEmpty()) return false;
        // 执行订单完成后的操作
        $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
        $OrderCompleteService->complete($list, $this->appId);
        return true;
    }

    /**
     * 未配送订单自动配送
     */
    private function sellerDeliver()
    {
        $orderIds = $this->model->getDeliverList($this->appId);
        if (!$orderIds) {
            return false;
        }
        $deliverOrderId = [];
        foreach ($orderIds as $key => $item) {
            $detail = $this->model::detail($item);
            $result = $this->model->sellerDeliver($detail, $this->appId);
            if ($result) {
                $deliverOrderId[] = $item;
            }
        }
        // 记录日志
        $this->dologs('sellerDeliver', [
            'deliverOrderId' => json_encode($deliverOrderId),
        ]);
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
        return log_write($value, 'task');
    }

}
