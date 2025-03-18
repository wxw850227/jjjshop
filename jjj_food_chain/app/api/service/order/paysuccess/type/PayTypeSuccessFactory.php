<?php

namespace app\api\service\order\paysuccess\type;

use app\common\enum\order\OrderTypeEnum;

/**
 * 支付成功辅助工厂类
 */
class PayTypeSuccessFactory
{
    public static function getFactory($out_trade_no, $attach, $pay_status = 10)
    {
        switch ($attach['order_type']) {
            case OrderTypeEnum::MASTER:
                return new MasterPaySuccessService($out_trade_no, $attach);
                break;
        }
    }
}