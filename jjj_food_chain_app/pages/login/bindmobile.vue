<template>
	<view class="login-container">

		<view class="wechatapp">
			<view class="header">
				<!-- #ifdef MP-WEIXIN -->
				<open-data class="" type="userAvatarUrl"></open-data>
				<!-- #endif -->
			</view>
		</view>
		<view class="auth-title">申请获取以下权限</view>
		<view class="auth-subtitle">为了为您提供更优质的服务，我们需要获得你的手机号</view>
		<view class="login-btn">
			<!-- #ifdef MP-WEIXIN -->
			<button open-type="getPhoneNumber" class="btn-normal" @getphonenumber="getPhoneNumber">授权获取</button>
			<!-- #endif -->
		</view>
		<view class="no-login-btn">
			<button class="btn-normal" @click="onNotLogin">暂不授权</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background: '',
				listData: [],
				sessionKey: ''
			}
		},
		onLoad() {
			let self = this;
			wx.login({
				success(res) {
					// 发送用户信息
					self._post('user.user/getSession', {
						code: res.code
					}, result => {
						self.sessionKey = result.data.session_key;
					});
				}
			});
		},
		methods: {
			onNotLogin: function() {
				this.gotoPage('/pages/index/index')
			},
			getPhoneNumber(e) {
				var self = this;
				if (e.detail.errMsg !== 'getPhoneNumber:ok') {
					return false;
				}
				uni.showLoading({
					title: "正在处理",
					mask: true
				});
				wx.login({
					success(res) {
						// 发送用户信息
						self._post('user.user/bindMobile', {
							session_key: self.sessionKey,
							encrypted_data: e.detail.encryptedData,
							iv: e.detail.iv,
						}, result => {
							uni.hideLoading()
							// 执行回调函数
							uni.navigateBack();
						}, false, () => {
							uni.hideLoading();
						});
					}
				});
			},
		},
	}
</script>

<style>
	.login-container {
		padding: 30rpx;
	}

	.wechatapp {
		padding: 80rpx 0 48rpx;
		border-bottom: 1rpx solid #e3e3e3;
		margin-bottom: 72rpx;
		text-align: center;
	}

	.wechatapp .header {
		width: 190rpx;
		height: 190rpx;
		border: 2px solid #fff;
		margin: 0rpx auto 0;
		border-radius: 50%;
		overflow: hidden;
		box-shadow: 1px 0px 5px rgba(50, 50, 50, 0.3);
	}

	.auth-title {
		color: #585858;
		font-size: 34rpx;
		margin-bottom: 40rpx;
	}

	.auth-subtitle {
		color: #888;
		margin-bottom: 88rpx;
		font-size: 28rpx;
	}

	.login-btn {
		padding: 0 20rpx;
	}

	.login-btn button {
		height: 88rpx;
		line-height: 88rpx;
		background: #04be01;
		color: #fff;
		font-size: 30rpx;
		border-radius: 999rpx;
		text-align: center;
	}

	.no-login-btn {
		margin-top: 20rpx;
		padding: 0 20rpx;
	}

	.no-login-btn button {
		height: 88rpx;
		line-height: 88rpx;
		background: #dfdfdf;
		color: #fff;
		font-size: 30rpx;
		border-radius: 999rpx;
		text-align: center;
	}
</style>