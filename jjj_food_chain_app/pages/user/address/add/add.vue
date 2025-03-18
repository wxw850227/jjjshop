<template>
	<view class="address-form" :data-theme='theme()' :class="theme() || ''">
		<form @submit="formSubmit" @reset="formReset">
			<view class="bg-white p-0-30 f30">
				<view class="d-s-c border-b-d9">
					<text class="key-name">收货人</text>
					<input class="ml20 flex-1 f32 p-30-0" name="name" type="text" placeholder-class="grary9"
						v-model="address.name" placeholder="请输入收货人姓名" />
				</view>
				<view class="d-s-c border-b-d9">
					<text class="key-name">联系方式</text>
					<input class="ml20 flex-1 f32 p-30-0" name="phone" type="text" placeholder-class="grary9"
						v-model="address.phone" placeholder="请输入收货人手机号" />
				</view>
				<view class="d-s-c border-b-d9" @click="chooseLocation">
					<text class="key-name">详细地址</text>
					<view class="input-box flex-1">
						<input class="ml20 f32 flex-1 p-30-0" name="detail" type="text" placeholder-class="grary9"
							placeholder="请选择地址" v-model="address.detail" disabled  />
					</view>
				</view>
				<view class="d-s-c border-b-d9">
					<text class="key-name">门牌号</text>
					<textarea class="ml20 flex-1 p-30-0 lh150" name="address" :auto-height="true"
						v-model="address.address" placeholder-class="grary9" placeholder="请输入街道小区楼牌号等"></textarea>
				</view>
			</view>
			<view class="p30"><button type="primary" form-type="submit" class="theme-btn f32 mt60 addBtn">保存</button>
			</view>
		</form>
		<mpvue-city-picker ref="mpvueCityPicker" :pickerValueDefault="cityPickerValueDefault" @onConfirm="onConfirm">
		</mpvue-city-picker>
		<web-view v-if="openWb" :src="wburl" @onPostMessage="handlePostMessage"></web-view>
	</view>
</template>

<script>
	import mpvueCityPicker from '@/components/mpvue-citypicker/mpvueCityPicker.vue';
	import utils from '@/common/utils.js';
	export default {
		components: {
			mpvueCityPicker
		},
		data() {
			return {
				urls: '',
				cityPickerValueDefault: [0, 0, 0],
				selectCity: '选择省,市,区',
				province_id: 0,
				city_id: 0,
				region_id: 0,
				address: {
					latitude: '',
					longitude: '',
					detail: ''
				},
				delta: 1,
				signPackage: '',
				openWb: false,
				wburl: ''
			};
		},
		onLoad: function(options) {
			//#ifdef H5
			if (this.isWeixin()) {
				this.urls = window.location.href;
			}
			//#endif
			this.delta = options.delta || 1;
			if (options.module == 'locationPicker') {
				this.address = uni.getStorageSync('addressData');
				uni.removeStorageSync('addressData');
				this.address.detail = options.addr;
				this.address.latitude = options.latng.split(',')[0];
				this.address.longitude = options.latng.split(',')[1];
			}
		},
		methods: {
			handlePostMessage(data) {
				// console.log("接收到消息：" + JSON.stringify(data.detail));
			},
			chooseLocation(n) {
				let self = this;
				uni.chooseLocation({
					success: function(res) {
						self.address.longitude = res.longitude;
						self.address.latitude = res.latitude;
						self.address.detail = res.address;
						console.log('位置名称：' + res.name);
						console.log('详细地址：' + res.address);
						console.log('纬度：' + res.latitude);
						console.log('经度：' + res.longitude);
					},
					fail(err) {
						uni.setStorageSync('address', '');
						console.log(err)
					},
					complete(com) {
						console.log(com)
					}
				});
			},
			/*提交*/
			formSubmit: function(e) {
				let self = this;
				var formdata = e.detail.value;
				formdata.latitude = self.address.latitude;
				formdata.longitude = self.address.longitude;
				if (formdata.name == '') {
					uni.showToast({
						title: '请输入收货人姓名',
						duration: 1000,
						icon: 'none'
					});
					return false;
				}
				// utils.isTelAvailable(formdata.phone)
				if (!utils.isTelAvailable(formdata.phone)) {
					uni.showToast({
						title:'请输入正确的联系方式',
						duration: 2000,
						icon: 'none'
					});
					return;
				}

				if (formdata.latitude == 0 || formdata.longitude == 0) {
					if (formdata.detail == '') {
						uni.showToast({
							title: '请选择正确的地址',
							duration: 1000,
							icon: 'none'
						});
						return false;
					}
				}


				self._post('user.address/add', formdata, function(res) {
					console.log(self.delta)
					self.showSuccess(res.msg, function() {
						// #ifndef H5
						uni.navigateBack({
							delta: 1
						});
						// #endif
						// #ifdef H5
						history.go(-self.delta);
						// #endif
					});
				});
			},

			formReset: function(e) {
				console.log('清空数据');
			},

			/*三级联动选择*/
			showMulLinkageThreePicker() {
				this.$refs.mpvueCityPicker.show();
			},

			/*确定选择的省市区*/
			onConfirm(e) {
				this.selectCity = e.label;
				this.province_id = e.cityCode[0];
				this.city_id = e.cityCode[1];
				this.region_id = e.cityCode[2];
			}
		}
	};
</script>

<style lang="scss">
	page {
		background-color: #FFFFFF;
	}

	.address-form {
		/* border-top: 16rpx solid #f2f2f2; */
	}

	.address-form .key-name {
		width: 140rpx;
		font-size: 32rpx
	}

	.address-form .btn-red {
		height: 88rpx;
		line-height: 88rpx;
		border-radius: 44rpx;
	}

	.addBtn {
		height: 80rpx;
		line-height: 80rpx;
		border-radius: 40rpx;
	}
</style>
