<?php

namespace app\admin\model\settings;

use app\common\enum\settings\SettingEnum;
use app\common\model\settings\Setting as SettingModel;

class Setting extends SettingModel
{

    /**
     * 新增
     */
    public function add($data)
    {
        $service = $this->where(['key' => SettingEnum::SYS_CONFIG])->find();
        if (!$service) {
            $add['key'] = SettingEnum::SYS_CONFIG;
            $add['describe'] = '系统设置';
            $add['values'] = $data;
            return $this->save($add);
        } else {
            return $this->where(['key' => SettingEnum::SYS_CONFIG])->save(['values' => json_encode($data)]);
        }
    }

}
