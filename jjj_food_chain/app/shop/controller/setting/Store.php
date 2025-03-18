<?php

namespace app\shop\controller\setting;

use app\common\enum\order\OrderPayTypeEnum;
use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;
use app\common\enum\settings\DeliveryTypeEnum;

/**
 * 商城设置控制器
 */
class Store extends Controller
{
    /**
     * 商城设置
     */
    public function index()
    {
        if ($this->request->isGet()) {
            return $this->fetchData();
        }
        $model = new SettingModel;
        $data = $this->request->param();
        $arr = [
            'name' => $data['name'],
            'is_get_log' => $data['is_get_log'],
            'is_send_wx' => $data['is_send_wx'],
            'login_logo' => $data['login_logo'],
            'login_desc' => $data['login_desc'],
            'user_name' => $data['user_name'],
            'wx_phone' => $data['wx_phone'],
            'avatarUrl' => $data['avatarUrl'],
            'logoUrl' => $data['logoUrl'],
        ];
        if ($model->edit('store', $arr)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取商城配置
     */
    public function fetchData()
    {
        $vars['values'] = SettingModel::getItem('store');
        return $this->renderSuccess('', compact('vars'));
    }

}
