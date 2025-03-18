<template>
	<view :data-theme='theme()' :class="theme() || ''">
		<view class="tc buy-checkout-top">
			<view class="f32 mb20">待支付</view>
			<view class="redA8 f60 fb">￥{{payPrice || ''}}</view>
		</view>
		<view class="buy-checkout p-0-30">
			<template v-for="(item,index) in checkedPay">
				<view :key='index' v-if="item==20" :class="pay_type == 20 ? 'item active' : 'item'"
					@tap="payTypeFunc(20)">
					<view class="d-s-c">
						<view class="icon-box d-c-c mr10"><span class="icon iconfont icon-weixin"></span></view>
						<text class="key">微信支付：</text>
					</view>
					<view class="icon-box d-c-c"><span class="icon iconfont icon-xuanze"></span></view>
				</view>
			</template>
			<view v-if="hasBanlance && order_type!=40" class="item">
				<view class="d-s-c">
					<view class="icon-box d-c-c mr10"><span class="icon iconfont icon-yue"></span></view>
					<text class="key">余额抵扣：(剩余：{{balance}})</text>
				</view>
				<switch :color="getThemeColor()" style="transform:scale(0.7);margin-right: -20rpx;"
					:checked="balanceType" @change="switch2Change" />
			</view>
		</view>
		<view class="bottom-btn theme-btn" @click="submit">立即支付</view>
	
	</view>
</template>

<script>
	import {
		pay
	} from '@/common/pay.js';
	export default {
		data() {
			return {
				balance: '',
				balanceType: false,
				type: 0,
				loading: true,
				order_id: 0,
				// 10 普通订单 20积分兑换 30会员卡 40充值 50券包  60骑手 70团购
				order_type: 0,
				pay_type: 0,
				checkedPay: [],
				payPrice: '',
				hasBanlance: false
			}
		},
		computed: {},
		onLoad(e) {
			let self = this;
			this.order_id = e.order_id;
			if (e.order_type) {
				this.order_type = e.order_type;
			}
			this.getData()
		},
		methods: {
			getData() {
				let self = this;
				self.loading = true;
				uni.showLoading({
					title: '加载中'
				});
				let url = 'user.order/pay';
				if (self.order_type == 30) {
					url = 'user.UserCard/pay';
				}
				if (self.order_type == 40) {
					url = 'balance.plan/pay';
				}
				if (self.order_type == 50) {
					url = 'plus.package.Package/pay';
				}
				if (self.order_type == 60) {
					url = 'plus.driver.apply/pay';
				}
				if (self.order_type == 70) {
					url = 'plus.group.Order/pay';
				}
				let params = {
					order_id: self.order_id,
					pay_source: self.getPlatform()
				}
				self._get(
					url, params,
					function(res) {
						self.loading = false;
						let list = [];
						self.payPrice = res.data.payPrice;
						self.balance = res.data.balance || '';
						self.checkedPay = res.data.payTypes.payType;
						self.hasBanlance = res.data.payTypes.use_balance;
						if (self.checkedPay.length > 0) {
							self.pay_type = self.checkedPay[0]
						} else {
							self.pay_type = 0;
						}
						uni.hideLoading();
					})
			},
			switch2Change(e) {
				this.balanceType = e.detail.value;
			},
			submit() {
				let self = this;
				self.loading = true;
				uni.showLoading({
					title: '加载中'
				});
				let url = 'user.order/pay';
				if (self.order_type == 30) {
					url = 'user.UserCard/pay';
				}
				if (self.order_type == 40) {
					url = 'balance.plan/pay';
				}
				if (self.order_type == 50) {
					url = 'plus.package.Package/pay';
				}
				if (self.order_type == 60) {
					url = 'plus.driver.apply/pay';
				}
				if (self.order_type == 70) {
					url = 'plus.group.Order/pay';
				}
				let use_balance = self.balanceType == true ? 1 : 0;
				if (self.payPrice == 0) {
					use_balance = 1;
				}
				let payType = self.pay_type;
				if (payType == 10) {
					payType = 0;
				}
				let params = {
					order_id: self.order_id,
					pay_source: self.getPlatform(),
					payType: payType,
					use_balance: use_balance,
				}
				self._post(url, params,
					function(res) {
						self.loading = false;
						uni.hideLoading();
						pay(res, self, self.paySuccess, self.payError);
					})
			},
			paySuccess(result) {
				let self = this;
				if (self.order_type == 30 || self.order_type == 40 || self.order_type == 50) {
					uni.showModal({
						title: '提示',
						content: '支付成功',
						success() {
							// #ifndef H5
							uni.navigateBack({
								delta: parseInt(1)
							});
							// #endif
							// #ifdef H5
							history.go(-1);
							// #endif
						}
					})
				} else if (self.order_type == 60) {
					uni.showModal({
						title: '提示',
						content: '支付成功',
						success() {
							self.gotoPage('/pages/user/index/index')
						}
					})

				} else if (self.order_type == 70) {
					self.gotoPage('/pages/order/group/pay-success?order_id=' + result.data.order_id, 'reLaunch');
				} else {
					self.gotoPage('/pages/order/pay-success/pay-success?order_id=' + result.data.order_id, 'reLaunch');
				}
			},
			payError(result) {
				let self = this;
				let url = '/pages/order/myorder';
				if (self.order_type == 30 || self.order_type == 40 || self.order_type == 50) {
					uni.showModal({
						title: '提示',
						content: '支付失败',
						success() {
							// #ifndef H5
							uni.navigateBack({
								delta: parseInt(1)
							});
							// #endif
							// #ifdef H5
							history.go(-1);
							// #endif
						}
					})
				} else if (self.order_type == 60) {
					uni.showModal({
						title: '提示',
						content: '支付失败',
						success() {
							self.gotoPage('/pages/user/index/index')
						}
					})
				} else if (self.order_type == 70) {
					self.gotoPage('/pages/order/group/detail?order_id=' + result.data.order_id, 'reLaunch');
				} else {
					self.gotoPage('/pages/order/order-detail?order_id=' + result.data.order_id, 'redirect');
				}
			},
			payTypeFunc(n) {
				this.pay_type = n;
			}
		}
	}
</script>

<style lang="scss">
	.icon-box {
		.icon.iconfont.icon-xuanze {
			font-size: 36rpx;
		}
	}

	.buy-checkout-top {
		padding: 100rpx 0;
	}

	.buy-checkout {
		background-color: #FFFFFF;
		margin: 0 24rpx;
		border-radius: $uni-border-radius-base;

		.item {
			border-bottom: 1px solid #eee;
			padding-left: 0;
			padding-right: 0;
			margin: 0 20rpx;
		}

		.item:last-child {
			border: none;
		}
	}

	.bottom-btn {
		position: fixed;
		bottom: 0;
		@include background_color("background_color");
		@include font_color('text_color2');
		width: 710rpx;
		margin: 20rpx;
		box-sizing: border-box;
		font-size: 30rpx;
		height: 96rpx;
		border-radius: 72rpx;
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>