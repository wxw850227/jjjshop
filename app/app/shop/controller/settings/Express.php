<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Express as ExpressModel;
use app\shop\model\order\Order;

/**
 * 物流公司
 */
class Express extends Controller
{
    /**
     * 物流公司列表
     */
    public function index()
    {
        $model = new ExpressModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('',compact('list'));
    }

    /**
     * 添加物流公司
     */
    public function add()
    {
        // 新增记录
        $model = new ExpressModel;
        $data = $this->postData();
        if ($model->add($data)) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 物流公司详情
     */
    public function detail($express_id)
    {
        // 物流公司详情
        $detail = ExpressModel::detail($express_id);
        return $this->renderSuccess('',compact('detail'));

    }

    /**
     * 编辑物流公司
     */
    public function edit()
    {
        $model = new ExpressModel;
        $data = $this->postData();
        $filter['express_id'] = $data['express_id'];
        unset($data['express_id']);
        // 更新记录
        if ($model->edit($data, $filter)) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除记录
     */
    public function delete($express_id)
    {
        // 判断当前物流公司是否已被订单使用
        $Order = new Order;
        if ($orderCount = $Order->where(['express_id' => $express_id])->count()) {
            $msg = '当前物流公司已被' . $orderCount . '个订单使用，不允许删除';
            return $this->renderError($msg);
        }

        $model1 = new ExpressModel;
        if ($model1->del($express_id)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }

    /**
     *定义物流编码列表
     */
    public function companyList()
    {
        $data = array(
            array(
                'company_name' => '顺丰',
                'company_code' => 'shunfeng',
            ),
            array(
                'company_name' => '中通',
                'company_code' => 'zhongtong',
            ),
            array(
                'company_name' => '澳通华人物流',
                'company_code' => 'cllexpress',
            ),
            array(
                'company_name' => '斑马物流',
                'company_code' => 'banma',
            ),
            array(
                'company_name' => '信丰物流',
                'company_code' => 'xinfengwuliu',
            ),
            array(
                'company_name' => '苏宁订单',
                'company_code' => 'suningorder',
            ),
            array(
                'company_name' => '宜送物流',
                'company_code' => 'yiex',
            ),
            array(
                'company_name' => 'AOL澳通速递',
                'company_code' => 'aolau',
            ),
            array(
                'company_name' => 'TRAKPAK',
                'company_code' => 'trakpak',
            ),
            array(
                'company_name' => 'GTS快递',
                'company_code' => 'gts',
            ),
            array(
                'company_name' => '通达兴物流',
                'company_code' => 'tongdaxing',
            ),
            array(
                'company_name' => '中国香港(HongKong Post)英文',
                'company_code' => 'hkposten',
            ),
            array(
                'company_name' => '俄罗斯邮政(Russian Post)',
                'company_code' => 'pochta',
            ),
            array(
                'company_name' => '云达通',
                'company_code' => 'ydglobe',
            ),
            array(
                'company_name' => 'EU-EXPRESS',
                'company_code' => 'euexpress',
            ),
            array(
                'company_name' => '广州海关',
                'company_code' => 'gzcustoms',
            ),
            array(
                'company_name' => '杭州海关',
                'company_code' => 'hzcustoms',
            ),
            array(
                'company_name' => '南京海关',
                'company_code' => 'njcustoms',
            ),
            array(
                'company_name' => '北京海关',
                'company_code' => 'bjcustoms',
            ),
            array(
                'company_name' => '美西快递',
                'company_code' => 'meixi',
            ),
            array(
                'company_name' => '顺丰-美国件',
                'company_code' => 'shunfengen',
            ),
            array(
                'company_name' => '顺丰速运',
                'company_code' => 'shunfeng',
            ),
            array(
                'company_name' => '顺丰-繁体',
                'company_code' => 'shunfenghk',
            ),
            array(
                'company_name' => '泰国中通ZTO',
                'company_code' => 'thaizto',
            ),
            array(
                'company_name' => '中通（带电话）',
                'company_code' => 'zhongtongphone',
            ),
            array(
                'company_name' => '中通国际',
                'company_code' => 'zhongtongguoji',
            ),
            array(
                'company_name' => '中通快运',
                'company_code' => 'zhongtongkuaiyun',
            ),
            array(
                'company_name' => '中通快递',
                'company_code' => 'zhongtong',
            ),
            array(
                'company_name' => '韵丰物流',
                'company_code' => 'yunfeng56',
            ),
            array(
                'company_name' => '速通物流',
                'company_code' => 'sutongwuliu',
            ),
            array(
                'company_name' => '联邦快递',
                'company_code' => 'lianbangkuaidi',
            ),
            array(
                'company_name' => '深圳德创物流',
                'company_code' => 'dechuangwuliu',
            ),
            array(
                'company_name' => 'EMS-英文',
                'company_code' => 'emsen',
            ),
            array(
                'company_name' => '小红书',
                'company_code' => 'xiaohongshuorder',
            ),
        );
        return $this->renderSuccess('', compact('data'));
    }
}
