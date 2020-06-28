<?php

namespace app\common\model\settings;

use think\facade\Request;
use app\common\library\express\Kuaidi100;
use app\common\model\BaseModel;

/**
 * 物流公司模型
 */
class Express extends BaseModel
{
    //表名
    protected $name = 'express';
    //主键字段名
    protected $pk = 'express_id';

    /**
     * 获取全部
     */
    public function getAll()
    {
        return $this->order(['sort' => 'asc'])->select();
    }

    /**
     * 获取列表
     */
    public function getList($params)
    {
        return $this->order(['sort' => 'asc'])
            ->paginate($params, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 物流公司详情
     */
    public static function detail($express_id)
    {
        return self::find($express_id);
    }

    /**
     * 获取物流动态信息
     */
    public function dynamic($express_name, $express_code, $express_no)
    {
        $data = [
            'express_name' => $express_name,
            'express_no' => $express_no
        ];
        // 实例化快递100类
        $config = Setting::getItem('store');
        $Kuaidi100 = new Kuaidi100($config['kuaidi100']);
        // 请求查询接口
        $data['list'] = $Kuaidi100->query($express_code, $express_no);
        if ($data['list'] === false) {
            $this->error = $Kuaidi100->getError();
            return false;
        }
        return $data;
    }
}