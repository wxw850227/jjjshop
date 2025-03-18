<?php

namespace app\api\model\supplier;

use app\common\model\supplier\Supplier as SupplierModel;
use app\common\exception\BaseException;

/**
 * 供应商模型类
 */
class Supplier extends SupplierModel
{
    //获取店铺信息
    public function getDetail($param)
    {
        $detail = $this->where(['shop_supplier_id' => $param['shop_supplier_id']])
            ->field("storebag_type,min_money,is_main,delivery_set,store_set,name,shop_supplier_id,logo,bag_type,longitude,latitude,store_time,pick_time,delivery_time,delivery_distance,6378.137*2*ASIN(SQRT(POW(SIN(({$param['latitude']} * PI()/180-latitude*PI()/180)/2),2) + COS({$param['latitude']}*PI()/180)*COS(latitude*PI()/180)*POW(SIN(({$param['longitude']} * PI()/180-longitude*PI()/180)/2),2)))*1000 AS distance")
            ->find();
        if (!$detail) {
            return [];
        }
        if ($detail['distance'] >= 1000) {
            $distance = bcdiv($detail['distance'], 1000, 2);
            $detail['distance'] = $distance . 'km';
        } else {
            $detail['distance'] = round($detail['distance'], 2) . 'm';
        }
        return $detail;
    }

    //所有店铺列表
    public function supplierList($param)
    {
        $model = $this;
        if (isset($param['name']) && $param['name']) {
            $model = $model->where('name', 'like', '%' . $param['name'] . '%');
        }
        // 查询列表数据
        $list = $model->alias('s')
            ->where('is_recycle', '=', 0)
            ->where('s.is_delete', '=', '0')
            ->field("link_phone,is_main,delivery_time,pick_time,store_time,s.shop_supplier_id,s.name,logo,status,store_type,address,longitude,latitude,6378.137*2*ASIN(SQRT(POW(SIN(({$param['latitude']} * PI()/180-latitude*PI()/180)/2),2) + COS({$param['latitude']}*PI()/180)*COS(latitude*PI()/180)*POW(SIN(({$param['longitude']} * PI()/180-longitude*PI()/180)/2),2)))*1000 AS distance")
            ->order(['distance' => 'asc', 'create_time' => 'desc'])
            ->paginate($param);
        foreach ($list as &$supplier) {
            if ($supplier['distance'] >= 1000) {
                $distance = bcdiv($supplier['distance'], 1000, 2);
                $supplier['distance'] = $distance . 'km';
            } else {
                $supplier['distance'] = round($supplier['distance'], 2) . 'm';
            }

        }
        return $list;
    }

    //可选店铺列表
    public function deliveryList($param, $user)
    {
        $model = $this;
        if (isset($param['name']) && $param['name']) {
            $model = $model->where('name', 'like', '%' . $param['name'] . '%');
        }
        if ($param['type'] == 10) {
            $latitude = $user['address']['latitude'];
            $longitude = $user['address']['longitude'];
        } else {
            $latitude = $param['latitude'];
            $longitude = $param['longitude'];
        }
        // 查询列表数据
        $list = $model->alias('s')
            ->where('is_recycle', '=', 0)
            ->where('s.is_delete', '=', '0')
            ->field("link_phone,delivery_time,pick_time,store_time,s.shop_supplier_id,s.name,logo,status,store_type,address,longitude,latitude,delivery_distance,6378.137*2*ASIN(SQRT(POW(SIN(({$latitude} * PI()/180-latitude*PI()/180)/2),2) + COS({$latitude}*PI()/180)*COS(latitude*PI()/180)*POW(SIN(({$longitude} * PI()/180-longitude*PI()/180)/2),2)))*1000 AS distance")
            ->order(['distance' => 'asc', 'create_time' => 'desc'])
            ->paginate($param);
        foreach ($list as &$supplier) {
            if ($param['type'] == 10 && (round($supplier['distance'] / 1000, 2) > $supplier['delivery_distance'] || $supplier['status'] == 1)) {
                $supplier['isSelect'] = 0;
            } else {
                $supplier['isSelect'] = 1;
            }
            if ($supplier['distance'] >= 1000) {
                $distance = bcdiv($supplier['distance'], 1000, 2);
                $supplier['distance'] = $distance . 'km';
            } else {
                $supplier['distance'] = round($supplier['distance'], 2) . 'm';
            }

        }
        return $list;
    }

    //获取默认门店
    public function getDefault($param)
    {
        $param['latitude'] = isset($param['latitude']) ? $param['latitude'] : 0;
        $param['longitude'] = isset($param['longitude']) ? $param['longitude'] : 0;
        return $this->where('status', '=', 0)
            ->where('is_recycle', '=', 0)
            ->where('is_delete', '=', 0)
            ->field("*,6378.137*2*ASIN(SQRT(POW(SIN(({$param['latitude']} * PI()/180-latitude*PI()/180)/2),2) + COS({$param['latitude']}*PI()/180)*COS(latitude*PI()/180)*POW(SIN(({$param['longitude']} * PI()/180-longitude*PI()/180)/2),2)))*1000 AS distance")
            ->order('distance asc,create_time dsc')
            ->find();
    }

    //判断门店是否营业
    public function supplierState($shop_supplier_id, $type = 0)
    {
        $supplier = $this->where('shop_supplier_id', '=', $shop_supplier_id)
            ->where('is_delete', '=', 0)
            ->where('is_recycle', '=', 0)
            ->find();
        //是否开启营业
        if (!$supplier || $supplier['status'] != 0) {
            throw new BaseException(['msg' => '店铺休息中']);
        }
        //外卖营业时间
        $delivery_time = $supplier['delivery_time'];
        if (!$delivery_time) {
            return true;
        }
        $delivery_time[0] = strtotime($delivery_time[0]);
        $delivery_time[1] = strtotime($delivery_time[1]);
        //自取营业时间
        $pick_time = $supplier['pick_time'];
        $pick_time[0] = strtotime($pick_time[0]);
        $pick_time[1] = strtotime($pick_time[1]);
        //店内营业时间
        $store_time = $supplier['store_time'];
        $store_time[0] = strtotime($store_time[0]);
        $store_time[1] = strtotime($store_time[1]);
        switch ($type) {
            case '0'://默认全部
                if (!(time() >= $delivery_time[0] && time() <= $delivery_time[1] && $delivery_time) && !(time() >= $pick_time[0] && time() <= $pick_time[1] && $pick_time)) {
                    throw new BaseException(['msg' => '不在营业时间内']);
                    return false;
                }
                break;
            case '10'://外卖
                if ($delivery_time && !(time() >= $delivery_time[0] && time() <= $delivery_time[1])) {
                    throw new BaseException(['msg' => '不在营业时间内']);
                    return false;
                }
                break;
            case '20'://自提
                if ($pick_time && !(time() >= $pick_time[0] && time() <= $pick_time[1])) {
                    throw new BaseException(['msg' => '不在营业时间内']);
                    return false;
                }
                break;
            case '30'://店内堂食
                if ($store_time && !(time() >= $store_time[0] && time() <= $store_time[1])) {
                    throw new BaseException(['msg' => '不在营业时间内']);
                    return false;
                }
                break;
        }
        return true;
    }

    /**
     * 获取列表数据
     */
    public static function getStoreList($longitude = '', $latitude = '')
    {
        $model = new static();
        // 查询列表数据
        $data = $model->where('is_delete', '=', '0')
            ->where('is_recycle', '=', '0')
            ->where('status', '=', '0')
            ->order(['create_time' => 'desc'])
            ->select();
        // 根据距离排序
        if (!empty($longitude) && !empty($latitude)) {
            return self::sortByDistance($data, $longitude, $latitude);
        }
        return $data;
    }

    /**
     * 根据距离排序
     */
    private function sortByDistance($data, $longitude, $latitude)
    {
        // 根据距离排序
        $list = $data->isEmpty() ? [] : $data->toArray();
        $sortArr = [];
        foreach ($list as &$store) {
            // 计算距离
            $distance = self::getDistance($longitude, $latitude, $store['longitude'], $store['latitude']);
            // 排序列
            $sortArr[] = $distance;
            $store['distance'] = $distance;
            if ($distance >= 1000) {
                $distance = bcdiv($distance, 1000, 2);
                $store['distance_unit'] = $distance . 'km';
            } else
                $store['distance_unit'] = $distance . 'm';
        }
        // 根据距离排序
        array_multisort($sortArr, SORT_ASC, $list);
        return $list;
    }

    //计算外卖距离
    public function calculateDistance($supplier, $user = false)
    {
        if ($user && $user['address_id']) {
            $distance = self::getDistance($supplier['longitude'], $supplier['latitude'], $user['address']['longitude'], $user['address']['latitude']);
            if ($distance >= 1000) {
                $distance = bcdiv($distance, 1000, 2);
                $distance = $distance . 'km';
            } else {
                $distance = round($distance, 2) . 'm';
            }
        } else {
            $distance = '0m';
        }
        return $distance;
    }

    /**
     * 获取两个坐标点的距离
     */
    private static function getDistance($ulon, $ulat, $slon, $slat)
    {
        // 地球半径
        $R = 6378137;
        // 将角度转为狐度
        $radLat1 = deg2rad($ulat);
        $radLat2 = deg2rad($slat);
        $radLng1 = deg2rad($ulon);
        $radLng2 = deg2rad($slon);
        // 结果
        $s = acos(cos($radLat1) * cos($radLat2) * cos($radLng1 - $radLng2) + sin($radLat1) * sin($radLat2)) * $R;
        // 精度
        $s = round($s * 10000) / 10000;
        return round($s);
    }

    /**
     * 判断会员是否绑定门店
     */
    public function isBind($user)
    {
        return $this->where('user_id', '=', $user['user_id'])
            ->where('is_delete', '=', 0)
            ->count();
    }

}
