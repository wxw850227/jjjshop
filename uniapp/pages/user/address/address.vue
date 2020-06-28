<template>
	<view v-if="!loadding">
		<view class="address-list bg-white">
			<view class="address p30 d-s-c border-b-e" v-for="(item,index) in listData" :key="index">
				<view class="radio">
					<radio :value="item.address_id+''" :checked="default_id==item.address_id+''" @click="radioChange(item.address_id)" />
				</view>
				<view class="info flex-1 ml20">
					<view class="user f34">
						<text>{{item.name}}</text>
						<text class="ml20">{{item.phone}}</text>
					</view>
					<view class="pt10 f28 gray6">
						<text class="tag" v-if="default_id==item.address_id">默认</text>
						{{item.region.province}} {{item.region.city}} {{item.region.region}} {{item.detail}}
					</view>
				</view>
				<view class="icon-box plus d-c-c ml30" @click="editAddress(item.address_id)">
					<span class="icon iconfont icon-edit"></span>
				</view>
				<view class="icon-box plus d-c-c ml30" @click="delAddress(item.address_id)">
					<span class="icon iconfont icon-lajitong"></span>
				</view>
			</view>
		</view>

		<!--添加地址-->
		<view class="foot-btns">
			<button type="primary" class="btn-red" @click="gotoPage('/pages/user/address/add/add')">新增收货地址</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				/*是否加载完成*/
				loadding:true,
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				/*数据*/
				listData: [],
				/*默认地址id*/
				default_id: '0',
				options: {}
			}
		},
		onLoad:function(options){
			this.options = options;
		},
		onShow:function(){
			uni.showLoading({
			    title: '加载中',
				mask:true
			});
			/*获取地址列表*/
			this.getData();
		},
		methods: {
			/*获取数据*/
			getData() {
				let self = this;
				let dataType = self.dataType;
				self._get('user.address/lists', {}, function(res) {
					self.listData = res.data.list;
					self.default_id = res.data.default_id + '';
					self.loadding=false;
					uni.hideLoading();
				});
			},

			/*跳转页面*/
			gotoPage(path) {
				uni.navigateTo({
					url: path
				});
			},

			/*点击单选*/
			radioChange(e) {
				let self = this;
				self.default_id = e;
				self._post('user.address/setDefault', {
					address_id: e,
				}, function(res) {
					self.options.source === 'order' && uni.navigateBack();
				});
				return false;

			},

			/*编辑地址*/
			editAddress(e) {
				uni.navigateTo({
					url: '/pages/user/address/edit/edit?address_id=' + e,
				});
			},

			/*删除地址*/
			delAddress(e) {
				let self = this;
				wx.showModal({
					title: "提示",
					content: "您确定要移除当前收货地址吗?",
					success: function(o) {
						o.confirm && self._get('user.address/delete', {
							address_id: e
						}, function(result) {
							if (result.code == 1) {
								uni.showToast({
									title: '删除成功',
									duration: 2000
								});
								self.getData();
							}

						});
					}
				});

			}
		}
	}
</script>

<style>
	.address-list {
		padding-bottom: 90rpx;
	}

	.foot-btns {
		padding: 0;
	}

	.foot-btns .btn-red {
		width: 100%;
		height: 90rpx;
		line-height: 90rpx;
		border-radius: 0;
	}
</style>
