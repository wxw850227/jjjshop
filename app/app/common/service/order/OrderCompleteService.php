<?php

namespace app\common\service\order;

use app\common\library\helper;
use app\common\model\user\User as UserModel;
use app\common\model\settings\Setting as SettingModel;
use app\common\enum\order\OrderTypeEnum;

/**
 * 已完成订单结算服务类
 */
class OrderCompleteService
{
    //订单类型
    private $orderType;

    //订单模型类
    private $orderModelClass = [
        OrderTypeEnum::MASTER => 'app\common\model\order\Order',
    ];

    //订单模型
    private $model;
    //用户模型
    private $UserModel;

    /**
     * 构造方法
     */
    public function __construct($orderType = OrderTypeEnum::MASTER)
    {
        $this->orderType = $orderType;
        $this->model = $this->getOrderModel();
        $this->UserModel = new UserModel;
    }

    /**
     * 初始化订单模型类
     */
    private function getOrderModel()
    {
        $class = $this->orderModelClass[$this->orderType];
        return new $class;
    }

    /**
     * 执行订单完成后的操作
     */
    public function complete($orderList, $appId)
    {
        // 已完成订单结算
        // 条件：后台订单流程设置 - 已完成订单设置0天不允许申请售后
        if (SettingModel::getItem('trade', $appId)['order']['refund_days'] == 0) {
            $this->settled($orderList);
        }
        return true;
    }

    /**
     * 执行订单结算
     */
    public function settled($orderList)
    {
        // 订单id集
        $orderIds = helper::getArrayColumn($orderList, 'order_id');
        // 累积用户实际消费金额
        $this->setIncUserExpend($orderList);
        // 将订单设置为已结算
        $this->model->onBatchUpdate($orderIds, ['is_settled' => 1]);
        return true;
    }

    /**
     * 累积用户实际消费金额
     */
    private function setIncUserExpend($orderList)
    {
        // 计算并累积实际消费金额(需减去售后退款的金额)
        $userData = [];
        foreach ($orderList as $order) {
            // 订单实际支付金额
            $expendMoney = $order['pay_price'];
            // 减去订单退款的金额
            foreach ($order['product'] as $product) {
                if (
                    !empty($product['refund'])
                    && $product['refund']['type']['value'] == 10      // 售后类型：退货退款
                    && $product['refund']['is_agree']['value'] == 10  // 商家审核：已同意
                ) {
                    $expendMoney -= $product['refund']['refund_money'];
                }
            }
            !isset($userData[$order['user_id']]) && $userData[$order['user_id']] = 0.00;
            $expendMoney > 0 && $userData[$order['user_id']] += $expendMoney;
        }
        // 累积到会员表记录
        $this->UserModel->onBatchIncExpendMoney($userData);
        return true;
    }

}