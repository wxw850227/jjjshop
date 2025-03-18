<template>
	<view>
		<view class="address-list bg-white">
			<view class="address p30 d-s-c border-b-e" v-for="(item,index) in storeList" :key="index">
				<view class="info flex-1" @click="onSelectedStore(item)">
					<view class="user f34">
						<text>{{item.store_name}}</text>
					</view>
					<view class="pt10 user f30 gray6">
						<text>{{item.phone}}</text>
					</view>
					<view class="pt10 f24 gray6">
						<text> {{item.region.province}}{{item.region.city}}{{item.region.region}}{{item.address}}</text>
					</view>
					<view>
						<text class="iconfont icon-dingwei"></text>
						<text>{{item.distance_unit}}</text>
					</view>
					<!-- 选中状态 -->
					<view v-if="item.store_id == selectedId" class="shop-item__right">
						<text class="iconfont icon-iconfontduihaocopy"></text>
					</view>
				</view>
			</view>
			<!-- 无数据提供的页面 -->
			<view v-if="!isLoading && !storeList.length">
				<view class="yoshop-notcont">
					<text class="iconfont icon-wushuju"></text>
					<text class="cont">亲，暂无自提门店哦</text>
				</view>
			</view>
		</view>
	</view>
</template>


<script>
	export default {
		data() {
			return {
				/*数据*/
				listData: [],
				isLoading: true, // 是否正在加载中
				storeList: [], // 门店列表,
				longitude: '',
				latitude: '',
				selectedId: -1,
			}
		},
		onLoad(options) {
			// 记录已选中的id
			this.selectedId = options.store_id;
			/*获取地址列表*/
			//this.getLocation();
			this.getData();
		},
		methods: {
			  /**
			   * 授权启用定位权限
			   */
			  onAuthorize() {
			    let self = this;
			    uni.openSetting({
			      success(res) {
			        if (res.authSetting["scope.userLocation"]) {
			          console.log('授权成功');
					  self.isAuthor = true;
			          setTimeout(() => {
			            // 获取用户坐标
			            self.getLocation((res) => {
			             
			            });
			          }, 1000);
			        }
			      }
			    })
			  },
			/**
			 * 获取用户坐标
			 */
			getLocation(callback) {
				let self = this;
				uni.getLocation({
					type: 'wgs84',
					success(res) {
						self.longitude = res.longitude;
						self.latitude = res.latitude;
						self.getData();
					},
					fail() {
						uni.showToast({
						    title: '获取定位失败，请点击右下角按钮打开定位权限',
						    duration: 2000
						});
						self.isAuthor=false;
					},
				})
			},

			/*获取数据*/
			getData() {
				let self = this;
				self.isLoading = true;
				self._get('store.store/lists', {
					longitude: self.longitude,
					latitude: self.latitude
				}, function(res) {
					self.isLoading = false;
					self.storeList = res.data.list;
				});
			},

			/**
			 * 选择门店
			 */
			onSelectedStore(e) {
				let self = this;
				self.selectedId = e;
				// 设置上级页面的门店id
				let pages = getCurrentPages();
				if (pages.length < 2) {
					return false;
				}
				self.$fire.fire('selectStoreId',e);
				// 返回上级页面
				// #ifndef H5
				uni.navigateBack();
				// #endif
				// #ifdef H5
				history.go(-1);
				// #endif
			},


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
