<template>
	<view class="address-form o-h" :data-theme='theme()' :class="theme() || ''">
		<view class="avatar-box">
			<!-- #ifndef MP-WEIXIN -->
			<view class="info-image" @click="changeAvatarUrl">
				<image class="ava-image" :src="userInfo.avatarUrl || '/static/default.png'" mode=""></image>
				<view class="icon iconfont icon-shangchuantupian_f"></view>
			</view>
			<!-- #endif -->
			<!-- #ifdef MP-WEIXIN -->
			<button class="info-image" open-type="chooseAvatar" @chooseavatar="onChooseAvatar">
				<image class="ava-image" :src="userInfo.avatarUrl || '/static/default.png'" mode=""></image>
				<view class="icon iconfont icon-shangchuantupian_f"></view>
			</button>
			<!-- #endif -->
		</view>
		<view class="user-info bg-white f26">
			<view class="d-b-c ">
				<text class="key-name">会员ID</text>
				<view class="userinfo-text border-b">{{userInfo.user_id}}</view>
			</view>
			<view class="d-b-c">
				<text class="key-name">昵称</text>
				<input class="userinfo-input border-b" placeholder="请输入昵称" placeholder-class="gray9" type="text"
					v-model="userInfo.nickName" />
			</view>
			<view class="d-b-c">
				<text class="key-name">手机</text>
				<view class="d-s-c border-b flex-1">
					<text class="userinfo-text">{{ maskPhone(userInfo.mobile) }}</text>
				</view>
			</view>
		</view>
		<view class="sub-btn theme-btn" @click="update">保存</view>
		<view class="setup-btn" @tap="logout()">退出登录</view>
		<!-- 上传头像 -->
		<Upload v-if="isUpload" :num="1" @getImgs="getImgsFunc"></Upload>
	</view>
</template>

<script>
	import Popup from '@/components/uni-popup.vue';
	import Upload from '@/components/upload/upload.vue';
	import {
		gotopage
	} from '@/common/gotopage.js';
	export default {
		components: {
			Popup,
			Upload
		},
		data() {
			return {
				userInfo: {},
				isBirthday: false,
				birthday: '',
				imageList: [],
				newName: '',
				type: '',
				isUpload: false,
				mobileModel: {
					mobile: '',
					code: ''
				},
				passwordModel: {
					mobile: '',
					code: '',
					password: '',
					repassword: ''
				},
				isPhone: false,
				isPassword: false,
				sms_open: false
			};
		},
		onShow() {
			/*获取个人中心数据*/
			this.getData();
		},
		methods: {
			clearStorage() {
				uni.clearStorageSync();
			},
			maskPhone(phone) {
				if (!phone || phone.length !== 11) return phone; // 确保电话号码有效且长度为11位
				return phone.replace(/(\d{3})\d{4}(\d{4})/, '$1***$2');
			},
			onChooseAvatar(e) {
				let self = this;
				self.uploadFile([e.detail.avatarUrl]);
			},
			/*获取数据*/
			getData() {
				let self = this;
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				self._get('user.index/setting', {}, function(res) {
					self.userInfo = res.data.userInfo;
					uni.hideLoading();
				});
			},

			/* 修改头像 */
			changeAvatarUrl() {
				let self = this;
				self.isUpload = true;
			},
			/*上传图片*/
			uploadFile: function(tempList) {
				let self = this;
				self.imageList = [];
				let i = 0;
				let img_length = tempList.length;
				let params = {
					token: uni.getStorageSync('token'),
					app_id: self.getAppId()
				};
				uni.showLoading({
					title: '图片上传中',
					mask: true
				});
				tempList.forEach(function(filePath, fileKey) {
					uni.uploadFile({
						url: self.websiteUrl + '/index.php?s=/api/file.upload/image',
						filePath: filePath,
						name: 'iFile',
						formData: params,
						success: function(res) {
							let result = typeof res.data === 'object' ? res.data : JSON.parse(res
								.data);
							if (result.code === 1) {
								self.imageList.push(result.data);
							} else {
								self.showError(result.msg);
							}
						},
						complete: function() {
							i++;
							if (img_length === i) {
								uni.hideLoading();
								// 所有文件上传完成
								self.getImgsFunc(self.imageList);
							}
						}
					});
				});
			},
			/*获取上传的图片*/
			getImgsFunc(e) {
				let self = this;
				if (e && typeof e != 'undefined') {
					let self = this;
					self.userInfo.avatarUrl = e[0].file_path;
					self.update();
					self.isUpload = false;
				}
			},
			logout() {
				let self = this;
				self._post('/user.User/logOut', {}, res => {
					uni.removeStorageSync('token');
					uni.removeStorageSync('user_id');
					self.gotoPage('/pages/index/index');
				})
			},
			update() {
				let self = this;
				if (self.loading) {
					return;
				}
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				let params = self.userInfo;
				self.loading = true;
				self._post('user.user/updateInfo', params, function(res) {
					self.showSuccess(
						'修改成功',
						function() {
							self.loading = false;
							uni.hideLoading();
							self.getData();
						},
						function(err) {
							uni.hideLoading();
							self.loading = false;
						}
					);
				});
			},
		}
	};
</script>

<style lang="scss">
	.avatar-box {
		height: 361rpx;
		background: #FFFFFF;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;

		.info-image {
			position: relative;
			width: 198rpx;
			height: 198rpx;
			padding: 0;
			background: none;
			border: none;
			border-radius: 50%;
			overflow: initial;

			.ava-image {
				width: 198rpx;
				height: 198rpx;
				border-radius: 50%;
			}

			.icon.iconfont {
				position: absolute;
				right: 0;
				bottom: 0;
				z-index: 1;
				width: 46rpx;
				height: 46rpx;
				border-radius: 50%;
				background: rgba(#000000, .55);
				color: #fff;
				font-size: 28rpx;
				display: flex;
				justify-content: center;
				align-items: center;
			}
		}
	}

	.user-info {
		margin-top: 22rpx;
		padding: 0 28rpx 0 34rpx;
		margin-bottom: 36rpx;

		.key-name {
			width: 134rpx;
		}

		.userinfo-text,
		.userinfo-input {
			flex: 1;
			height: 98rpx;
			line-height: 98rpx;
			font-size: 26rpx;
			color: #333;
		}
	}

	.sub-btn {
		width: 692rpx;
		height: 88rpx;
		line-height: 88rpx;
		border-radius: 44rpx;
		font-size: 30rpx;
		display: flex;
		justify-content: center;
		margin: 0 auto;
		font-weight: bold;
	}

	.setup-btn {
		position: fixed;
		bottom: 43rpx;
		width: 100%;
		font-size: 26rpx;
		color: #999;
		text-align: center;
	}

	.make-item {
		height: 60rpx;
	}

	.pop-input {
		width: 100%;
		margin: 26rpx 0;
		box-sizing: border-box;
		border-bottom: 2rpx solid #d9d9d9;
		line-height: 60rpx;
	}

	.pop-input input {
		width: 100%;
		padding-left: 15rpx;

		font-size: 26rpx;
		color: #333333;
		margin: 16rpx 0;
		text-align: left;
		height: 60rpx;
		line-height: 60rpx;
	}

	.pop-input .icon.icon-guanbi {
		width: 38rpx;
		height: 38rpx;
		background-color: #999999;
		color: #ffffff;
		font-size: 22rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
		opacity: 0.8;
	}

	.info-image {
		width: 112rpx;
		height: 112rpx;
		border-radius: 10rpx;
		margin-right: 20rpx;

		image {
			width: 112rpx;
			height: 112rpx;
			border-radius: 10rpx;
		}
	}

	.btns {
		border-radius: $uni-border-radius-big;
		min-width: 220rpx;
	}



	.pop-box {
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 995;

		.pop-bg {
			position: fixed;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 1000;
			background: rgba(#000, .65);
		}

		.pop-content {
			width: 578rpx;
			background: #FFFFFF;
			border-radius: 25rpx;
			padding: 45rpx 40rpx 40rpx 40rpx;
			box-sizing: border-box;
			position: relative;
			z-index: 1001;

			.close-btn {
				width: 44rpx;
				height: 44rpx;
				background: rgba(#000000, .45);
				border-radius: 50%;
				color: #fff;
				font-size: 26rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				position: absolute;
				right: 17rpx;
				top: 17rpx;
				z-index: 1;
			}

			.pop-title {
				font-size: 30rpx;
				color: #333333;
				text-align: center;
				margin-bottom: 34rpx;
				font-weight: bold;
			}

			.pop-dsc {
				color: #666666;
				font-size: 24rpx;
				line-height: 1.5;
			}

			.pop-formitem {
				border-bottom: 1px solid #eee;
				display: flex;
				justify-content: center;
				align-items: center;
				padding-top: 38rpx;
				height: 74rpx;

				.pop-formitem-input {
					height: 74rpx;
					font-size: 26rpx;
				}

				.get-code-btn {
					min-width: 182rpx;
					height: 58rpx;
					line-height: 58rpx;
					padding: 0rpx 30rpx;
					border-radius: 50rpx;
					white-space: nowrap;
					background-color: #ffffff;
					@include font_color('notice-price');
					font-size: 26rpx;
					box-sizing: border-box;
				}

				.get-code-btn[disabled='true'] {
					background: #f7f7f7;
				}
			}

			.pop-sub-btn {
				width: 498rpx;
				height: 88rpx;
				border-radius: 44rpx;
				font-size: 30rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				margin-top: 60rpx;
				font-weight: bold;
			}
		}
	}
</style>