<?php

namespace app\admin\model\supplier;

use app\common\model\supplier\Supplier as SupplierModel;

/**
 * 门店模型
 */
class Supplier extends SupplierModel
{
    /**
     * 添加
     */
    public function add($data, $app_id)
    {
        //添加门店
        $data['real_name'] = $data['user_name'];
        $data['name'] = $data['user_name'];
        $data['app_id'] = $app_id;
        $data['is_main'] = 1;
        $data['is_recycle'] = 0;
        $data['delivery_set'] = ["10", "20"];
        $data['store_set'] = ["30", "40"];
        $data['delivery_time'] = json_encode(["00:00", "23:59"]);
        $data['pick_time'] = json_encode(["00:00", "23:59"]);
        $data['store_time'] = json_encode(["00:00", "23:59"]);
        $this->save($data);
    }
}