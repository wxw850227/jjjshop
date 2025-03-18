<template>
	<view class="login-container" :data-theme="theme()" :class="theme() || ''">
		<view class="skip" @click="gotoPage('/pages/index/index')">跳过→</view>
		<view class="" v-if="is_login!=0">
			<view class="login_topbpx">
				<view class="login_tit" v-if="is_login==2">快速注册</view>
				<view class="login_tit" v-if="is_login==1">账号登录</view>
			</view>
			<view class="group-bd">
				<view class="form-level d-s-c">
					<input class="login-input" :adjust-position="false" type="text" v-model="formData.mobile"
						placeholder="请填写手机号" />
				</view>
				<view class="form-level d-s-c">
					<input class="login-input" :adjust-position="false" type="text" password="true"
						v-model="formData.password" placeholder="请输入密码" />
				</view>
			</view>
		</view>
		<view @click="isRead=!isRead" class="d-s-c gray6 mt30">
			<view class="icon iconfont icon-tijiaochenggong" :class="isRead?'active agreement':'agreement'"></view>
			我已阅读并接受<text class="theme-notice" @click="xieyi('service')">《用户协议》</text>和<text class="theme-notice"
				@click="xieyi('privacy')">《隐私政策》</text>
		</view>
		<button v-if="is_login==2" class="theme-btn sub-btn" @click="formSubmit">立即注册</button>
		<button v-if="is_login==1" class="theme-btn sub-btn" @click="formSubmit">立即登录</button>
		<view class="f30 gray6 d-c-c">
			<view v-if="is_login ==1 " @click="is_login =2">快速注册</view>
			<view v-if="is_login ==2" @click="is_login =1">账号登录</view>
		</view>

	</view>
</template>

<script>
	export default {
		data() {
			return {
				/*表单数据对象*/
				formData: {
					mobile: '',
					password: '',
				},
				is_login: 2,
				isRead: false
			};
		},
		methods: {
			xieyi(type) {
				this.gotoPage('/pages/webview/ue?type=' + type)
			},
			/*提交*/
			formSubmit() {
				let self = this;
				if (!self.isRead) {
					uni.showToast({
						title: '请先阅读并接受用户协议及隐私政策',
						duration: 2000,
						icon: 'none'
					});
					return
				}
				let formdata = {
					mobile: self.formData.mobile,
					password: self.formData.password
				}
				let url = ''
				if (!/^1(3|4|5|6|7|8|9)\d{9}$/.test(self.formData.mobile)) {
					uni.showToast({
						title: '手机有误,请重填！',
						duration: 2000,
						icon: 'none'
					});
					return;
				}
				if (self.is_login == 2) {
					if (self.formData.password.length < 6) {
						uni.showToast({
							title: '密码至少6位数！',
							duration: 2000,
							icon: 'none'
						});
						return;
					}
					url = 'user.useropen/register'
				} else {
					if (self.formData.password == '') {
						uni.showToast({
							title: '密码不能为空！',
							duration: 2000,
							icon: 'none'
						});
						return;
					}
					url = 'user.useropen/phonelogin'
				}

				uni.showLoading({
					title: '正在提交',
					mask: true
				});
				self._post(
					url,
					formdata,
					result => {
						// 记录token user_id
						uni.setStorageSync('token', result.data.token);
						uni.setStorageSync('user_id', result.data.user_id);
						// 获取登录前页面
						let url = '/' + uni.getStorageSync('currentPage');
						let pageOptions = uni.getStorageSync('currentPageOptions');
						if (Object.keys(pageOptions).length > 0) {
							url += '?';
							for (let i in pageOptions) {
								url += i + '=' + pageOptions[i] + '&';
							}
							url = url.substring(0, url.length - 1);
						}
						// 执行回调函数
						self.gotoPage(url, 'redirect');
					},
					false,
					() => {
						uni.hideLoading();
					}
				);
			},
		}
	};
</script>

<style lang="scss" scoped>
	@import './login.scss';
</style>