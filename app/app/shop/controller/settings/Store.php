<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;
use app\common\enum\settings\DeliveryTypeEnum;

/**
 * 商城设置
 */
class Store extends Controller
{

    public function index()
    {
        $model = new SettingModel;
        $data = $this->request->param();
        $values = [
            'name' => $data['name'],
            'delivery_type' => [10],
            'kuaidi100' => ['customer' => $data['customer'], 'key' => $data['key']],
        ];
        $key = 'store';
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
        $key = 'store';
        $vars['values'] = SettingModel::getItem($key);
        $all_type = DeliveryTypeEnum::data();
        return $this->renderSuccess('', compact('vars', 'all_type'));
    }

}
