<template>
	<!--支付选择-->
	<view :class="isPayPopup ? 'pop-bg open cashier-pop' : 'pop-bg close cashier-pop'" @click.stop>
		<view class="pop-content" @click.stop>
			<image @click="hidePopupFunc(null)" class="close-img" src="/static/icon/close.png" mode=""></image>
			<view class="tc f32 fb">支付方式</view>
			<template v-for="(item,index) in checkedPay" :key='index'>
				<view class="cashier-item" :class="pay_type == item ? 'active' : ''" @tap="payTypeFunc(item)">
					<template v-if="item==20">
						<text class="f28 gray3">微信支付</text>
						<view class="icon-box d-c-c"><span class="icon iconfont icon-tijiaochenggong"></span></view>
					</template>
					<template v-if="item==30">
						<text class="f28 gray3">支付宝支付</text>
						<view class="icon-box d-c-c"><span class="icon iconfont icon-tijiaochenggong"></span></view>
					</template>
					<template v-if="item==10">
						<text class="f28 gray3">余额支付</text>
						<view class="icon-box d-c-c"><span class="icon iconfont icon-tijiaochenggong"></span></view>
					</template>
				</view>
			</template>
			<view class="pay-btn" @click="subFunc">立即支付</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				checkedPay: [10, 20],
				pay_type: 10
			}
		},
		props: ['isPayPopup'],
		methods: {
			hidePopupFunc(e) {
				this.$emit('close', e);
			},
			payTypeFunc(e) {
				this.pay_type = e;
			},
			subFunc() {
				this.$emit('submit', this.pay_type);
			}
		}
	}
</script>

<style>
</style>