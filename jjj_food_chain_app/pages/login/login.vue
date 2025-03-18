<template>
	<view class="login-container" :data-theme="theme()" :class="theme() || ''">
		<view class="wechatapp">
			<image class="logo-image" :src=" setting.login_logo||'/static/login-default.png'"></image>
			<view class="d-c-c f30 gray3">{{setting.name}}</view>
		</view>
		<view class="auth-title fb">{{setting.login_desc}}</view>
		<view class="auth-subtitle">授权绑定手机号 为您提供更好的服务</view>
		<view class="login-btn d-c-c">
			<!-- #ifdef MP-WEIXIN -->

			<template v-if="!mobile">
				<button v-if="wx_phone" class="theme-btn"  open-type="getPhoneNumber" @getphonenumber="getUserInfo">一键登录</button>
				<button v-else class="theme-btn" @click="UserLogin()">立即登录</button>
			</template>
			<button v-else class="theme-btn" @click="UserLogin">一键登录</button>
			<!-- #endif -->
			<!-- #ifdef MP-ALIPAY -->
			<button :disabled="!isRead" @getAuthorize="onGetAuthorize" scope="userInfo" open-type="getAuthorize" size="default"
				class="login-btn-ali theme-btn">
				支付宝一键登录
			</button>
			<!-- #endif -->
		</view>
		<view @click="isRead=!isRead" class="d-c-c gray6 mt20">
			<view class="icon iconfont icon-tijiaochenggong" :class="isRead?'active agreement':'agreement'"></view>
			我已阅读并接受<text class="theme-notice" @click="xieyi('service')">《用户协议》</text>和<text class="theme-notice"
				@click="xieyi('privacy')">《隐私政策》</text>
		</view>
		<view class="no-login-btn d-c d-c-c">
			<view class="f26 gray9" @click="onNotLogin">暂不登录</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				wx_phone:false,
				loading: true,
				background: '',
				listData: [],
				invitation_id: 0,
				user_id: '',
				mobile: true,
				isRead: false,
				setting: {
					login_desc: '',
					login_logo: '',
					name: ''
				}
			}
		},
		onShow() {
			//wx.login(); //重新登录
			
		},
		onLoad(e) {
			this.invitation_id = uni.getStorageSync('invitation_id') || 0;
			let self = this;
			this.getCodeType();
			uni.getUserInfo({
				success: function(res) {
					// console.log(res)
					const userInfo = res.userInfo;
					console.log(userInfo)
					uni.login({
						provider: 'weixin',
						success: (res) => {
							console.log('res-login', res); //获取到code
							self.code = res.code;
							// console.log('code', res.code);

							//请求登录接口
							if (res.errMsg == 'login:ok') {
								var params = {
									code: self.code,
									nickname: userInfo.nickName,
									avatar: userInfo.avatarUrl
								}
								self._post('user.user/login', {
									code: self.code,
									source: 'wx',
									invitation_id: self.invitation_id,
									referee_id: uni.getStorageSync('referee_id')
								}, result => {
									self.user_id = result.data.user_id;
									self.mobile = result.data.mobile;
								}, false, () => {
									self.loading = false;
								});
							}
						},
					});

				}
			})
		},
		methods: {
			xieyi(type) {
				this.gotoPage('/pages/webview/ue?type=' + type)
			},
			getCodeType() {
				let self = this;
				self._post(
					'index/loginSetting', {},
					res => {
						self.setting = res.data.setting;
						self.wx_phone = res.data.setting.wx_phone;
					},
				);

			},
			onNotLogin: function() {
				this.gotoPage('/pages/index/index')
			},
			UserLogin() {
				let self = this;
				if (self.loading) {
					return
				}
				if (!self.isRead) {
					uni.showToast({
						title: '请勾选并同意《隐私政策》和《用户协议》',
						icon: 'none'
					})
					return
				}
				uni.showLoading({
					title: "正在处理",
					mask: true
				});
				// 执行微信登录
				wx.login({
					success(res_login) {
						let url = 'user.user/userLogin';
						let params = {
							code: res_login.code
						}
						// 发送用户信息
						self._post(url, params, result => {
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

			},
			getUserInfo: function(e) {
				let self = this;
				if (self.loading) {
					return
				}
				if (!self.isRead) {
					uni.showToast({
						title: '请勾选并同意《隐私政策》和《用户协议》',
						icon: 'none'
					})
					return
				}
				if (e.detail.errMsg !== 'getPhoneNumber:ok') {
					return false;
				}
				uni.showLoading({
					title: "正在处理",
					mask: true
				});
				// 执行微信登录
				wx.login({
					success(res_login) {
						let url = 'user.user/bindMobile';
						let params = {
							code: res_login.code,
							user_id: self.user_id,
							encrypted_data: e.detail.encryptedData,
							iv: e.detail.iv
						}
						// 发送用户信息
						self._post(url, params, result => {
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
			},
			onGetAuthorize(res) {
				console.log('开始授权')
				let self = this;
				uni.login({
					provider: 'alipay',
					success: function(loginRes) {
						console.log("sucss")
						// console.log(loginRes)
						// // #ifdef MP-ALIPAY
						// my.getOpenUserInfo({
						// 	success: function(infoRes) {
						// 		self.aliPayLogin(loginRes, infoRes);
						// 	}
						// })
						// // #endif
						uni.getUserInfo({
							provider: 'alipay',
							success: function(infoRes) {
								self.aliPayLogin(loginRes, infoRes);
							}
						});
					},
					fail(err) {
						console.log(err)
					}
				});
			},
			aliPayLogin(loginRes, infoRes) {
				let self = this;
				console.log(loginRes); // 获取用户信息 
				console.log(infoRes);
				uni.showLoading({
					title: '登录中',
					mask: true
				});
				self._post('user.useralipay/login', {
					code: loginRes.code,
					avatar: infoRes.avatar,
					nickName: infoRes.nickName
				}, result => {
					// 记录token user_id
					console.log(result.data.token)
					uni.setStorageSync('token', result.data.token);
					uni.setStorageSync('user_id', result.data.user_id);

					// 执行回调函数
					self.gotoPage('/pages/index/index', 'redirect');
				}, false, () => {
					self.gotoPage('/pages/index/index', 'redirect');
				});
			},
		},
	}
</script>

<style lang="scss">
	page {
		background-color: #fff;
	}

	.login-container {
		padding: 30rpx;
	}

	.wechatapp {
		padding: 120rpx 0 48rpx;
		margin-bottom: 72rpx;
		text-align: center;

		.logo-image {
			margin: 0 auto;
			margin-bottom: 20rpx;
			width: 145rpx;
			height: 145rpx;
			border-radius: 50%;
		}
	}

	.m-0-a {
		margin: 0 auto;
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
		color: #333333;
		font-size: 30rpx;
		margin-top: 170rpx;
		margin-bottom: 85rpx;
		text-align: center;
	}

	.auth-subtitle {
		color: #777;
		margin-bottom: 20rpx;
		font-size: 22rpx;
		text-align: center;
	}

	.login-btn {
		padding: 0 20rpx;
	}

	.login-btn button {
		width: 592rpx;
		height: 88rpx;
		border-radius: 44rpx;
		font-size: 30rpx;
		text-align: center;
		line-height: 88rpx;
	}

	.login-btn button.login-btn-ali {
		height: 88rpx;
		line-height: 88rpx;
		background: #1678ff;
		color: #fff;
		font-size: 30rpx;
		border-radius: 999rpx;
		text-align: center;
	}

	.no-login-btn {
		position: fixed;
		bottom: 220rpx;
		left: 0;
		width: 100%;
	}

	.agreement {
		border-radius: 50%;
		width: 28rpx;
		height: 28rpx;
		border: 1px solid #ccc;
		background: #fff;
		position: relative;
		margin-right: 10rpx;
		box-sizing: border-box;
		color: #fff;
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 18rpx;
		font-weight: bold;
	}

	.agreement.active {
		@include font_color('font_color_inverse');
		@include background_color('background_color');
		@include border_color('border_color');
	}
</style>