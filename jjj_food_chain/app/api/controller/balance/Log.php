<?php

namespace app\api\controller\balance;

use app\api\controller\Controller;
use app\api\model\user\BalanceLog as BalanceLogModel;
use app\api\model\settings\Setting as SettingModel;

/**
 * 余额账单明细
 */
class Log extends Controller
{
    /**
     * 余额首页
     */
    public function index()
    {
        $user = $this->getUser();
        $list = (new BalanceLogModel)->getTop10($user['user_id']);
        // 余额
        $balance = $user['balance'];
        return $this->renderSuccess('', compact('list', 'balance'));
    }

    /**
     * 余额账单明细列表
     */
    public function lists($type = 'all')
    {
        $user = $this->getUser();
        $list = (new BalanceLogModel)->getList($user['user_id'], $type);
        return $this->renderSuccess('', compact('list'));
    }

}