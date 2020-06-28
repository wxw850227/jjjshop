<?php

namespace app\common\model\settings;

use think\facade\Request;

use app\common\model\BaseModel;

/**
 * 配送模板模型
 */
class Delivery extends BaseModel
{
    //表名
    protected $name = 'delivery';
    //主键字段名
    protected $pk = 'delivery_id';

    /**
     * 关联配送模板区域及运费
     */
    public function rule()
    {
        return $this->hasMany('DeliveryRule');
    }

    /**
     * 计费方式
     */
    public function getMethodAttr($value)
    {
        $method = [10 => '按件数', 20 => '按重量'];
        return ['text' => $method[$value], 'value' => $value];
    }

    /**
     * 获取全部
     */
    public static function getAll()
    {
        $model = new static;
        return $model->order(['sort' => 'asc'])->select();
    }

    /**
     * 获取列表
     */
    public function getList($params)
    {
        return $this->with(['rule'])
            ->order(['sort' => 'asc'])
            ->paginate($params, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 运费模板详情
     */
    public static function detail($delivery_id)
    {
        return self::find($delivery_id, ['rule']);
    }

}
