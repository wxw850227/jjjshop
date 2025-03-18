<?php

namespace app\api\model\user;

use app\common\model\user\UserAddress as UserAddressModel;
use app\api\model\supplier\Supplier as SupplierModel;

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
     * 获取列表
     */
    public function list($user_id, $shop_supplier_id)
    {
        $supplier = SupplierModel::detail($shop_supplier_id);
        $list = $this->where('user_id', '=', $user_id)
            ->field("*,6378.137*2*ASIN(SQRT(POW(SIN(({$supplier['latitude']} * PI()/180-latitude*PI()/180)/2),2) + COS({$supplier['latitude']}*PI()/180)*COS(latitude*PI()/180)*POW(SIN(({$supplier['longitude']} * PI()/180-longitude*PI()/180)/2),2)))*1000 AS distance")
            ->select();
        foreach ($list as &$item) {
            $item['distance'] = round($item['distance'] / 1000, 2);
            $item['status'] = $item['distance'] > $supplier['delivery_distance'] ? 0 : 1;
        }
        return $list;
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
                'detail' => $data['detail'],
                'address' => $data['address'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
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
        return $this->save([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'detail' => $data['detail'],
                'address' => $data['address'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
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
     * @return bool
     * @throws \Exception
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
        return static::where($where)->find();
    }

}