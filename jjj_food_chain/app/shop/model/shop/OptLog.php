<?php

namespace app\shop\model\shop;

use app\common\model\shop\OptLog as OptLogModel;
/**
 * 后台管理员登录日志模型
 */
class OptLog extends OptLogModel
{
    /**
     * 获取列表数据
     */
    public function getList($params)
    {
        $model = $this;
        // 查询条件：订单号
        if (isset($params['username']) && !empty($params['username'])) {
            $model = $model->where('user.user_name|user.real_name', 'like', "%{$params['username']}%");
        }
        // 查询列表数据
        return $model->alias('log')->field(['log.*','user.user_name','user.real_name'])
            ->join('shop_user user', 'user.shop_user_id = log.shop_user_id','left')
            ->order(['log.create_time' => 'desc'])
            ->paginate($params, false, [
                'query' => \request()->request()
            ]);
    }
}