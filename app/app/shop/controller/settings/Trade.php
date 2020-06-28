<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;


class Trade extends Controller
{

    public function index()
    {
        $model = new SettingModel;
        $data = $this->request->param();

        $values = [
            'order' => [
                'close_days' => $data['close_days'],
                'receive_days' => $data['receive_days'],
                'refund_days' => $data['refund_days']
            ],
            'freight_rule' => $data['freight_rule'],
        ];
        $key = 'trade';
        if ($model->edit($key, $values)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 商城设置进来请求的接口
     */
    public function fetchData()
    {
        $key = 'trade';
        $vars['values'] = SettingModel::getItem($key);
        return $this->renderSuccess('', compact('vars'));
    }

}
