<?php

namespace app\shop\controller\user;

use app\shop\controller\Controller;
use app\shop\model\user\BalanceLog as BalanceLogModel;

/**
 * 余额明细
 */
class Balance extends Controller
{
    /**
     * 余额明细
     */
    public function log()
    {
        $model = new BalanceLogModel;
        return $this->renderSuccess('', [
            // 充值记录列表
            'list' => $model->getList($this->postData('Params')),
            // 属性集
            'attributes' => $model::getAttributes(),
        ]);
    }
}