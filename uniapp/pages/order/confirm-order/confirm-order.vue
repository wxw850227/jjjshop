<template>
	<view class="wrap" v-if="!loading">
		<Myinfo v-if="tab_type == 0" :Address="Address" :exist_address="exist_address"></Myinfo>
		<!--购买的产品-->
		<view class="vender">
			<view class="group-hd">
				<view class="left"><text class="min-name">商品</text></view>
			</view>
			<view class="list">
				<view class="item" v-for="(item, index) in ProductData" :key="index">
					<view class="cover">
						<image :src="item.product_image" mode="aspectFit"></image>
					</view>
					<view class="info">
						<view class="title">{{ item.product_name }}</view>
						<view class="describe">{{ item.product_sku.product_attr }}</view>

						<view class="level-box count_choose">
							<view class="price">
								¥
								<text class="num">{{ item.product_price }}</text>
							</view>
							<view class="num-wrap">
								<view class="f22">×{{ item.total_num }}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<!--总数-->
			<view class="total-box">
				<text>共{{ OrderData.order_total_num }}件商品，</text>
				<view class="">
					合计：
					<text class="price">￥{{ OrderData.order_total_price }}</text>
				</view>
			</view>
		</view>

		<!--其它费用-->
		<view class="buy-checkout">
			<view class="item">
				<text class="key">订单总金额：</text>
				<text class="price">￥{{ OrderData.order_total_price }}</text>
			</view>
			<view class="item">
				<text class="key">配送费用：</text>
				<text class="price">￥{{ OrderData.express_price }}</text>
			</view>
		</view>

		<!--支付方式-->
		<view class="buy-checkout">
			<view class="item active">
				<view class="d-s-c">
					<view class="icon-box d-c-c mr10"><span class="icon iconfont icon-weixin"></span></view>
					<text class="key">微信支付：</text>
				</view>
				<view class="icon-box d-c-c"><span class="icon iconfont icon-xuanze"></span></view>
			</view>
		</view>

		<!--买家留言-->
		<view class="buyer-message uni-textarea"><textarea class="textarea" :remark="remark" placeholder-style="color:#cccccc;" placeholder="选项:买家留言" /></view>

		<!--底部支付-->
		<view class="foot-pay-btns">
			<view class="price" v-if="!OrderData.force_points">
				¥
				<text class="num">{{ OrderData.order_pay_price }}</text>
			</view>
			<button type="primary" @click="SubmitOrder">提交订单</button>
		</view>
	</view>
</template>

<script>
import Myinfo from './part/my-info';
import imageComponent from '@/components/imageComponent/imageComponent.vue';
import { pay } from '@/common/pay.js';
export default {
	components: {
		Myinfo,
		imageComponent
	},
	data() {
		return {
			/*是否加载完成*/
			loading: true,
			options: {},
			indicatorDots: true,
			autoplay: true,
			interval: 2000,
			duration: 500,
			tab_type: 0,
			/*商品id*/
			product_id: '',
			/*商品数量*/
			product_num: '',
			/*商品数据*/
			ProductData: [],
			/*订单数据数据*/
			OrderData: [],
			// 是否存在收货地址
			exist_address: false,
			/*默认地址*/
			Address: {
				region: []
			},
			extract_shop: [],
			product_sku_id: 0,
			/*配送方式*/
			delivery: 10,
			remark: '',
			/*支付方式*/
			pay_type: 20,
			deliverySetting: []
		};
	},
	onLoad(options) {
		let self = this;
		self.options = options;
	},
	onShow() {
		this.getData();
	},
	methods: {
		/**/
		hasType(e) {
			if (this.deliverySetting.indexOf(e) != -1) {
				return true;
			} else {
				return false;
			}
		},
		/*获取数据*/
		getData() {
			let self = this;
			uni.showLoading({
				title: '加载中',
				mask:true
			});

			let callback = function(res) {
				self.exist_address = res.data.exist_address;
				self.Address = res.data.address;
				self.ProductData = res.data.product_list;
				self.OrderData = res.data;

				self.deliverySetting = res.data.deliverySetting;
				if (self.OrderData.order_pay_price == 0) {
					self.pay_type = 10;
				}
				self.loading = false;
			};

			// 请求的参数
			let params = {
				delivery: self.delivery
			};

			//直接购买
			if (self.options.order_type === 'buy') {
				self._get(
					'order.order/buy',
					Object.assign({}, params, {
						product_id: self.options.product_id,
						product_num: self.options.product_num,
						product_sku_id: self.options.product_sku_id
					}),
					function(res) {
						callback(res);
					}
				);
			}
			// 购物车结算
			else if (self.options.order_type === 'cart') {
				self._get(
					'order.order/cart',
					Object.assign({}, params, {
						cart_ids: self.options.cart_ids || 0
					}),
					function(res) {
						callback(res);
					}
				);
			}
		},
		/*选择配送方式*/
		tabFunc(e) {
			this.tab_type = e;
			if (e == 0) {
				this.delivery = 10;
			} else {
				this.delivery = 20;
			}
			this.getData();
		},
		/*提交订单*/
		SubmitOrder() {
			let self = this;
			let pay_source = 'wx';
			uni.showLoading({
				title: '加载中',
				mask:true
			});

			let params = {
				delivery: self.delivery,
				remark: self.remark,
				pay_type: self.pay_type,
				pay_source: pay_source
			};

			// 创建订单-直接下单
			let url = '';
			if (self.options.order_type === 'buy') {
				url = 'order.order/buy';
				params = Object.assign({}, params, {
					product_id: self.options.product_id,
					product_num: self.options.product_num,
					product_sku_id: self.options.product_sku_id
				});
			}

			// 创建订单-购物车结算
			if (self.options.order_type === 'cart') {
				url = 'order.order/cart';
				params = Object.assign({}, params, {
					cart_ids: self.options.cart_ids || 0
				});
			}

			self._post(url, params, function(result) {
				pay(result, self);
			});
		}
	}
};
</script>

<style lang="scss">
.wrap {
	padding-bottom: 90rpx;
}
</style>
