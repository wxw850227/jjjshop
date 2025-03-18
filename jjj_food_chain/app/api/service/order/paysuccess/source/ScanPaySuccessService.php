<?php

namespace app\api\service\order\paysuccess\source;


/**
 * 扫码订单支付成功后的回调
 */
class ScanPaySuccessService
{
    /**
     * 回调方法
     */
    public function onPaySuccess($order)
    {
        return true;
    }

}