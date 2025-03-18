<template>
	<view class="home-push-wrap">
		<Popup :show="isPopup" :width="width" :height="height" :backgroundColor="backgroundColor" :boxShadow="boxShadow"
			:padding="0" @hidePopup="hidePopupFunc">
			<view class="home-push" :class="type == 1 || type == 3 ? 'home-push-bg' : ''">
				<!--图文-->
				<template v-if="type == 1">
					<view class="image-text">
						<view class="pic">
							<image :src="form.file_path" mode="aspectFill"></image>
						</view>
						<view class="title">{{ form.title }}</view>
						<view class="des">{{ form.remark }}</view>
						<view class="btns">
							<button type="primary" @click="gotoLink(form.link.url)">{{ form.des }}</button>
						</view>
					</view>
				</template>
				<!--纯图-->
				<template v-if="type == 2">
					<view class="image-only" @click="gotoLink(form.link.url)">
						<view class="pic">
							<image :src="form.file_path" mode="aspectFill"></image>
						</view>
					</view>
				</template>
			</view>
			<view class="close-btns" @click="hidePopupFunc(true)"><text class="icon iconfont icon-guanbi"></text></view>
		</Popup>
	</view>
</template>

<script>
	import Popup from '@/components/uni-popup.vue';
	import {
		gotopage
	} from '@/common/gotopage.js';
	export default {
		components: {
			Popup
		},
		data() {
			return {
				/*是否显示*/
				isPopup: false,
				/*展示类别*/
				type: 0,
				/*宽度*/
				width: 600,
				/*高度*/
				height: 800,
				/*背景颜色*/
				backgroundColor: 'none',
				/*阴影*/
				boxShadow: 'none',
				form: {}
			};
		},
		props: ['homepush_data'],
		created() {

		},
		mounted() {
			/*获取弹出层内容*/
			this.setData();
		},
		methods: {
			/*获取弹出层内容*/
			setData() {
				this.isPopup = true;
				this.form = this.homepush_data;
				this.type = this.homepush_data.type;
			},
			/*关闭弹窗*/
			hidePopupFunc(e) {
				uni.setStorageSync('homepush_name', this.homepush_data.name);
				this.isPopup = false;
			},

			/*跳转页面*/
			gotoLink(e) {
				this.hidePopupFunc();
				this.gotoPage(e);
			},
		}
	};
</script>

<style>
	.home-push {
		width: 100%;
		border-radius: 20upx;
		overflow: hidden;
	}

	.home-push-bg {
		background: #ffffff;
		box-shadow: 0 0 30upx rgba(0, 0, 0, 0.1);
	}

	.home-push .title {
		padding: 10rpx 20rpx;
		text-align: center;
		font-size: 30rpx;
		font-weight: bold;
	}

	.home-push .des {
		padding: 20rpx;
		color: #999999;
	}

	.home-push .btns {
		padding: 20rpx;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.home-push .btns button {
		width: 200rpx;
		padding: 0;
		height: 80rpx;
		line-height: 80rpx;
		font-size: 28rpx;
		border-radius: 45rpx;
		background: #e2231a;
	}

	.home-push-wrap .image-text .pic {
		width: 600rpx;
		height: 300rpx;
	}

	.home-push-wrap .image-only .pic {
		width: 600rpx;
		height: 420rpx;
	}

	.home-push .cuopon .cuopon-title {
		width: 600rpx;
		height: 250rpx;
		overflow: hidden;
	}

	.home-push-wrap image {
		width: 100%;
		height: 100%;
	}

	.home-push .cuopon .list {
		padding: 20rpx;
	}

	.home-push .cuopon .list .item {
		margin-top: 20rpx;
		display: flex;
		justify-content: flex-start;
		background: #fff9eb;
	}

	.home-push .cuopon .item .info {
		flex: 1;
		padding: 20rpx;
		border: 1px solid #ffe4d8;
		border-radius: 16rpx;
		margin-bottom: 0;
	}

	.home-push .cuopon .item .info .num {
		color: #e2231a;
	}

	.home-push .cuopon .item .info .sub {}

	.home-push .cuopon .item .explan {
		position: relative;
		width: 120rpx;
		padding: 20rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		border: 1px solid #ffe4d8;
		border-left: 2px dashed #ffe4d8;
		border-radius: 16rpx;
		background: #ff553c;
		color: #FFFFFF;
	}

	.home-push .cuopon .item .explan .name {
		font-size: 30rpx;
		color: #fff9eb;
	}

	.home-push .cuopon .item .explan .sub {
		color: #ff9897;
	}

	.home-push .cuopon .list .explan::before,
	.home-push .cuopon .list .explan::after {
		display: block;
		position: absolute;
		content: '';
		width: 20rpx;
		height: 10rpx;
		background: #ffffff;
	}

	.home-push .cuopon .list .explan::before {
		left: -10rpx;
		top: -1px;
		border-radius: 0 0 10rpx 10rpx;
	}

	.home-push .cuopon .list .explan::after {
		left: -10rpx;
		bottom: -1px;
		border-radius: 10rpx 10rpx 0 0;
	}

	.home-push-wrap .close-btns {
		margin: 50rpx auto 0;
		width: 60rpx;
		height: 60rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
		background: none;
		border: 2px solid #ffffff;
	}

	.home-push-wrap .close-btns .iconfont {
		color: #ffffff;
	}
</style>
