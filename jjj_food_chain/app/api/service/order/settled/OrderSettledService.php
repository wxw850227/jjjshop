<?php

namespace app\api\service\order\settled;

use app\api\model\order\Order as OrderModel;
use app\api\model\order\OrderProduct;
use app\api\model\order\OrderAddress as OrderAddress;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\model\settings\Setting as SettingModel;
use app\api\service\user\UserService;
use app\common\enum\settings\DeliveryTypeEnum;
use app\common\library\helper;
use app\common\service\BaseService;
use app\common\service\product\factory\ProductFactory;
use app\api\model\supplier\Supplier as SupplierModel;
use app\api\model\store\Table as TableModel;

/**
 * 订单结算服务基类
 */
abstract class OrderSettledService extends BaseService
{
    /* $model OrderModel 订单模型 */
    public $model;

    // 当前应用id
    protected $app_id;

    protected $user;

    // 订单结算商品列表
    protected $productList = [];

    protected $params;
    /**
     * 订单结算的规则
     * 主商品默认规则
     */
    protected $settledRule = [
        'is_coupon' => true,        // 优惠券抵扣
        'is_user_grade' => true,     // 会员等级折扣
        'is_agent' => true,     // 商品是否开启分销,最终还是支付成功后判断分销活动是否开启
    ];

    /**
     * 订单结算数据
     */
    protected $orderData = [];
    /**
     * 订单来源
     */
    protected $orderSource;

    /**
     * 构造函数
     */
    public function __construct($user, $productList, $params)
    {

        $this->model = new OrderModel;
        $this->app_id = OrderModel::$app_id;
        $this->user = $user;
        $this->productList = $productList;
        $this->params = $params;
    }

    /**
     * 订单确认-结算台
     */
    public function settlement()
    {
        // 整理订单数据
        $this->orderData = $this->getOrderData();
        // 验证商品状态, 是否允许购买
        $this->validateProductList();
        // 订单商品总数量
        $orderTotalNum = helper::getArrayColumnSum($this->productList, 'product_num');
        // 设置订单商品总金额(不含优惠折扣)
        $this->setOrderTotalPrice();
        // 计算订单商品的实际付款金额
        $this->setOrderProductPayPrice();
        // 处理配送方式
        if ($this->orderData['delivery'] == DeliveryTypeEnum::EXPRESS) {
            $this->setOrderExpress();
        }
        // 计算订单最终金额
        $this->setOrderPayPrice();
        // 返回订单数据
        return array_merge([
            'product_list' => $this->productList,   // 商品信息
            'order_total_num' => $orderTotalNum,        // 商品总数量
        ], $this->orderData, $this->settledRule);
    }

    /**
     * 验证订单商品的状态
     * @return bool
     */
    abstract function validateProductList();

    /**
     * 创建新订单
     */
    public function createOrder($order)
    {
        // 表单验证
        if (!$this->validateOrderForm($order, $this->params)) {
            return false;
        }
        // 创建新的订单
        $order_id = $this->model->transaction(function () use ($order) {
            // 创建订单事件
            return $this->createOrderEvent($order);
        });
        return $order_id;
    }

    /**
     * 设置订单的商品总金额(不含优惠折扣)
     */
    private function setOrderTotalPrice()
    {
        // 订单商品的总金额(不含优惠券折扣)
        $this->orderData['order_total_price'] = helper::number2(helper::getArrayColumnSum($this->productList, 'total_price'));
        //外卖订单
        if ($this->params['delivery'] <= 30) {
            if ($this->params['delivery'] == 30) {
                if ($this->orderData['supplier']['storebag_type'] == 1) {
                    $this->orderData['order_bag_price'] = $this->orderData['supplier']['storebag_price'];
                } else {
                    $this->orderData['order_bag_price'] = helper::number2(helper::getArrayColumnSum($this->productList, 'total_bag_price'));
                }
            } else {
                if ($this->orderData['supplier']['bag_type'] == 1) {
                    $this->orderData['order_bag_price'] = $this->orderData['supplier']['bag_price'];
                } else {
                    $this->orderData['order_bag_price'] = helper::number2(helper::getArrayColumnSum($this->productList, 'total_bag_price'));
                }
            }

        }
    }

    /**
     * 计算订单商品的实际付款金额
     */
    private function setOrderProductPayPrice()
    {
        // 商品总价 - 优惠抵扣
        foreach ($this->productList as &$product) {
            $product['total_pay_price'] = $product['total_price'];
        }
        return true;
    }

    /**
     * 整理订单数据(结算台初始化)
     */
    private function getOrderData()
    {
        $supplier = SupplierModel::detail($this->params['shop_supplier_id']);
        // 系统支持的配送方式 (后台设置)
        $deliveryType = $this->params['order_type'] == 0 ? $supplier['delivery_set'] : $supplier['store_set'];
        sort($deliveryType);
        $delivery = $this->params['delivery'] > 0 ? $this->params['delivery'] : $deliveryType[0];
        //获取桌号信息
        $table_no = '';
        if (isset($this->params['table_id']) && ($this->params['table_id'])) {
            $table = TableModel::detail($this->params['table_id']);
            $table_no = $table['table_no'];
        }

        return [
            // 配送类型
            'delivery' => $delivery,
            // 默认地址
            'address' => $this->user['address_default'],
            // 是否存在收货地址
            'exist_address' => $this->user['address_id'] > 0,
            // 配送费用
            'express_price' => 0.00,
            // 支付方式
            'pay_type' => isset($this->params['pay_type']) ? $this->params['pay_type'] : OrderPayTypeEnum::WECHAT,
            // 系统设置
            'setting' => [
                'delivery' => $deliveryType,     // 支持的配送方式
            ],
            // 记忆的自提联系方式
            'last_extract' => UserService::getLastExtract($this->user['user_id']),
            'deliverySetting' => $deliveryType,
            //包装费
            'order_bag_price' => 0,
            //门店信息
            'supplier' => $supplier,
            //桌号id
            'table_id' => isset($this->params['table_id']) && ($this->params['table_id']) ? $this->params['table_id'] : 0,
            //桌号信息
            'table_no' => $table_no,
            //订单类型
            'order_type' => $this->params['order_type'],
        ];
    }

    /**
     * 订单配送
     */
    private function setOrderExpress()
    {
        //查询是否满足满额包邮
        $this->orderData['express_price'] = $this->orderData['supplier']['shipping_fee'];
        return true;
    }

    /**
     * 设置订单的实际支付金额(含配送费)
     */
    private function setOrderPayPrice()
    {
        //应付金额
        $this->orderData['total_order_price'] = helper::number2(helper::bcadd($this->orderData['order_total_price'], $this->orderData['order_bag_price'] + $this->orderData['express_price']));
        // 订单实付款金额(订单金额 + 运费)
        $this->orderData['order_price'] = helper::number2(helper::getArrayColumnSum($this->productList, 'total_pay_price'));
        // 订单实付款金额(订单金额 + 运费)
        $this->orderData['order_pay_price'] = helper::number2(helper::bcadd($this->orderData['order_price'], $this->orderData['express_price']));
        // 订单实付款金额(订单金额 + 包装费)
        $this->orderData['order_pay_price'] = helper::number2(helper::bcadd($this->orderData['order_pay_price'], $this->orderData['order_bag_price']));
    }

    /**
     * 表单验证 (订单提交)
     */
    private function validateOrderForm(&$order, $param)
    {
        //判断是否营业
        $business = (new SupplierModel)->supplierState($param['shop_supplier_id'], $param['dinner_type']);
        if (!$business) {
            return false;
        }
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            if (empty($order['address'])) {
                $this->error = '请先选择联系地址';
                return false;
            }
        }
        if ($order['delivery'] == DeliveryTypeEnum::EXTRACT) {
            if (empty($this->params['phone'])) {
                $this->error = '请填写联系人电话';
                return false;
            }
        }
        return true;
    }

    /**
     * 创建订单事件
     */
    private function createOrderEvent($order)
    {
        // 新增订单记录
        $status = $this->add($order, $this->params['remark']);

        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            // 记录收货地址
            $this->saveOrderAddress($order['address'], $status);
        } elseif ($order['delivery'] == DeliveryTypeEnum::EXTRACT) {
            // 记录自提信息
            $this->saveOrderExtract($this->params['linkman'], $this->params['phone']);
        }
        // 保存订单商品信息
        $this->saveOrderProduct($order, $status);
        // 更新商品库存 (针对下单减库存的商品)
        ProductFactory::getFactory($this->orderSource['source'])->updateProductStock($order['product_list']);
        return $status;
    }

    /**
     * 新增订单记录
     */
    private function add($order, $remark = '')
    {
        // 订单数据
        $data = [
            'user_id' => $this->user['user_id'],
            'order_no' => $this->model->orderNo(),
            'trade_no' => $this->model->tradeNo(),
            'total_price' => $order['order_total_price'],
            'bag_price' => $order['order_bag_price'],
            'order_price' => $order['total_order_price'],
            'pay_price' => $order['order_pay_price'],
            'delivery_type' => $order['delivery'],
            'pay_type' => $order['pay_type'],
            'buyer_remark' => trim($remark),
            'order_source' => $this->orderSource['source'],
            'activity_id' => isset($this->orderSource['activity_id']) ? $this->orderSource['activity_id'] : 0,
            'shop_supplier_id' => $this->params['shop_supplier_id'],
            'mealtime' => isset($this->params['mealtime']) ? $this->params['mealtime'] : '',
            'order_type' => $order['order_type'],
            'table_no' => $order['table_no'],
            'table_id' => $order['table_id'],
            'callNo' => 'A' . $this->model->callNo($this->params['shop_supplier_id']),
            'app_id' => $this->app_id,
        ];
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            $data['express_price'] = $order['express_price'];
        }
        //随主订单配置
        $config = SettingModel::getItem('trade');
        $closeDays = $config['order']['close_days'];
        $data['pay_end_time'] = time() + ($closeDays * 60);
        // 保存订单记录
        $this->model->save($data);
        return $this->model['order_id'];
    }

    /**
     * 记录收货地址
     */
    private function saveOrderAddress($address, $order_id)
    {
        $model = new OrderAddress();
        return $model->save([
            'order_id' => $order_id,
            'user_id' => $this->user['user_id'],
            'app_id' => $this->app_id,
            'name' => $address['name'],
            'phone' => $address['phone'],
            'detail' => $address['detail'],
            'address' => $address['address'],
            'longitude' => $address['longitude'],
            'latitude' => $address['latitude'],
        ]);
    }

    /**
     * 保存上门自提联系人
     */
    private function saveOrderExtract($linkman, $phone)
    {
        // 记忆上门自提联系人(缓存)，用于下次自动填写
        UserService::setLastExtract($this->model['user_id'], trim($linkman), trim($phone));
        // 保存上门自提联系人(数据库)
        return $this->model->extract()->save([
            'linkman' => trim($linkman),
            'phone' => trim($phone),
            'user_id' => $this->model['user_id'],
            'app_id' => $this->app_id,
        ]);
    }

    /**
     * 保存订单商品信息
     */
    private function saveOrderProduct($order, $status)
    {
        // 订单商品列表
        $productList = [];
        foreach ($order['product_list'] as $product) {
            $item = [
                'order_id' => $status,
                'user_id' => $this->user['user_id'],
                'app_id' => $this->app_id,
                'product_id' => $product['product_id'],
                'product_name' => $product['product']['product_name'],
                'image_id' => $product['product']['logo']['image_id'],
                'deduct_stock_type' => $product['product']['deduct_stock_type'],
                'spec_type' => $product['product']['spec_type'],
                'product_sku_id' => $product['sku']['product_sku_id'],
                'product_attr' => $product['describe'],
                'content' => $product['product']['content'],
                'product_price' => $product['price'],
                'line_price' => $product['product_price'],
                'total_num' => $product['product_num'],
                'total_price' => $product['total_price'],
                'total_pay_price' => $product['total_pay_price'],
            ];
            $productList[] = $item;
        }
        $model = new OrderProduct();
        return $model->saveAll($productList);
    }

    /**
     * 设置订单商品会员折扣价
     */
    private function setOrderGrade()
    {
        // 设置默认数据
        helper::setDataAttribute($this->productList, [
            // 标记参与会员折扣
            'is_user_grade' => false,
            // 会员等级抵扣的金额
            'grade_ratio' => 0,
            // 会员折扣的商品单价
            'grade_product_price' => 0.00,
            // 会员折扣的总额差
            'grade_total_money' => 0.00,
        ], true);

        // 是否开启会员等级折扣
        if (!$this->settledRule['is_user_grade']) {
            return false;
        }
        // 计算抵扣金额
        foreach ($this->productList as &$product) {
            // 判断商品是否参与会员折扣
            if (!$product['is_enable_grade']) {
                continue;
            }
            $alone_grade_type = 10;
            // 商品单独设置了会员折扣
            if ($product['is_alone_grade'] && isset($product['alone_grade_equity'][$this->user['grade_id']])) {
                if ($product['alone_grade_type'] == 10) {
                    // 折扣比例
                    $discountRatio = helper::bcdiv($product['alone_grade_equity'][$this->user['grade_id']], 100);
                } else {
                    $alone_grade_type = 20;
                    $discountRatio = helper::bcdiv($product['alone_grade_equity'][$this->user['grade_id']], $product['product_price'], 2);
                }
            } else {
                // 折扣比例
                $discountRatio = helper::bcdiv($this->user['grade']['equity'], 100);
            }
            if ($discountRatio < 1) {
                // 会员折扣后的商品总金额
                if ($alone_grade_type == 20) {
                    // 固定金额
                    $grade_product_price = $product['alone_grade_equity'][$this->user['grade_id']];
                } else {
                    $grade_product_price = helper::number2(helper::bcmul($product['product_price'], $discountRatio), true);
                }
                $gradeTotalPrice = $grade_product_price * $product['product_num'];

                helper::setDataAttribute($product, [
                    'is_user_grade' => true,
                    'grade_ratio' => $discountRatio,
                    'grade_product_price' => $grade_product_price,
                    'grade_total_money' => helper::number2(helper::bcsub($product['total_price'], $gradeTotalPrice)),
                    'total_price' => $gradeTotalPrice,
                ], false);
            }
        }
        return true;
    }

}