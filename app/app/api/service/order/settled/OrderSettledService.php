<?php

namespace app\api\service\order\settled;

use app\api\model\order\Order as OrderModel;
use app\api\model\order\OrderProduct;
use app\api\model\order\OrderAddress as OrderAddress;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\enum\order\OrderTypeEnum;
use app\common\model\settings\Setting as SettingModel;
use app\api\service\points\PointsDeductService;
use app\api\service\coupon\ProductDeductService;
use app\api\service\user\UserService;
use app\common\library\helper;
use app\common\service\delivery\ExpressService;
use app\common\service\BaseService;
use app\common\service\product\factory\MasterProductService;

/**
 * 订单结算服务基类
 */
abstract class OrderSettledService extends BaseService
{
    //OrderModel 订单模型
    public $model;

    // 当前应用id
    protected $app_id;

    protected $user;

    // 订单结算商品列表
    protected $productList = [];

    protected $params;

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
        $orderTotalNum = helper::getArrayColumnSum($this->productList, 'total_num');
        // 设置订单商品总金额(不含优惠折扣)
        $this->setOrderTotalPrice();
        //快递费用
        $this->setOrderExpress();

        // 计算订单商品的实际付款金额
        $this->setOrderProductPayPrice();

        // 计算订单最终金额
        $this->setOrderPayPrice();

        // 返回订单数据
        return array_merge([
            'product_list' => array_values($this->productList),   // 商品信息
            'order_total_num' => $orderTotalNum,        // 商品总数量
        ], $this->orderData);
    }

    /**
     * 计算订单商品的实际付款金额
     */
    private function setOrderProductPayPrice()
    {
        // 商品总价 - 优惠抵扣
        foreach ($this->productList as &$product) {
            $product['total_pay_price'] = helper::number2($product['total_price']);
        }
        return true;
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
        $this->model->transaction(function () use ($order) {
            // 创建订单事件
            return $this->createOrderEvent($order);
        });

        return $this->model['order_id'];
    }

    /**
     * 设置订单的商品总金额(不含优惠折扣)
     */
    private function setOrderTotalPrice()
    {
        // 订单商品的总金额(不含优惠券折扣)
        $this->orderData['order_total_price'] = helper::number2(helper::getArrayColumnSum($this->productList, 'total_price'));
    }


    /**
     * 整理订单数据(结算台初始化)
     */
    private function getOrderData()
    {
        // 系统支持的配送方式 (后台设置)
        $deliveryType = SettingModel::getItem('store')['delivery_type'];
        return [
            // 配送类型
            'delivery' => $this->params['delivery'] > 0 ? $this->params['delivery'] : $deliveryType[0],
            // 默认地址
            'address' => $this->user['address_default'],
            // 是否存在收货地址
            'exist_address' => $this->user['address_id'] > 0,
            // 配送费用
            'express_price' => 0.00,
            // 当前用户收货城市是否存在配送规则中
            'intra_region' => true,
            // 支付方式
            'pay_type' => isset($this->params['pay_type']) ? $this->params['pay_type'] : OrderPayTypeEnum::WECHAT,
            // 系统设置
            'setting' => [
                'delivery' => $deliveryType,     // 支持的配送方式
            ],
            'deliverySetting' => $deliveryType,
        ];
    }

    /**
     * 订单配送-快递配送
     */
    private function setOrderExpress()
    {
        // 设置默认数据：配送费用
        helper::setDataAttribute($this->productList, [
            'express_price' => 0,
        ], true);
        // 当前用户收货城市id
        $cityId = $this->user['address_default'] ? $this->user['address_default']['city_id'] : null;

        // 初始化配送服务类
        $ExpressService = new ExpressService(
            $this->app_id,
            $cityId,
            $this->productList,
            OrderTypeEnum::MASTER
        );

        // 获取不支持当前城市配送的商品
        $notInRuleProduct = $ExpressService->getNotInRuleProduct();

        // 验证商品是否在配送范围
        $this->orderData['intra_region'] = ($notInRuleProduct === false);

        if (!$this->orderData['intra_region']) {
            $notInRuleProductName = $notInRuleProduct['product_name'];
            $this->error = "很抱歉，您的收货地址不在商品 [{$notInRuleProductName}] 的配送范围内";
            return false;
        } else {
            // 计算配送金额
            $ExpressService->setExpressPrice();
        }

        // 订单总运费金额
        $this->orderData['express_price'] = helper::number2($ExpressService->getTotalFreight());
        return true;
    }

    /**
     * 设置订单的实际支付金额(含配送费)
     */
    private function setOrderPayPrice()
    {
        // 订单金额(含优惠折扣)
        $this->orderData['order_price'] = helper::number2(helper::getArrayColumnSum($this->productList, 'total_pay_price'));
        // 订单实付款金额(订单金额 + 运费)
        $this->orderData['order_pay_price'] = helper::number2(helper::bcadd($this->orderData['order_price'], $this->orderData['express_price']));
    }

    /**
     * 表单验证 (订单提交)
     */
    private function validateOrderForm(&$order)
    {
        if (empty($order['address'])) {
            $this->error = '请先选择收货地址';
            return false;
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
        // 记录收货地址
        $this->saveOrderAddress($order['address'], $status);
        // 保存订单商品信息
        $this->saveOrderProduct($order, $status);

        // 更新商品库存 (针对下单减库存的商品)
        (new MasterProductService())->updateProductStock($order['product_list']);
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
            'total_price' => $order['order_total_price'],
            'order_price' => $order['order_price'],
            'pay_price' => $order['order_pay_price'],
            'delivery_type' => $order['delivery'],
            'pay_type' => $order['pay_type'],
            'buyer_remark' => trim($remark),
            'app_id' => $this->app_id,
        ];
        //快递费用
        $data['express_price'] = $order['express_price'];
        // 保存订单记录
        $this->model->save($data);
        $new_order_id = $this->model->order_id;
        return $new_order_id;
    }

    /**
     * 记录收货地址
     */
    private function saveOrderAddress($address, $order_id)
    {
        $model = new OrderAddress();
        if ($address['region_id'] == 0 && !empty($address['district'])) {
            $address['detail'] = $address['district'] . ' ' . $address['detail'];
        }
        return $model->save([
            'order_id' => $order_id,
            'user_id' => $this->user['user_id'],
            'app_id' => $this->app_id,
            'name' => $address['name'],
            'phone' => $address['phone'],
            'province_id' => $address['province_id'],
            'city_id' => $address['city_id'],
            'region_id' => $address['region_id'],
            'detail' => $address['detail'],
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
                'product_name' => $product['product_name'],
                'image_id' => $product['image'][0]['image_id'],
                'deduct_stock_type' => $product['deduct_stock_type'],
                'spec_type' => $product['spec_type'],
                'spec_sku_id' => $product['product_sku']['spec_sku_id'],
                'product_sku_id' => $product['product_sku']['product_sku_id'],
                'product_attr' => $product['product_sku']['product_attr'],
                'content' => $product['content'],
                'product_no' => $product['product_sku']['product_no'],
                'product_price' => $product['product_sku']['product_price'],
                'line_price' => $product['product_sku']['line_price'],
                'product_weight' => $product['product_sku']['product_weight'],
                'total_num' => $product['total_num'],
                'total_price' => $product['total_price'],
                'total_pay_price' => $product['total_price'],
            ];
            $productList[] = $item;
        }

        $model = new OrderProduct();
        return $model->saveAll($productList);
    }

}