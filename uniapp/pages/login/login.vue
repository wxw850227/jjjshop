<template>
	<view class="login-container">

		<view class="wechatapp">
			<view class="header">
				<open-data class="" type="userAvatarUrl"></open-data>
			</view>
		</view>
		<view class="auth-title">申请获取以下权限</view>
		<view class="auth-subtitle">获得你的公开信息（昵称、头像等）</view>
		<view class="login-btn">
			<!-- <button class="btn-normal" openType="getUserInfo" lang="zh_CN" bindgetuserinfo="getUserInfo">授权登录</button> -->
			<!-- #ifdef MP-WEIXIN -->
			<button open-type="getUserInfo" class="btn-normal" @getuserinfo="wxGetUserInfo" lang="zh_CN">授权登录</button>
			<!-- #endif -->
		</view>
		<view class="no-login-btn">
			<button class="btn-normal" @click="onNotLogin">暂不登录</button>
		</view>

		<!-- <button @click="login">其他登录</button> -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background: '',
				listData: []
			}
		},
		onLoad() {

		},
		methods: {
			getData() {
				let _this = this;
				_this._get('index/index', {}, function(res) {
					_this.listData = res.data.list;
					_this.background = res.data.background;
				});
			},
			wxGetUserInfo: function(e) {
				let self = this;
				if (e.detail.errMsg !== 'getUserInfo:ok') {
					return false;
				}
				uni.showLoading({
					title: "正在登录",
					mask: true
				});
				// 执行微信登录
				uni.login({
					success(res) {
						wx.getUserInfo({
							lang: 'zh_CN',
							success: function(result) {
								// 发送用户信息
								self._post('passport/login', {
									code: res.code,
									user_info: e.detail.rawData,
									encrypted_data: encodeURIComponent(e.detail.encryptedData),
									iv: encodeURIComponent(e.detail.iv),
									signature: e.detail.signature,
									referee_id: uni.getStorageSync('referee_id'),
									source: 'wx'
								}, result => {
									// 记录token user_id
									uni.setStorageSync('token', result.data.token);
									uni.setStorageSync('user_id', result.data.user_id);
									// 执行回调函数
									uni.navigateBack();
								}, false, () => {
									uni.hideLoading();
								});
							}
						});
					}
				});
			},
			
			/*暂不登录，直接跳转首页*/
			onNotLogin(){
				uni.switchTab({
				    url: '/pages/index/index'
				});
			}
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
