<template>
	<view :data-theme="theme()" :class="theme() || ''">
		<diy style="position: relative;" :diyItems="items" @scanQrcode="scanQrcode"></diy>
	</view>
</template>

<script>
	import diy from '@/components/diy/diy.vue';
	import utils from '@/common/utils.js';
	export default {
		components: {
			diy,
		},
		data() {
			return {
				user: '',
				/*是否正在加载*/
				loadding: true,
				listData: [],
				urldata: '',
				longitude: 0,
				latitude: 0,
				delivery_set: [],
				items: [],
			};
		},
		mounted() {
			let self = this;
			//#ifdef H5
			if (this.isWeixin()) {
				this.urldata = window.location.href;
			}
			//#endif
			self.getcityData();
			self.getData();
		},
		onLoad(e) {
			let scene = utils.getSceneData(e);
			let s_id = e.shop_supplier_id ? e.shop_supplier_id : scene.sid;
			if (s_id) {
				uni.setStorageSync('selectedId', s_id);
			}
		},

		/*分享当前页面*/
		onShareAppMessage() {
			let self = this;
			return {
				title: self.page.params.share_title,
				imageUrl: self.page.params.share_img,
				path: '/pages/index/index?' + self.getShareUrlParams()
			};
		},
		methods: {
			getData() {
				let self = this;
				uni.showLoading({
					title: '加载中'
				});
				self._post(
					'index/index', {
						url: self.urldata,
						longitude: self.longitude || '0',
						latitude: self.latitude || '0'
					},
					function(res) {
						//#ifdef MP-WEIXIN
						if (res.data.getPhone) {
							uni.navigateTo({
								url: '/pages/login/bindmobile'
							});
							return;
						}
						//#endif
						uni.setStorageSync('sign', res.data.signPackage);
						if (uni.getStorageSync('selectedId') == '') {
							uni.setStorageSync('selectedId', res.data.supplier ? res.data.supplier.shop_supplier_id :
								0);
						}
						self.items = res.data.data.items;
						self.user = res.data.user;
						// 配置微信扫一扫参数
						//#ifdef H5
						if (self.urldata != '') {
							self.jweixin = self.configWxScan(res.data.signPackage);
						}
						//#endif
						uni.hideLoading();
					}
				);
			},
			/*扫一扫核销*/
			scanQrcode: function() {
				let self = this;
				//#ifdef H5
				if (this.isWeixin()) {
					// 只允许通过相机扫码
					self.jweixin.scanQRCode({
						needResult: 1,
						scanType: ['qrCode', 'barCode'],
						success: function(res) {
							window.location.href = res.resultStr;
						},
						error: function(res) {
							uni.showToast({
								title: '扫码失败，请重试'
							});
						}
					});
				} else {
					console.log('H5暂只支持公众号扫码');
				}
				//#endif
				//#ifndef H5
				// 只允许通过相机扫码
				uni.scanCode({
					onlyFromCamera: true,
					success: function(res) {
						if (res.errMsg == 'scanCode:ok') {
							self.gotoPage(res.path);
						} else {
							uni.showToast({
								title: '扫码失败，请重试'
							});
						}
					}
				});
				//#endif
			},

			/*获取定位方式*/
			getcityData() {
				let self = this;
				// 第一次，如果是公众号，则初始化微信sdk配置
				// #ifdef H5
				if (self.isWeixin()) {
					let sign = uni.getStorageSync('sign');
					if (!sign) {
						uni.showLoading({
							title: '加载中'
						});
						self._post(
							'index/index', {
								url: window.location.href
							},
							function(res) {
								uni.setStorageSync('sign', res.data.signPackage);
								sign = res.data.signPackage;
								uni.hideLoading();
								self.getWxLocation(sign);
							}
						);
					} else {
						self.getWxLocation(sign);
					}
				} else {
					self.getLocation();
				}
				// #endif
				// #ifndef H5
				self.getLocation();
				// #endif
			},
			/*授权启用定位权限*/
			onAuthorize() {
				let self = this;
				uni.openSetting({
					success(res) {
						if (res.authSetting['scope.userLocation']) {
							self.isAuthor = true;
							setTimeout(() => {
								// 获取用户坐标
								self.getLocation(res => {});
							}, 1000);
						}
					}
				});
			},
			/*获取用户坐标*/
			getLocation(callback) {
				let self = this;
				uni.getLocation({
					type: 'wgs84',
					success(res) {
						self.longitude = res.longitude;
						self.latitude = res.latitude;
						self.getData();
					},
					fail(err) {
						self.longitude = 0;
						self.latitude = 0;
						uni.showToast({
							title: '获取定位失败，请点击右下角按钮打开定位权限',
							duration: 2000,
							icon: 'none'
						});
						self.getData();
					}
				});
			},
			/* 公众号获取坐标 */
			getWxLocation(signPackage, callback) {
				let self = this;
				var jweixin = require('jweixin-module');
				jweixin.config(JSON.parse(signPackage));
				jweixin.ready(function(res) {
					jweixin.getLocation({
						type: 'wgs84',
						success: function(res) {
							self.longitude = res.longitude;
							self.latitude = res.latitude;
							self.getData();
						},
						fail(err) {
							self.longitude = 0;
							self.latitude = 0;
							self.getData();
						}
					});
				});
				jweixin.error(function(res) {
					console.log(res);
				});
			}
		}
	};
</script>

<style lang="scss">
	/* #ifdef H5 */
	page {
		height: auto;
		min-height: 100%;
	}

	/* #endif */
	.banner {
		position: relative;
		width: 100%;
		height: 600rpx;

		.bg {
			width: 100%;
			height: 600rpx;
		}

		.intro {
			position: absolute;
			top: calc(50rpx + var(--status-bar-height));
			left: 40rpx;
			color: #ffffff;
			display: flex;
			flex-direction: column;

			.greet {
				font-size: $font-size-lg;
				margin-bottom: 10rpx;
			}

			.note {
				font-size: $font-size-sm;
			}
		}
	}

	.content {
		padding: 0 30rpx;
	}

	.entrance {
		position: relative;
		margin-top: -80rpx;
		margin-bottom: 30rpx;
		border-radius: 10rpx;
		background-color: #ffffff;
		box-shadow: $box-shadow;
		padding: 40rpx 0;
		display: flex;
		align-items: center;
		justify-content: center;

		.item {
			flex: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			position: relative;

			&:nth-child(1):after {
				content: '';
				position: absolute;
				width: 1rpx;
				background-color: #ddd;
				right: 0;
				height: 100%;
				transform: scaleX(0.5) scaleY(0.8);
			}

			.icon {
				width: 120rpx;
				height: 120rpx;
				margin: 28rpx;
			}

			.title {
				font-size: 40rpx;
				color: $text-color-base;
				font-weight: 600;
			}

			.content {
				font-size: 28rpx;
				color: $text-color-grey;
				font-weight: 400;
			}
		}
	}

	.info {
		position: relative;
		margin-bottom: 30rpx;
		border-radius: 10rpx;
		background-color: #ffffff;
		box-shadow: $box-shadow;
		padding: 30rpx;
		display: flex;
		align-items: center;
		justify-content: center;

		.integral_section {
			flex: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;

			.top {
				display: flex;
				align-items: center;

				.title {
					color: $text-color-base;
					font-size: $font-size-base;
					margin-right: 10rpx;
				}

				.value {
					font-size: 44rpx;
					font-weight: bold;
				}
			}

			.bottom {
				font-size: $font-size-sm;
				color: $text-color-assist;
				display: flex;
				align-items: center;
			}
		}

		.qrcode_section {
			color: $color-primary;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-size: $font-size-sm;

			image {
				width: 40rpx;
				height: 40rpx;
				margin-bottom: 10rpx;
			}
		}
	}

	.navigators {
		width: 100%;
		margin-bottom: 20rpx;
		border-radius: 10rpx;
		background-color: #ffffff;
		box-shadow: $box-shadow;
		padding: 20rpx;
		display: flex;
		align-items: stretch;

		.left {
			width: 340rpx;
			margin-right: 20rpx;
			display: flex;
			padding: 0 20rpx;
			flex-direction: column;
			font-size: $font-size-sm;
			color: $text-color-base;
			background-color: #f2f2e6;

			.grid {
				height: 50%;
				display: flex;
			}
		}

		.right {
			width: 290rpx;
			display: flex;
			flex-direction: column;

			.tea-activity,
			.member-gifts {
				width: 100%;
				display: flex;
				padding: 20rpx;
				font-size: $font-size-sm;
				color: $text-color-base;
				align-items: center;
				position: relative;
			}

			.tea-activity {
				background-color: #fdf3f2;
				margin-bottom: 20rpx;
			}

			.member-gifts {
				background-color: #fcf6d4;
			}

			.right-img {
				flex: 1;
				position: relative;
				margin-left: 20rpx;
				margin-right: -20rpx;
				margin-bottom: -20rpx;
				display: flex;
				align-items: flex-end;

				image {
					width: 100%;
				}
			}
		}

		.mark-img {
			width: 30rpx;
			height: 30rpx;
			margin-right: 10rpx;
		}

		.yzclh-img {
			height: 122.96rpx;
			width: 214.86rpx;
		}
	}

	.member-news {
		width: 100%;
		margin-bottom: 30rpx;

		.header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 20rpx 0;

			.title {
				font-size: $font-size-lg;
				font-weight: bold;
			}

			.iconfont {
				font-size: 52rpx;
				color: $text-color-assist;
			}
		}

		.list {
			width: 100%;
			display: flex;
			flex-direction: column;

			.item {
				width: 100%;
				height: 240rpx;
				position: relative;

				image {
					width: 100%;
					height: 100%;
					border-radius: 8rpx;
				}

				.title {
					position: relative;
					font-size: 32rpx;
					font-weight: 500;
					width: 100%;
					top: -70rpx;
					left: 16rpx;
					color: #ffffff;
				}
			}
		}
	}

	.collection-box {
		position: fixed;
		width: 380rpx;
		padding: 20rpx;
		top: 20rpx;
		right: 20rpx;
		line-height: 40rpx;
		font-size: 24rpx;
		border-radius: 16rpx;
		background: #ffffff;
		border: 1px solid #eeeeee;
		box-shadow: 0 0 6rpx 0 rgba(0, 0, 0, 0.08);
		z-index: 99;
	}

	.collection-box::after {
		position: absolute;
		content: '';
		display: block;
		right: 140rpx;
		top: -15rpx;
		transform: rotate(45deg);
		width: 30rpx;
		height: 30rpx;
		transform: rotate;
		background: #ffffff;
		border-left: 1px solid #eeeeee;
		border-top: 1px solid #eeeeee;
	}

	.collection-box .point {
		width: 20rpx;
		height: 20rpx;
		font-size: 60rpx;
		line-height: 0;
		color: #666666;
	}

	.collection-box .point-big {
		font-size: 80rpx;
	}

	.collection-box .close-btn {
		position: absolute;
		padding: 0;
		right: 10rpx;
		top: 10rpx;
		width: 40rpx;
		height: 40rpx;
		line-height: 30rpx;
		background: #ffffff;
		color: #999999;
		border-radius: 50%;
	}

	.follow-gzh {
		position: fixed;
		left: 0;
		right: 0;
		bottom: calc(var(--window-bottom));
		border-radius: 16rpx;
		box-shadow: 0 0 20rpx 0 rgba(0, 0, 0, 0.1);
		background: #ffffff;
		z-index: 10;
	}

	.follow-gzh .iconfont {
		display: block;
		position: absolute;
		right: 10rpx;
		top: 10rpx;
		z-index: 99;
	}
</style>