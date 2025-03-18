<template>
	<view :data-theme="theme()" :class="theme() || ''">
		<view class="pay-success" v-if="!loadding">
			<view class="success-icon d-c-c d-c">
				<view class="d-c-c mb20">
					<text class="iconfont icon-kongxinduigou"></text>
					<text class="name ">支付成功</text>
				</view>
				<view class="f28">
					<text>实付金额:</text>
					<text class="">￥{{detail.pay_price}}</text>
				</view>
				<view class="callBox" v-if="detail.callNo">
					<view class="f28">取餐号</view>
					<view class="fb f42">{{detail.callNo}}</view>
				</view>
			</view>
			<view class="success-btns d-c-c">
				<button class="btn1" @click="goMyorder">我的订单</button>
			</view>
		</view>
	</view>

</template>

<script>
	import Popup from '@/components/uni-popup.vue';
	export default {
		data() {
			return {
				/*是否加载完成*/
				loadding: true,
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
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
				codeImage: ''
			}
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
			/*获取订单详情*/
			getData() {
				let _this = this;
				let order_id = _this.order_id;
				_this._get(
					'user.order/paySuccess', {
						order_id: order_id
					},
					function(res) {
						_this.detail = res.data.order;
						_this.codeImage = res.data.order.qrcode;
						_this.loadding = false;
						uni.hideLoading();
					}
				);
			},
			/*返回首页*/
			goHome() {
				this.gotoPage('/pages/index/index')
			},
			/*返回我的订单*/
			goMyorder() {
				this.gotoPage('/pages/order/myorder')
			}
		}
	}
</script>

<style lang="scss">
	.pay-success {
		padding: 24rpx 24rpx 48rpx 24rpx;
	}

	.pay-success .success-icon {
		display: flex;
		padding: 48rpx 48rpx 68rpx 48rpx;
		background-color: #FFFFFF;
		border-radius: $uni-border-radius-base;
		position: relative;
	}

	.pay-success .success-icon .icon-kongxinduigou {
		width: 36rpx;
		height: 36rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
		font-size: 36rpx;
		color: #333333;
		margin-right: 10rpx;
	}

	.pay-success .success-icon .name {
		// margin-top: 20rpx;
		font-size: 38rpx;
	}

	.pay-success .order-info {
		background: #FFFFFF;
	}

	.pay-success .success-btns {
		margin-top: 50rpx;
		padding: 30rpx;
	}

	.pay-success .success-btns button.btn1 {
		font-size: 30rpx;
		color: #fff;
		@include background_color('background_color');
		@include font_color('font_color_inverse');
		width: 662rpx;
		height: 92rpx;
		border-radius: 200rpx;
		position: fixed;
		left: 0;
		right: 0;
		margin: auto;
		bottom: 48rpx;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.callBox {
		width: 564rpx;
		height: 152rpx;
		// background: #F5F5F5;
		border-radius: $uni-border-radius-sm;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-direction: column;
		padding: 32rpx 0;
		box-sizing: border-box;
		margin-top: 20rpx;


	}
</style>