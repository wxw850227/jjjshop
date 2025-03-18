<?php

namespace app\shop\model\settings;

use think\facade\Cache;
use app\common\model\settings\Setting as SettingModel;
use app\common\enum\settings\SettingEnum;

class Setting extends SettingModel
{
    /**
     * 更新系统设置
     */
    public function edit($key, $values,$shop_supplier_id=0)
    {
        $model = self::detail($key,$shop_supplier_id) ?: $this;
        // 删除系统设置缓存
        Cache::delete('setting_' . self::$app_id. '_'.$shop_supplier_id);
        return $model->save([
                'key' => $key,
                'describe' => SettingEnum::data()[$key]['describe'],
                'values' => $values,
                'app_id' => self::$app_id,
                'shop_supplier_id' => $shop_supplier_id
            ]) !== false;
    }

    /**
     * 数据验证
     */
    private function validValues($key, $values)
    {
        $callback = [
            'store' => function ($values) {
                return $this->validStore($values);
            },
            'printer' => function ($values) {
                return $this->validPrinter($values);
            },
        ];
        // 验证商城设置
        return isset($callback[$key]) ? $callback[$key]($values) : true;
    }

    /**
     * 验证商城设置
     */
    private function validStore($values)
    {
        if (!isset($values['delivery_type']) || empty($values['delivery_type'])) {
            $this->error = '配送方式至少选择一个';
            return false;
        }
        return true;
    }

    /**
     * 验证小票打印机设置
     */
    private function validPrinter($values)
    {
        if ($values['is_open'] == false) {
            return true;
        }
        if (!$values['printer_id']) {
            $this->error = '请选择订单打印机';
            return false;
        }
        if (empty($values['order_status'])) {
            $this->error = '请选择订单打印方式';
            return false;
        }
        return true;
    }

}
