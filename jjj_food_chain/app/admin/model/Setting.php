<?php

namespace app\admin\model;

use app\common\model\settings\Setting as SettingModel;

class Setting extends SettingModel
{
    /**
     * 新增默认配置
     */
    public function insertDefault($app_id, $store_name)
    {
        // 添加商城默认设置记录
        $data = [];
        foreach ($this->defaultData($store_name) as $key => $item) {
            $data[] = array_merge($item, ['app_id' => $app_id]);
        }
        return $this->saveAll($data);
    }
}