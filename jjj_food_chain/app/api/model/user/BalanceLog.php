<?php

namespace app\api\model\user;

use app\common\model\user\BalanceLog as BalanceLogModel;

/**
 * 用户余额变动明细模型
 */
class BalanceLog extends BalanceLogModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
    ];

    /**
     * 获取账单明细列表
     */
    public function getTop10($userId)
    {
        // 获取列表数据
        return $this->where('user_id', '=', $userId)
            ->order(['create_time' => 'desc'])
            ->limit(10)
            ->select();
    }

    /**
     * 获取账单明细列表
     */
    public function getList($userId, $type)
    {
        $model = $this;
        if($type == 'rechange'){
            $model = $model->where('scene', '=', 10);
        }
        // 获取列表数据
        return $model->where('user_id', '=', $userId)
            ->order(['create_time' => 'desc'])
            ->paginate(30, false, [
                'query' => request()->request()
            ]);
    }

}