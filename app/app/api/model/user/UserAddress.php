<?php

namespace app\api\model\user;

use app\common\model\settings\Region;
use app\common\model\user\UserAddress as UserAddressModel;

/**
 * 用户收货地址模型
 */
class UserAddress extends UserAddressModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取列表
     */
    public function getList($user_id)
    {
        return $this->where('user_id', '=', $user_id)->select();
    }

    /**
     * 新增收货地址
     */
    public function add($user, $data)
    {
        // 添加收货地址
        $this->startTrans();
        try {
            $address_id = $this->insertGetId([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'region_id' => $data['region_id'],
                'detail' => $data['detail'],
                'district' => ($data['region_id'] === 0 && !empty($region[2])) ? $region[2] : '',
                'user_id' => $user['user_id'],
                'app_id' => self::$app_id
            ]);
            // 设为默认收货地址
            !$user['address_id'] && $user->save(['address_id' => $address_id]);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 编辑收货地址
     */
    public function edit($data)
    {
        // 添加收货地址
        $region = explode(',', $data['region']);
        $province_id = Region::getIdByName($region[0], 1);
        $city_id = Region::getIdByName($region[1], 2, $province_id);
        $region_id = Region::getIdByName($region[2], 3, $city_id);
        return $this->save([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'province_id' => $province_id,
                'city_id' => $city_id,
                'region_id' => $region_id,
                'detail' => $data['detail'],
                'district' => ($region_id === 0 && !empty($region[2])) ? $region[2] : '',
            ]) !== false;
    }

    /**
     * 设为默认收货地址
     */
    public function setDefault($user)
    {
        // 设为默认地址
        return $user->save(['address_id' => $this['address_id']]);
    }

    /**
     * 删除收货地址
     */
    public function remove($user)
    {
        // 查询当前是否为默认地址
        $user['address_id'] == $this['address_id'] && $user->save(['address_id' => 0]);
        return $this->delete();
    }

    /**
     * 收货地址详情
     */
    public static function detail($user_id, $address_id)
    {
        $where = ['user_id' => $user_id, 'address_id' => $address_id];
        return self::where($where)->find();
    }

}