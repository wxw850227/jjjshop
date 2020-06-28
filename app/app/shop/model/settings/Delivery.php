<?php

namespace app\shop\model\settings;

use app\shop\model\product\Product as ProductModel;
use app\common\model\settings\Delivery as DeliveryModel;

/**
 * 配送模板模型
 */
class Delivery extends DeliveryModel
{
    /**
     * 添加新记录
     */
    public function add($data)
    {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }

        $delivery = [
            'name' => $data['name'],
            'method' => $data['method'],
            'sort' => $data['sort'],
            'app_id' => self::$app_id
        ];

        if ($this->save($delivery)) {
            return $this->createDeliveryRule($data['rule'], 'add');
        }
        return false;
    }

    /**
     * 编辑记录
     */
    public function edit($data)
    {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }

        $delivery = [
            'name' => $data['name'],
            'method' => $data['method'],
            'sort' => $data['sort'],
        ];

        if ($this->save($delivery)) {
            return $this->createDeliveryRule($data['rule'], 'edit');
        }
        return false;
    }

    /**
     * 添加模板区域及运费
     * @param $data
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function createDeliveryRule($data, $scene = 'add')
    {
        $save = [];
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $save[] = [
                'region' => join(',', array_values($data[$i]['citys'])),
                'first' => $data[$i]['first'],
                'first_fee' => $data[$i]['first_fee'],
                'additional' => $data[$i]['additional'],
                'additional_fee' => $data[$i]['additional_fee'],
                'app_id' => self::$app_id
            ];
        }
        if($scene == 'edit'){
            $this->rule()->delete();
        }
        return $this->rule()->saveAll($save);
    }

    /**
     * 表单验证
     */
    private function onValidate($data)
    {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }
        foreach ($data['rule']['first'] as $value) {
            if ((int)$value <= 0 || (int)$value != $value) {
                $this->error = '首件或首重必须是正整数';
                return false;
            }
        }
        foreach ($data['rule']['additional'] as $value) {
            if ((int)$value <= 0 || (int)$value != $value) {
                $this->error = '续件或续重必须是正整数';
                return false;
            }
        }
        return true;
    }

    /**
     * 获取配送区域及运费设置项
     */
    public function getFormList()
    {
        // 所有地区
        $regions = Region::getCacheAll();
        $list = [];
        foreach ($this['rule'] as $rule) {
            $citys = explode(',', $rule['region']);
            $province = [];
            foreach ($citys as $cityId) {
                if (!isset($regions[$cityId])) continue;
                !in_array($regions[$cityId]['pid'], $province) && $province[] = $regions[$cityId]['pid'];
            }
            $list[] = [
                'first' => $rule['first'],
                'first_fee' => $rule['first_fee'],
                'additional' => $rule['additional'],
                'additional_fee' => $rule['additional_fee'],
                'province' => $province,
                'citys' => $citys,
            ];
        }
        return $list;
    }

    /**
     * 删除记录
     */
    public function del($wehre)
    {
        // 删除运费模板
        $res = self::destroy($wehre);
        return DeliveryRule::destroy($wehre);
    }

    /**
     * 验证运费模板是否被商品使用
     */
    public function checkIsUseProduct($deliveryId)
    {
        // 判断是否存在商品
        $productCount = (new ProductModel)->where('delivery_id', '=', $deliveryId)
            ->where('is_delete', '=', 0)
            ->count();
        if ($productCount > 0) {
            return true;
        }
        return false;
    }

}
