<template>
	<view class="user-index pb60" :data-theme='theme()' :class="theme() || ''">
		<view class="user-topbg">
			<!-- #ifdef APP-->
			<view class="state_top"></view>
			<!-- #endif -->
			<view class="head_top" :style="topBarTop()?'height:'+topBarTop()+'px;':''"></view>
			<view class="ww100" :style="getNavHeight()"></view>
		</view>
		<!--个人信息-->
		<view class="user-header pr">
			<!-- #ifdef APP-->
			<view class="state_top"></view>
			<!-- #endif -->
			<view class="head_top" :style="topBarTop()?'height:'+topBarTop()+'px;':''"></view>
			<view class="ww100" :style="getNavHeight()"></view>
			<view class="user-header-inner">
				<view class="photo" @click="gotoPage('/pages/user/set/set')">
					<image :src="detail.avatarUrl || '/static/login-default.png'" mode="aspectFill"></image>
				</view>
				<view class="info d-b-c flex-1">
					<view class="userName fb">{{ detail.nickName }}</view>
				</view>
			</view>
		</view>
		<!--我的订单-->
		<view class="my-wallet d-b-c">
			<view class="d-c-c d-c">
				<text class=" f28 gray3">余额</text>
				<view class=" order_num pt10">{{ isLogin?detail.balance :'--' }}</view>
			</view>
			<view class="f24 gray9" @click="gotoPage('/pages/user/my-wallet/my-wallet')">查看明细<text
					class="icon iconfont icon-jiantou1"></text></view>
		</view>
		<!--菜单-->
		<view class="menu-wrap">
			<view class="order-top">服务功能</view>
			<view class="group-bd f-w">
				<template v-for="(item, index) in menus" :key="index">
					<view class="item" v-if="item.status==1" @click="jumpPage(item.link_url)">
						<view class="icon-round d-c-c">
							<image class="icon-round" :src="item.image_url" mode="aspectFit"></image>
						</view>
						<text class="name">{{ item.title }}</text>
					</view>
				</template>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				isLogin: false,
				isloadding: true,
				/*是否加载完成*/
				loadding: true,
				/*菜单*/
				menus: [],
				detail: {
					balance: 0
				},
				urldata: '',
			};
		},
		onPullDownRefresh() {
			let self = this;
			self.getData();
		},
		onLoad(e) {
			let self = this;
			//#ifdef H5
			if (this.isWeixin()) {
				this.urldata = window.location.href;
			}
			//#endif
		},
		onShow() {
			/*获取个人中心数据*/
			this.getData();
		},
		methods: {
			/*获取数据*/
			getData() {
				let self = this;
				self.isloadding = true;
				self._get('user.index/detail', {
					url: self.urldata,
				}, function(res) {
					if (res.data.userInfo) {
						self.isLogin = true;
					}
					self.detail = res.data.userInfo;
					self.menus = res.data.menus;
					self.loadding = false;
					uni.stopPullDownRefresh();
					self.isloadding = false;
					// 配置微信扫一扫参数
					//#ifdef H5
					if (self.urldata != '') {
						self.jweixin = self.configWxScan(res.data.signPackage);
					}
					//#endif
				});
			},
			/*跳转页面*/
			jumpPage(path) {
				let self = this;
				if (self.isloadding) {
					return
				}
				if (path.startsWith('/')) {
					self.gotoPage(path);
				} else {
					self[path]();
				}
			},
			/*扫一扫核销*/
			scanQrcode: function() {
				this.gotoPage('/pages/user/scan/scan');
			},
			/*扫一扫核销*/
			receipt: function() {
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
						let ulr = res.path;
						console.log(res)
						console.log(ulr)
						if (res.errMsg == 'scanCode:ok') {
							self.gotoPage(ulr);
						} else {
							uni.showToast({
								title: '扫码失败，请重试'
							})
						}
					}
				});
				//#endif
			}
		}
	};
</script>

<style lang="scss">
	page {
		background-color: #fff;
	}

	.user-topbg {
		position: absolute;
		left: 0;
		top: 0;
		z-index: 0;
		width: 100%;
		padding-bottom: 263rpx;
		@include background_linearTr('bg_user_index1', 'bg_user_index2', 'bg_user_index3', -180deg, 0, 81%, 100%);
	}

	.user-header {
		position: relative;
		position: relative;
		z-index: 1;
		overflow: hidden;
	}

	.user-header .user-header-inner {
		position: relative;
		padding: 30rpx 24rpx 26rpx 24rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
		overflow: hidden;
		z-index: 1;
	}

	.user-header .user-info {
		display: flex;
		justify-content: flex-start;
		align-items: center;
	}

	.user-header .photo,
	.user-header .photo image {
		width: 82rpx;
		height: 82rpx;
		border-radius: 50%;
	}

	.user-header .info {
		padding-left: 16rpx;
		box-sizing: border-box;
		overflow: hidden;
	}

	.user-header .info .userName {
		font-size: 32rpx;
		color: #333;
	}

	.menu-wrap {
		// margin: 0 24rpx;
		// padding: 0 35rpx;
		position: relative;
		z-index: 1;

		.group-bd {
			display: flex;
			justify-content: flex-start;
			align-items: flex-start;
			padding-bottom: 28rpx;
		}

		.item {
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			width: 25%;
			height: 150rpx;
			font-size: 24rpx;
			margin: 28rpx 0;

			.name {
				margin-top: 19rpx;
			}

			.iconfont {
				font-size: 40rpx;
				color: #ffffff;
			}

		}

		.icon-round {
			width: 92rpx;
			height: 92rpx;
			color: #ffffff;
		}
	}



	.order-top {
		display: flex;
		justify-content: space-between;
		align-items: center;
		box-sizing: border-box;
		font-size: 30rpx;
		color: #333;
		margin: 0 24rpx;
	}


	.my-wallet {
		margin: 0 24rpx;
		box-sizing: border-box;
		border-radius: 25rpx;
		background: #ffffff;
		margin-bottom: 40rpx;
		position: relative;
		height: 148rpx;
		padding: 40rpx 26rpx 30rpx 25rpx;
		box-sizing: border-box;
		box-shadow: 0rpx 5rpx 12rpx 0rpx rgba(6, 0, 1, 0.03);
		.order_num {
			font-size: 38rpx;
			color: #111;
			font-weight: bold;
		}

		.iconfont.icon-jiantou1 {
			font-size: 24rpx;
			color: #999;
		}

	}

	.head_top {
		width: 100%;
		height: var(--status-bar-height);
		height: 24rpx;
	}
</style>