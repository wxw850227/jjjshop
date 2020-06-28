<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\user\User as UserModel;
use app\api\model\order\Order as OrderModel;
use app\api\model\settings\Setting as SettingModel;

/**
 * 个人中心主页
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     */
    public function detail()
    {
        // 当前用户信息
        $user = $this->getUser();
        // 订单总数
        $model = new OrderModel;

        return $this->renderSuccess('', [
            'userInfo' => $user,
            'orderCount' => [
                'payment' => $model->getCount($user, 'payment'),
                'received' => $model->getCount($user, 'received'),
                'comment' => $model->getCount($user, 'comment'),
            ],
        ]);
    }
}