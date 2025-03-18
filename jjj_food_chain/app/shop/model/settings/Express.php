<?php

namespace app\shop\model\settings;

use app\common\model\settings\Express as ExpressModel;
use app\common\model\order\Order as OrderModel;
/**
 * 物流公司模型
 */
class Express extends ExpressModel
{

    /**
     * 添加新记录
     */
    public function add($data)
    {
        $data['app_id'] = self::$app_id;
        return $this->save($data);
    }

    /**
     * 编辑记录
     */
    public function edit($data)
    {
        return $this->save($data);
    }

    /**
     * 删除记录
     */
    public function remove()
    {
        // 判断当前物流公司是否已被订单使用
        $model = new OrderModel;
        if ($orderCount = $model->where(['express_id' => $this['express_id']])->count()) {
            $this->error = '当前物流公司已被' . $orderCount . '个订单使用，不允许删除';
            return false;
        }
        return $this->delete();
    }
}