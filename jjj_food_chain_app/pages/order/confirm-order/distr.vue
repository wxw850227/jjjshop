<template>
	<view :class="Visible ? 'usable-distr open' : 'usable-distr close'">
		<view class="popup-bg" @click="closePopup"></view>
		<view class="main pt30" v-on:click.stop>
			<view class="distr-tit">配送方式</view>
			<view class="distr-list bor_botm" @click="radioChange(10)">
				<view>普通配送</view>
				<view class="radio">
					<radio :value="'1'" :checked="checked_id==10" /><text></text>
				</view>
			</view>
			<view v-if="deliverySetting[1]==20" @click="radioChange(20)">
				<view class="distr-list">
					<view>自提门店：</view>
					<view class="radio">
						<radio :value="'2'" :checked="checked_id==20" /><text></text>
					</view>
				</view>
				<Storeinfo ref="getShopinfoData" :extract_store="extract_store" :chooseSotr="chooseSotr" :last_extract="last_extract" style="flex: 1;"></Storeinfo>
			</view>
			<view class="distr_btn">
				<button class="btn-gcred" @click="closePopup">完成</button>
			</view>
		</view>
	</view>
</template>

<script>
	import Storeinfo from './store-info';

	export default {
		data() {
			return {
				/*是否可见*/
				Visible: false,
				checked_id: 10,
				choose_store_id: 0
			}
		},
		components: {
			Storeinfo,
		},
		props: ['isDist', 'extract_store', 'last_extract', 'deliverySetting','chooseSotr'],
		watch: {
			isDist(val) {
				this.Visible = val;
			}
		},
		onLoad(options) {
			let self = this;
			self.options = options;
		},
		methods: {
			closePopup(e) {
				if (this.checked_id == 20 && this.$props.extract_store.store_id == null) {
					uni.showToast({
						icon: 'none',
						title: '请选择自提点'
					})
				} else {
					this.$emit('close', e);
				}
			},
			radioChange(n) {
				let self = this;
				self.checked_id = n;
				self.$fire.fire('checkedfir', n);
			},
		}

	}
</script>

<style>
	.usable-distr .popup-bg {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0, 0, 0, 0.6);
		z-index: 99;
	}

	.usable-distr .main {
		position: fixed;
		width: 100%;
		bottom: 0;
		min-height: 400rpx;
		max-height: 1400rpx;
		background-color: #fff;
		transform: translate3d(0, 980rpx, 0);
		transition: transform 0.2s cubic-bezier(0, 0, 0.25, 1);
		bottom: env(safe-area-inset-bottom);
		z-index: 99;
	}

	.usable-distr.open .main {
		transform: translate3d(0, 0, 0);
	}

	.usable-distr.close .popup-bg {
		display: none;
	}

	.distr-tit {
		text-align: center;
		font-size: 39rpx;
		margin-top: 23rpx;
		margin-bottom: 70rpx;
	}

	.distr-list {
		padding: 25rpx;
		margin-bottom: 25rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;

	}

	.bor_botm {
		border-bottom: 1rpx solid #cacaca;
	}

	.distr_btn button {
		width: 90%;
		margin: 0 auto;
		margin-bottom: 30rpx;
		height: 60rpx;
		line-height: 60rpx;
		border-radius: 30rpx;
	}
</style>
