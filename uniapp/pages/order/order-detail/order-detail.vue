<template>
	<view class="order-datail" v-if="!loadding">
		<!--详情状态-->
		<view class="order-state d-s-c">
			<view class="icon-box">
				<!--感叹号-->
				<!-- <span class="icon iconfont icon-gantanhao"></span> -->
				<!--成功-->
				<!-- <span class="icon iconfont icon-xuanze"></span> -->
				<!--发货收货-->
				<!-- <span class="icon iconfont icon-daishouhuo"></span> -->
				<!--已支付待支付-->
				<span  v-if="detail.state_text=='已付款，待发货'"  class="icon iconfont icon-icon"></span>
				<span  v-if="detail.state_text=='待付款'"  class="icon iconfont icon-icon"></span>
				<span  v-if="detail.state_text=='已发货，待收货'"  class="icon iconfont icon-daishouhuo"></span>
				<span  v-if="detail.state_text=='已完成'"  class="icon iconfont icon-xuanze"></span>
				<span  v-if="detail.state_text=='已取消'" class="icon iconfont icon-gantanhao"></span>
			</view>
			<view class="state-cont flex-1">
				<view class="state-txt d-b-c">
					<text class="desc f34">{{ detail.state_text }}</text>
				</view>
				<view class="status-price pt10" v-if="detail.pay_status.value==10">应付金额：¥ {{ detail.pay_price }}</view>
			</view>
			<view class="dot-bg"></view>
		</view>

		<!--物流地址-->
		<view class="order-express p30 d-s-c" v-if="detail.delivery_type.value == 10">
			<view class="icon-box"><span class="icon iconfont icon-dizhi1"></span></view>
			<view class="cont-text ml20">
				<view class="express-text">{{ detail.address.name }} {{ detail.address.phone }}</view>
				<view class="f24 gray9 pt10">
					{{ detail.address.region.province }} {{ detail.address.region.city
                    }} {{ detail.address.region.region }}
					{{ detail.address.detail }}
				</view>
			</view>
		</view>
		<!-- 上门自提：自提门店 -->
		<view class="order-express p30 d-s-c" v-if="detail.delivery_type.value == 20">
			<view class="flow-delivery__title m-top20">
				<span class="icon iconfont icon-dizhi1">自提门店</span>
			</view>
			<view class="cont-text ml20">
				<view class="express-text">
					{{extractStore.shop_name }} {{extractStore.phone }}
					<view class="f24 gray9 pt10">
						{{ extractStore.region.province }} {{ extractStore.region.city}}
						{{ extractStore.region.region }}
						{{ extractStore.address }}
					</view>
				</view>
			</view>
		</view>

		<!-- 物流信息 -->
		<view class="group bg-white" v-if="detail.delivery_type.value == 10 && detail.delivery_status.value == 20">
			<view class="d-b-c">
				<view>
					<view class="p-20-0">
						<text class="gray9">物流公司：</text>
						<text>{{ detail.express.express_name }}</text>
					</view>
					<view class="p-20-0">
						<text class="gray9">物流单号：</text>
						<text>{{ detail.express_no }}</text>
					</view>
				</view>
				<view @click="gotoExpress(detail.order_id)">
					<text class="icon iconfont icon-jiantou"></text>
				</view>
			</view>
		</view>

		<!--购物列表-->
		<view class="shops group bg-white">
			<view class="group-hd border-b-e">
				<view class="left">
					<text class="min-name">商品</text>
				</view>
			</view>
			<view class="list">
				<view class="one-product p-20-0" v-for="(item, index) in detail.product" :key="index">
					<view class="d-s-c">
						<view class="cover">
							<image :src="item.image.file_path" mode="aspectFit"></image>
						</view>
						<view class="flex-1">
							<view class="pro-info">{{ item.product_name }}</view>
							<view class="pt10 p-0-30 d-b-c">
								<view class="price f22">
									¥
									<text class="f40">{{ item.product_price }}</text>
								</view>
								<view class="f24 gray9">x{{ item.total_num }}</view>
							</view>
						</view>
					</view>
					<view class="pt10 d-e-c">

						<!-- 申请售后 -->
						<view class="m-top20 dis-flex flex-x-end">
							<text v-if="item.refund">已申请售后</text>
							<view v-else-if="detail.isAllowRefund" @click="onApplyRefund(item.order_product_id)">
								<button type="default">申请售后</button>
							</view>
						</view>

					</view>
				</view>
			</view>
		</view>

		<!--订单信息-->
		<view class="group bg-white f28">
			<view class="p-20-0">
				<text class="gray9">订单编号：</text>
				<text>{{ detail.order_no }}</text>
			</view>
			<view class="p-20-0">
				<text class="gray9">下单时间：</text>
				<text>{{ detail.create_time }}</text>
			</view>
			<view class="p-20-0">
				<text class="gray9">支付方式：</text>
				<text>{{ detail.pay_type.text }}</text>
			</view>
			<view class="p-20-0">
				<text class="gray9">配送方式：</text>
				<text>{{ detail.delivery_type.text }}</text>
			</view>
			<view class="p-20-0 d-b-c">
				<text class="gray9">商品金额</text>
				<text>¥ {{ detail.order_price }}</text>
			</view>
			<view class="p-20-0 d-b-c">
				<text class="gray9">运费</text>
				<text>+ ¥ {{ detail.express_price }}</text>
			</view>
			<view class="p-20-0 d-e-c fb f34">
				应付金额：
				<text class="red">¥ {{ detail.order_price }}</text>
			</view>
		</view>


		<!-- 操作栏 -->
		<view v-if="detail.order_status.value != 20" class="foot-btns">
			<!-- 取消订单 -->
			<button type="default" v-if="detail.pay_status.value==10" @click="cancelOrder(detail.order_id)">取消订单</button>

			<block v-if="detail.order_status.value!=21">
				<block v-if="detail.pay_status.value==20 && detail.delivery_status.value==10">
					<button @click="cancelOrder(detail.order_id)" type="default">申请取消</button>
				</block>
			</block>
			<text v-else class="count">取消申请中</text>
			<block v-if="detail.pay_status.value == 10">
				<!-- 订单付款 -->
				<button @click="onPayOrder(detail.order_id)" type="primary" v-if="detail.pay_status.value == 10" class="ml10 btn-red">去支付</button>
			</block>
			<!-- 确认收货 -->
			<block v-if="detail.delivery_status.value == 20 && detail.receipt_status.value == 10">
				<button type="default" @click="orderReceipt(detail.order_id)">确认收货</button>
			</block>
		</view>
		<!--支付选择-->
		<Popup :show="isPayPopup" msg="支付方式" @hidePopup="hidePopupFunc">
			<!--支付方式-->
			<view class="buy-checkout">
				<view :class="pay_type==20?'item active border-b-e':'item border-b-e'" @click="payTypeFunc(20)">
					<view class="d-s-c">
						<view class="icon-box d-c-c mr10">
							<span class="icon iconfont icon-weixin"></span>
						</view>
						<text class="key">微信支付</text>
					</view>
					<view class="icon-box d-c-c"></view>
				</view>
				<view :class="pay_type==10?'item active':'item'" @click="payTypeFunc(10)">
					<view class="d-s-c">
						<view class="icon-box d-c-c mr10">
							<span class="icon iconfont icon-yue"></span>
						</view>
						<text class="key">余额支付</text>
					</view>
					<view class="icon-box d-c-c"></view>
				</view>
			</view>
			<view class="bts">

			</view>
		</Popup>

		<!--拼团-->
		<Popup :show="is_fightgroup" @hidePopup="is_fightgroup=false">
			<!--拼团人-->
			<view class="fight-users mt20 bg-white">
				<!--成员列表-->
				<view class="user-list d-c-c">
					<view class="user-box pr" v-for="(item,index) in 3" :key="index">
						<text class="leader" v-if="index==0">团长</text>
						<image src="../../../static/user_active.png" mode="aspectFit"></image>
					</view>
					<view class="user-box user-who d-c-c">
						?
					</view>
				</view>
				<view class="d-c-c mt30 gray3">
					还差<text class="fb">1</text>人成团
				</view>
				<view class="mt20 d-c-c gray9">
					剩余<text>08:00:00</text>结束
				</view>

				<!--去邀请好友-->
				<view class="d-c-c mt30">
					<button type="default" class="btn-red">去邀请好友</button>
				</view>
			</view>
		</Popup>
	</view>

</template>

<script>
	import Popup from '@/components/uni-popup.vue'
	import {pay} from '@/common/pay.js'
	export default {
		data() {
			return {
				/*是否加载完成*/
				loadding: true,
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				/*是否显示支付类别弹窗*/
				isPayPopup: false,
				/*订单id*/
				order_id: 0,
				/*订单详情*/
				detail: {
					order_status: [],
					address: {
						region: []
					},
					product: [],
					pay_type: [],
					delivery_type: [],
					pay_status: []
				},
				extractStore: {},
				/*是否显示拼团*/
				is_fightgroup:false
			};
		},
		components: {
			Popup
		},
		onLoad(e) {
			this.order_id = e.order_id;
		},
		mounted() {
			uni.showLoading({
				title: '加载中',
				mask:true
			});
			/*获取订单详情*/
			this.getData();
		},
		methods: {
			/*获取数据*/
			getData() {
				let _this = this;
				let order_id = _this.order_id;
				_this._get(
					'user.order/detail', {
						order_id: order_id
					},
					function(res) {
						_this.detail = res.data.order;
						_this.extractStore = res.data.order.extractStore;
						console.log(_this.extractStore);
						_this.loadding = false;
						uni.hideLoading();
					}
				);
			},
			/*显示支付方式*/
			hidePopupFunc() {
				this.isPayPopup = false;
			},

			/*取消订单*/
			cancelOrder(e) {
				let self = this;
				let order_id = e;
				wx.showModal({
					title: "提示",
					content: "您确定要取消当前订单吗?",
					success: function(o) {
						uni.showLoading({
							title: '正在处理',
							mask:true
						});
						o.confirm && self._get('user.order/cancel', {
							order_id: order_id,
						}, function(res) {
							uni.hideLoading();
							uni.showToast({
								title: '操作成功',
								duration: 2000,
								icon: 'success'
							});
							self.getData();
						});
					}
				});
			},
			/*确认收货*/
			orderReceipt(order_id) {
				let self = this;
				wx.showModal({
					title: "提示",
					content: "您确定要收货吗?",
					success: function(o) {
						uni.showLoading({
							title: '正在处理',
							mask:true
						});
						o.confirm && self._post(
							'user.order/receipt', {
								order_id: order_id
							},
							function(res) {
								uni.hideLoading();
								uni.showToast({
									title: res.msg,
									duration: 2000,
									icon: 'success'
								});
								self.getData();
							}
						);
					}
				});

			},
			/*查看物流*/
			gotoExpress(order_id) {
				uni.navigateTo({
					url: '/pages/order/express/express?order_id=' + order_id,
				});
			},
			/*申请售后*/
			onApplyRefund(e) {
				uni.navigateTo({
					url: '/pages/order/refund/apply/apply?order_product_id=' + e,
				});
			},

			/*去支付*/
			payTypeFunc(payType) {
				let self = this;
				let order_id = self.order_id;
				self.isPayPopup = false;
				let pay_source = 'wx';
				// #ifdef  H5
				pay_source = 'mp';
				// #endif
				uni.showLoading({
					title: '加载中',
					mask:true
				});
				self._post('user.order/pay', {
					payType: payType,
					order_id: order_id,
				}, function(res) {
					uni.hideLoading();
					pay(res, self);
				});
			},

			/*支付方式选择*/
			onPayOrder(orderId) {
				let self = this;
				self.isPayPopup = true;
				self.order_id = orderId;
			},
		}
	};
</script>

<style scoped>
	.order-express {
		background: #ffffff;
	}

	.order-express .icon-box .iconfont {
		font-size: 50rpx;
	}

	.order-datail {
		padding-bottom: 90 rpx;
	}
	.order-datail .fight-users{ margin: 0 auto;}
	.order-datail .fight-users .user-box{ width: 80rpx; height: 80rpx; margin: 10rpx; border-radius: 50%; border: 1px dashed #CCCCCC;}
	.order-datail .fight-users{ padding: 30rpx;}
	.order-datail .fight-users .user-box image{width: 80rpx; height: 80rpx;border-radius: 50%;}
	.order-datail .fight-users .user-box .leader{ position: absolute; top: -20rpx; left: 50%; margin-left: -30rpx; width: 60rpx; height: 30rpx; line-height: 30rpx; text-align: center; color: #FFFFFF; border-radius: 30rpx; border: 1px solid #FFFFFF; background: red;}
	.order-datail .fight-users .user-box.user-who{ font-size: 50rpx; color: #999999;}
</style>
