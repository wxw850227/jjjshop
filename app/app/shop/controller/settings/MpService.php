<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 公众号消息设置控制器
 */
class MpService extends Controller
{
    /**
     * 公众号消息设置
     */
    public function index()
    {
        if($this->request->isGet()){
            return $this->fetchData();
        }
        $model = new SettingModel;
        $data = $this->request->param();
        $arr = [
            'qq' => $data['qq'],
            'wechat' => $data['wechat'],
            'mp_image' => $data['mp_image'],
        ];
        if ($model->edit('mp_service', $arr)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 获取商城配置
     */
    public function fetchData()
    {
        $vars['values'] = SettingModel::getItem('mp_service');
        return $this->renderSuccess('', compact('vars'));
    }

}
