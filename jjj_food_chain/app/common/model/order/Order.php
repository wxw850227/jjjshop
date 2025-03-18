<?php

namespace app\common\model\order;

use app\common\enum\order\OrderSourceEnum;
use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\wx\WxOrder;
use app\common\model\BaseModel;
use app\common\enum\settings\DeliveryTypeEnum;
use app\common\enum\order\OrderPayStatusEnum;
use app\common\enum\order\OrderTypeEnum;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\library\helper;
use app\common\model\settings\Setting as SettingModel;
use app\common\service\order\OrderService;
use app\common\service\order\OrderCompleteService;

/**
 * 订单模型模型
 */
class Order extends BaseModel
{
    protected $pk = 'order_id';
    protected $name = 'order';

    /**
     * 追加字段
     * @var string[]
     */
    protected $append = [
        'state_text',
        'order_source_text',
        'order_type_text',
        'deliver_text',
    ];

    /**
     * 订单商品列表
     */
    public function product()
    {
        return $this->hasMany('app\\common\\model\\order\\OrderProduct', 'order_id', 'order_id')->hidden(['content']);
    }


    /**
     * 关联订单收货地址表
     */
    public function address()
    {
        return $this->hasOne('app\\common\\model\\order\\OrderAddress');
    }

    /**
     * 关联自提订单联系方式
     */
    public function extract()
    {
        return $this->hasOne('app\\common\\model\\order\\OrderExtract');
    }

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo('app\\common\\model\\user\\User', 'user_id', 'user_id');
    }

    /**
     * 关联供应商表
     */
    public function supplier()
    {
        return $this->belongsTo('app\\common\\model\\supplier\\Supplier', 'shop_supplier_id', 'shop_supplier_id');
    }

    /**
     * 关联配送信息
     */
    public function deliver()
    {
        return $this->belongsTo('app\\common\\model\\order\\OrderDeliver', 'order_id', 'order_id')->order('deliver_id desc');
    }

    /**
     * 订单状态文字描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getStateTextAttr($value, $data)
    {
        // 订单状态
        if (in_array($data['order_status'], [20, 30])) {
            $orderStatus = [20 => '已取消', 30 => '已完成'];
            return $orderStatus[$data['order_status']];
        }
        // 付款状态
        if ($data['pay_status'] == 10) {
            return '待付款';
        }
        // 发货状态
        if ($data['order_status'] == 10) {
            if ($data['delivery_type'] == 10 && $data['delivery_status'] == 10) {
                return '待配送';
            }
            if ($data['delivery_type'] == 10 && $data['delivery_status'] == 20) {
                return '配送中';
            }
            return '进行中';
        }

        return $value;
    }

    /**
     * 订单状态文字描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getDeliverTextAttr($value, $data)
    {
        // 订单状态待接单＝1,待取货＝2,配送中＝3,已完成＝4,已取消＝5, 指派单=8
        if (in_array($data['order_status'], [20, 30])) {
            $orderStatus = [20 => '已取消', 30 => '已完成'];
            return $orderStatus[$data['order_status']];
        }
        // 发货状态
        if ($data['delivery_status'] == 10) {
            return '待配送';
        }
        // 发货状态
        if ($data['delivery_status'] == 20) {
            $deliverStatus = [1 => '待接单', 2 => '待取货', 3 => '配送中', 4 => '已完成'];
            return $deliverStatus[$data['deliver_status']];
        }
        return $value;
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayTypeAttr($value)
    {
        return ['text' => OrderPayTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 订单类型
     * @param $value
     * @return array
     */
    public function getOrderTypeTextAttr($value, $data)
    {
        return $data['order_type'] == 0 ? '外卖订单' : '店内订单';
    }

    /**
     * 订单来源
     * @param $value
     * @return array
     */
    public function getOrderSourceTextAttr($value, $data)
    {
        return OrderSourceEnum::data()[$data['order_source']]['name'];
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        return ['text' => OrderPayStatusEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 发货状态
     * @param $value
     * @return array
     */
    public function getDeliveryStatusAttr($value)
    {
        $status = [10 => '待配送', 20 => '已配送'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getReceiptStatusAttr($value)
    {
        $status = [10 => '待收货', 20 => '已收货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getOrderStatusAttr($value)
    {
        $status = [10 => '进行中', 20 => '已取消', 21 => '待取消', 30 => '已完成'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 配送方式
     * @param $value
     * @return array
     */
    public function getDeliveryTypeAttr($value)
    {
        return ['text' => DeliveryTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 发送第三方配送
     * @param $value
     * @return array
     */
    public function addOrder($deliver)
    {
        $this->sendLocal($this);
    }

    //商家配送
    public function sendLocal($data)
    {
        $data->save(['deliver_status' => 3, 'deliver_source' => 10, 'delivery_status' => 20]);
        $add = [
            'deliver_source' => 10,
            'order_id' => $data['order_id'],
            'order_no' => $data['order_no'],
            'distance' => $data->getDistance($data['supplier']['longitude'], $data['supplier']['latitude'], $data['address']['longitude'], $data['address']['latitude']),
            'price' => 0,
            'shop_supplier_id' => $data['shop_supplier_id'],
            'deliver_status' => 3,
            'phone' => $data['supplier']['link_phone'],
            'app_id' => $data['app_id']
        ];
        (new OrderDeliver())->save($add);
    }

    public static function getDistance($ulon, $ulat, $slon, $slat)
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
     * 订单详情
     * @param $where
     * @param string[] $with
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($where, $with = ['user', 'address', 'product' => ['image'], 'extract', 'supplier'])
    {
        is_array($where) ? $filter = $where : $filter['order_id'] = (int)$where;
        return self::with($with)->where($filter)->find();
    }

    /**
     * 订单详情
     * @param $where
     * @param string[] $with
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detailByNo($order_no, $with = ['user', 'address', 'product' => ['image', 'refund'], 'extract', 'express', 'extractStore.logo', 'extractClerk', 'supplier'])
    {
        return self::with($with)->where('order_no', '=', $order_no)->find();
    }

    /**
     * 批量获取订单列表
     * @param $orderIds
     * @param array $with
     * @return array
     */
    public function getListByIds($orderIds, $with = [])
    {
        $data = $this->getListByInArray('order_id', $orderIds, $with);
        return helper::arrayColumn2Key($data, 'order_id');
    }

    /**
     * 批量更新订单
     * @param $orderIds
     * @param $data
     * @return bool
     */
    public function onBatchUpdate($orderIds, $data)
    {
        return $this->where('order_id', 'in', $orderIds)->save($data);
    }

    /**
     * 批量更新订单状态
     * @param $orderIds
     * @param $data
     * @return bool
     */
    public function onBatchUpdateStatus($orderIds, $data)
    {
        return $this->where('order_id', 'in', $orderIds)->where('delivery_status', '=', 10)->save($data);
    }

    /**
     * 批量获取订单列表
     * @param $field
     * @param $data
     * @param array $with
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getListByInArray($field, $data, $with = [])
    {
        return $this->with($with)
            ->where($field, 'in', $data)
            ->where('is_delete', '=', 0)
            ->select();
    }

    /**
     * 生成订单号
     * @return string
     */
    public function orderNo()
    {
        return OrderService::createOrderNo();
    }

    /**
     * 生成交易号
     * @return string
     */
    public function tradeNo()
    {
        return OrderService::createTradeNo();
    }

    /**
     * 确认核销（自提订单）
     * @param $extractClerkId
     * @return bool|mixed
     */
    public function verificationOrder()
    {
        if ($this['pay_status']['value'] != 20 || in_array($this['order_status']['value'], [20, 30])) {
            $this->error = '该订单不满足核销条件';
            return false;
        }
        return $this->transaction(function () {
            $deliver = (new OrderDeliver())::detail(['order_id' => $this['order_id'], 'status' => 10]);
            if ($deliver) {
                $deliver->updateDeliver();
            }
            // 更新订单状态：已发货、已收货
            $status = $this->save([
                'delivery_status' => 20,
                'delivery_time' => time(),
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 执行订单完成后的操作
            $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
            $OrderCompleteService->complete([$this], static::$app_id);
            return $status;
        });
    }


    /**
     * 获取已付款订单总数 (可指定某天)
     */
    public function getOrderData($startDate, $endDate, $type, $shop_supplier_id, $order_type = -1)
    {
        $model = $this;

        !is_null($startDate) && $model = $model->where('pay_time', '>=', strtotime($startDate));

        if (is_null($endDate)) {
            !is_null($startDate) && $model = $model->where('pay_time', '<', strtotime($startDate) + 86400);
        } else {
            $model = $model->where('pay_time', '<', strtotime($endDate) + 86400);
        }

        if ($shop_supplier_id > 0) {
            $model = $model->where('shop_supplier_id', '=', $shop_supplier_id);
        }
        if ($order_type >= 0) {
            $model = $model->where('order_type', '=', $order_type);
        }

        $model = $model->where('is_delete', '=', 0)
            ->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20);
        if ($type == 'order_total') {
            // 订单数量
            return $model->count();
        } else if ($type == 'order_total_price') {
            // 订单总金额
            return $model->sum('pay_price');
        } else if ($type == 'order_user_total') {
            // 支付用户数
            return count($model->distinct(true)->column('user_id'));
        } else if ($type == 'order_refund_money') {
            // 退款金额
            return $model->sum('refund_money');
        } else if ($type == 'order_refund_total') {
            // 退款订单数
            return $model->where('refund_money', '>', 0)->count();
        } else if ($type == 'income_price') {
            // 预计收入
            return $model->sum('pay_price') - $model->sum('refund_money');
        }
        return 0;
    }

    /**
     * 获取各类型总销售额
     */
    public function getOrderTotalMoney($order_type, $shop_supplier_id, $data = [])
    {
        $model = $this;
        if (isset($data['type']) && $data['type']) {
            switch ($data['type']) {
                case '1'://今天
                    $model = $model->where('create_time', '>=', strtotime(date('Y-m-d')));
                    break;
                case '2'://近7天
                    $model = $model->where('create_time', '>=', strtotime(-6 . ' days', strtotime(date('Y-m-d'))));
                    break;
                case '3'://近15天
                    $model = $model->where('create_time', '>=', strtotime(-14 . ' days', strtotime(date('Y-m-d'))));
                    break;
                case '4'://自定义
                    $start = strtotime($data['time'][0]);
                    $end = strtotime($data['time'][1]) + 86399;
                    $model = $model->where('create_time', 'between', "$start,$end");
                    break;
            }
        }
        if ($shop_supplier_id) {
            $model = $model->where('shop_supplier_id', '=', $shop_supplier_id);
        }
        $model = $model->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->where('order_type', '=', $order_type)
            ->where('is_delete', '=', 0);
        $detail['express_price'] = $model->sum('express_price') ? $model->sum('express_price') : 0;
        $detail['bag_price'] = $model->sum('bag_price') ? $model->sum('bag_price') : 0;
        $detail['product_price'] = $model->sum('total_price') ? $model->sum('total_price') : 0;
        $detail['refund_money'] = $model->sum('refund_money') ? $model->sum('refund_money') : 0;
        $detail['total_price'] = $model->sum('pay_price') ? $model->sum('pay_price') : 0;
        $detail['income_money'] = round($detail['total_price'] - $detail['refund_money'], 2);
        $detail['order_count'] = $model->count();
        return $detail;
    }

    /**
     * 获取商品销量Top10
     */
    public function getProductRank($type, $product_type, $shop_supplier_id = 0)
    {
        $model = new OrderProduct;
        if ($type == 0) {
            $order = 'total_num desc';
        } else {
            $order = 'total_price desc';
        }
        if ($product_type >= 0) {
            $model = $model->where('p.product_type', '=', $product_type);
        }
        if ($shop_supplier_id) {
            $model = $model->where('p.shop_supplier_id', '=', $shop_supplier_id);
        }
        $list = $model->alias('op')
            ->where('o.pay_status', '=', 20)
            ->where('o.order_status', '<>', 20)
            ->join('order o', 'op.order_id=o.order_id')
            ->join('product p', 'p.product_id=op.product_id')
            ->field('p.product_name,sum(total_pay_price) as total_price,sum(total_num) as total_num')
            ->group('op.product_id')
            ->order($order)
            ->limit(10)
            ->select();
        return $list;
    }

    public function sendWxExpress()
    {
        //判断是否开启小程序发货
        $setting = SettingModel::getItem('store', $this['app_id']);
        if ($this['wx_delivery_status'] != 10 || !$setting['is_send_wx']) {
            return false;
        }
        // 如果是小程序微信支付，则提交
        if ($this['pay_type']['value'] != 20 || $this['pay_source'] != 'wx' || $this['transaction_id'] == '') {
            return false;
        }
        $logistics_type = $this['delivery_type']['value'] == 10 ? 2 : 4;
        // 请求参数
        $params_arr = [
            // 订单，需要上传物流信息的订单
            'order_key' => [
                // 订单单号类型，用于确认需要上传详情的订单。枚举值1，使用下单商户号和商户侧单号；枚举值2，使用微信支付单号。
                "order_number_type" => 2,
                // 原支付交易对应的微信订单号
                "transaction_id" => $this['transaction_id']
            ],
            // 发货模式，发货模式枚举值：1、UNIFIED_DELIVERY（统一发货）2、SPLIT_DELIVERY（分拆发货） 示例值: UNIFIED_DELIVERY
            "delivery_mode" => 1,
            // 物流模式，发货方式枚举值：1、实体物流配送采用快递公司进行实体物流配送形式 2、同城配送 3、虚拟商品，虚拟商品，例如话费充值，点卡等，无实体配送形式 4、用户自提
            "logistics_type" => $logistics_type,
            // 物流信息列表，发货物流单列表，支持统一发货（单个物流单）和分拆发货（多个物流单）两种模式，多重性: [1, 10]
            "shipping_list" => [
                [
                    // 物流单号，物流快递发货时必填，示例值: 323244567777 字符字节限制: [1, 128]
                    "tracking_no" => '',
                    // 物流公司编码，快递公司ID，参见「查询物流公司编码列表」，物流快递发货时必填， 示例值: DHL 字符字节限制: [1, 128]
                    "express_company" => '',
                    // 商品信息，例如：微信红包抱枕*1个，限120个字以内
                    "item_desc" => $this['product'][0]['product_name'],
                    // 联系方式，当发货的物流公司为顺丰时，联系方式为必填，收件人或寄件人联系方式二选一
                    "contact" => [
                        // 收件人联系方式，收件人联系方式为，采用掩码传输，最后4位数字不能打掩码 示例值: `189****1234, 021-****1234, ****1234, 0**2-***1234, 0**2-******23-10, ****123-8008` 值限制: 0 ≤ value ≤ 1024
                        "receiver_contact" => $this['delivery_type']['value'] == 10 ? $this->desensitize($this['address']['phone'], 3, 4) : ''
                    ]
                ]
            ],
            // 上传时间，用于标识请求的先后顺序 示例值: `2022-12-15T13:29:35.120+08:00`
            "upload_time" => $this->getUploadTime(),
            // 支付者，支付者信息
            "payer" => [
                // 用户标识，用户在小程序appid下的唯一标识。 下单前需获取到用户的Openid 示例值: oUpF8uMuAJO_M2pxb1Q9zNjWeS6o 字符字节限制: [1, 128]
                "openid" => $this['user']['open_id']
            ]
        ];
        // 小程序配置信息
        $app = AppWx::getApp($this['app_id']);
        $model = new WxOrder($app);
        if ($model->uploadExpress($params_arr)) {
            $this->save(['wx_delivery_status' => 20]);
            return true;
        } else {
            log_write($model->getError());
            $this->error = $model->getError();
            return false;
        }
    }

    private function getUploadTime()
    {
        $microtime = microtime();
        list($microSeconds, $timeSeconds) = explode(' ', $microtime);
        $milliseconds = round($microSeconds * 1000);
        return date('Y-m-d') . 'T' . date('H:i:s') . '.' . $milliseconds . '+08:00';
    }

    /**
     * 脱敏
     *
     * @authors: Msy
     * @Created-Time: 2022/10/17 17:54
     * @param $string 需要脱敏的字符
     * @param $start  开始位置
     * @param $length 脱敏长度
     * @param $re     替换字符
     * @return string
     */
    public function desensitize($string, $start = 0, $length = 0, $re = '*')
    {
        if (empty($string) || empty($length) || empty($re)) return $string;
        $end = $start + $length;
        $strlen = mb_strlen($string);
        $str_arr = array();
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < $end) {
                $str_arr[] = $re;
            } else {
                $str_arr[] = mb_substr($string, $i, 1);
            }
        }
        return implode('', $str_arr);
    }

    //生成取餐号
    public function callNo($shop_supplier_id)
    {
        $startTime = strtotime(date('Y-m-d'));
        $endTime = $startTime + 86399;
        $model = $this;
        $count = $model->where('shop_supplier_id', '=', $shop_supplier_id)
            ->where('create_time', 'between', [$startTime, $endTime])
            ->count();
        if ($count) {
            $num = $count + 1;
        } else {
            $num = 1;
        }
        if ($num < 10) {
            $num = '00' . $num;
        }
        if ($num >= 10 && $num < 100) {
            $num = '0' . $num;
        }
        return $num;
    }

    /**
     * 商品销售榜
     */
    public function getSaleTimeRanking($param, $type, $shop_supplier_id = 0)
    {
        $model = new OrderProduct;
        if ($type == 0) {
            $order = 'total_num desc';
        } else {
            $param['product_time'] = $param['product_sale_time'];
            $order = 'total_price desc';
        }
        if ($shop_supplier_id) {
            $model = $model->where('p.shop_supplier_id', '=', $shop_supplier_id);
        }
        if ($param['product_time'] == 1) {//今日
            $model = $model->where('op.create_time', '>=', strtotime(date('Y-m-d')));
        } elseif ($param['product_time'] == 2) {//近7天
            $model = $model->where('op.create_time', '>=', strtotime(date('Y-m-d', strtotime('-7 day'))));
        } elseif ($param['product_time'] == 3) {//本月
            $model = $model->where('op.create_time', '>=', strtotime(date('Y-m')));
        } elseif ($param['product_time'] == 4) {//本年
            $model = $model->where('op.create_time', '>=', strtotime(date('Y-01-01')));
        }
        $list = $model->alias('op')
            ->where('o.pay_status', '=', 20)
            ->where('o.order_status', '<>', 20)
            ->join('order o', 'op.order_id=o.order_id')
            ->join('product p', 'p.product_id=op.product_id')
            ->field('p.product_name,sum(total_pay_price) as total_price,sum(total_num) as total_num')
            ->group('op.product_id')
            ->order($order)
            ->limit(5)
            ->select();
        return $list;
    }

    /**
     * 订单概况
     */
    public function getGeneralOrder($param, $type, $shop_supplier_id = 0)
    {
        $model = $this;
        if ($shop_supplier_id) {
            $model = $model->where('shop_supplier_id', '=', $shop_supplier_id);
        }
        if ($type == 1) {//外送订单
            $model = $model->where('order_type', '=', 0)->where('delivery_type', '=', 10);
        } elseif ($type == 2) {//自提订单
            $model = $model->where('order_type', '=', 0)->where('delivery_type', '=', 20);
        } elseif ($type == 3) {//堂食订单
            $model = $model->where('order_type', '=', 1)->where('delivery_type', '=', 40);
        } elseif ($type == 4) {//打包订单
            $model = $model->where('order_type', '=', 1)->where('delivery_type', '=', 30);
        }
        if ($param['order_time'] == 1) {//今日
            $model = $model->where('create_time', '>=', strtotime(date('Y-m-d')));
        } elseif ($param['order_time'] == 2) {//近7天
            $model = $model->where('create_time', '>=', strtotime(date('Y-m-d', strtotime('-7 day'))));
        } elseif ($param['order_time'] == 3) {//本月
            $model = $model->where('create_time', '>=', strtotime(date('Y-m')));
        } elseif ($param['order_time'] == 4) {//本年
            $model = $model->where('create_time', '>=', strtotime(date('Y-01-01')));
        }
        $num = $model->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->count();
        return $num;
    }

}