<?php

namespace app\api\service\order\paysuccess\source;


/**
 * 普通订单支付成功后的回调
 */
class MasterPaySuccessService
{
    /**
     * 回调方法
     */
    public function onPaySuccess($order)
    {
        return true;
    }

}