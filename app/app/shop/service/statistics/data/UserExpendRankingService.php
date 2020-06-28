<?php

namespace app\shop\service\statistics\data;

use app\shop\model\user\User as UserModel;

/**
 * 数据统计-用户消费榜
 */
class UserExpendRankingService
{
    /**
     * 用户消费榜
     */
    public function getUserExpendRanking()
    {
        return (new UserModel)->field(['user_id', 'nickName', 'expend_money'])
            ->where('is_delete', '=', 0)
            ->order(['expend_money' => 'DESC'])
            ->limit(10)
            ->select();
    }

}