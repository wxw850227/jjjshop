<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;
use app\common\library\sms\Driver as SmsDriver;

/**
 * 短信设置
 */
class Sms extends Controller
{
    /**
     * 短信设置首页
     */
    public function index()
    {
        $model = new SettingModel;
        $data = $this->request->param();
        $arr = [
            'default' => 'aliyun',
            'engine' => [
                'aliyun' => [
                    'AccessKeyId' => $data['AccessKeyId'],
                    'AccessKeySecret' => $data['AccessKeySecret'],
                    'sign' => $data['sign'],
                    'accept_phone' => $data['accept_phone'],
                ]
            ]
        ];
        $key = 'sms';
        if ($model->edit($key, $arr)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 商城设置进来请求的接口
     */
    public function fetchData()
    {
        $key = 'sms';
        $vars['values'] = SettingModel::getItem($key);
        return $this->renderSuccess('', compact('vars'));
    }

}
