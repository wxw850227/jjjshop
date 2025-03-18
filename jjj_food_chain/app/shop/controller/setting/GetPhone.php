<?php

namespace app\shop\controller\setting;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;
use app\common\enum\settings\GetPhoneTypeEnum;

/**
 * 商城设置控制器
 */
class GetPhone extends Controller
{
    /**
     * 商城设置
     */
    public function index()
    {
        if($this->request->isGet()){
            return $this->fetchData();
        }
        $model = new SettingModel;
        $data = $this->request->param();
        $arr = [
            'area_type' => $data['checkedCities'],
            'send_day' => $data['send_day'],
        ];
        if ($model->edit('getPhone', $arr)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取商城配置
     */
    public function fetchData()
    {
        $vars['values'] = SettingModel::getItem('getPhone');
        $all_type = GetPhoneTypeEnum::data();
        return $this->renderSuccess('', compact('vars', 'all_type'));
    }

}
