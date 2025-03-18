<template>
	<view class="pay-wrap" v-if="!loading">
		<view class="top-container">
			<view class="pay-title">
				微信支付
			</view>
			<view class="price p-20-0">
				支付金额：<text class="red f40 fb">￥{{order.pay_price}}</text>
			</view>
		</view>
		<view class="p30">
			<view class="product-name mt30">
				订单号：{{order.order_no}}
			</view>
			<view class="mt30">
				<a class="pay" :href="urls"><button class="pay">确认支付</button></a>
			</view>
		</view>
	</view>

</template>


<script>
	export default {
		data() {
			return {
				urls: '',
				order_id: 0,
				order:{},
				order_type: '',
				/*加载中*/
				loading:true
			}
		},
		onLoad(e) {
			this.order_id = e.order_id;
			this.order_type = e.order_type;
			uni.showLoading({
			    title: '加载中'
			});
			/*获取数据*/
			this.getData();
		},
		methods: {
			/*获取数据*/
			getData() {
				let self = this;
				self._post('user.userweb/payH5', {
					order_id: self.order_id,
					order_type: self.order_type
				}, function(res) {
					self.order=res.data.order;
					self.urls = res.data.mweb_url + '&redirect_url=' + res.data.return_Url;
					uni.hideLoading();
					self.loading=false;
				});
			}

		}
	}
</script>

<style>
	.product-pay-list {
		text-align: left;
	}
	.product-pay-list .pic{ width: 120rpx; height: 120rpx; }

	.product-name {
		font-size: 30rpx;
	}
	
	.top-container {padding: 100rpx 0;  text-align: center; background: #eeeeee; color: #333333;}
.top-container .pay-title{font-size: 40rpx; font-weight: bold;}
	.pay-wrap .pay {
		display: block;
		height: 88rpx;
		line-height: 88rpx;
		font-size: 30rpx;
		color: #FFFFFF;
		text-align: center;
		border-radius: 8rpx;
		color: #FFFFFF;
		background: #04be01;
	}
</style>