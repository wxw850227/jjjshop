<?php

namespace app\common\service\order;

use app\common\library\helper;
use app\common\model\supplier\Supplier as SupplierModel;
use app\common\model\user\User as UserModel;
use app\common\enum\order\OrderTypeEnum;

/**
 * 已完成订单结算服务类
 */
class OrderCompleteService
{
    // 订单类型
    private $orderType;

    /**
     * 订单模型类
     * @var array
     */
    private $orderModelClass = [
        OrderTypeEnum::MASTER => 'app\common\model\order\Order',
    ];

    // 模型
    private $model;

    /* @var UserModel $model */
    private $UserModel;

    private $supplierModel;

    /**
     * 构造方法
     */
    public function __construct($orderType = OrderTypeEnum::MASTER)
    {
        $this->orderType = $orderType;
        $this->model = $this->getOrderModel();
        $this->UserModel = new UserModel;
        $this->supplierModel = new SupplierModel();
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
        $this->settled($orderList);
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
        // 供应商结算
        $this->setIncSupplierMoney($orderList);
        return true;
    }

    /**
     * 供应商金额=支付金额-运费
     */
    private function setIncSupplierMoney($orderList)
    {
        // 计算并累积实际消费金额(需减去售后退款的金额)
        $supplierData = [];
        foreach ($orderList as $order) {
            if ($order['shop_supplier_id'] == 0 || $order['is_settled'] == 1) {
                continue;
            }
            // 供应价格+运费
            $supplierMoney = $order['pay_price'] - $order['refund_money'];
            //线下支付不累积余额
            if (in_array($order['pay_type']['value'], [10, 20])) {
                !isset($supplierData[$order['shop_supplier_id']]) && $supplierData[$order['shop_supplier_id']] = 0.00;
                $supplierMoney > 0 && $supplierData[$order['shop_supplier_id']] += $supplierMoney;
            }
        }
        // 累积到供应商表记录
        $supplierData && $this->supplierModel->onBatchIncSupplierMoney($supplierData);
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
            if ($order['user_id'] == 0) {
                continue;
            }
            // 订单实际支付金额
            $expendMoney = $order['pay_price'];
            // 减去订单退款的金额
            $expendMoney = $expendMoney - $order['refund_money'];
            !isset($userData[$order['user_id']]) && $userData[$order['user_id']] = 0.00;
            $expendMoney > 0 && $userData[$order['user_id']] += $expendMoney;
        }
        // 累积到会员表记录
        $userData && $this->UserModel->onBatchIncExpendMoney($userData);
        return true;
    }

}