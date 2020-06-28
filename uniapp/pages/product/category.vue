<template>
	<view class="category-wrap">
		<!-- 搜索框 -->
		<view class="index-search-box" id="searchBox">
			<view class="index-search t-c" @click="gotoSearch">
				<span class="icon iconfont icon-sousuo"></span>
				<text class="ml10">{{ searchName }}</text>
			</view>
		</view>

		<!--类别列表-->
		<view class="category-content">
			<!--type 11-->
			<view class="cotegory-type cotegory-type-1" v-if="show_type == 10">
				<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'">
					<view class="list">
						<view class="item" v-for="(item, index) in listData" :key="index" @click="gotoList(item.category_id)">
							<image :src="hasImages(item)" mode="aspectFit"></image>
						</view>
					</view>
				</scroll-view>
			</view>

			<!--type 22-->
			<view class="cotegory-type cotegory-type-2" v-if="show_type == 20">
				<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'">
					<view class="list">
						<view class="item d-s-c d-c" v-for="(item, index) in listData" :key="index" @click="gotoList(item.category_id)">
							<image :src="hasImages(item)" mode="aspectFit"></image>
							<text>{{ item.name }}</text>
						</view>
					</view>
				</scroll-view>
			</view>

			<!--type 33-->
			<view class="cotegory-type cotegory-type-3" v-if="show_type == 30">
				<view class="category-tab">
					<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'">
						<view :class="index == select_index ? 'item active' : 'item'" v-for="(item, index) in listData" :key="index" @click="selectCategory(index)">
							<text>{{ item.name }}</text>
						</view>
					</scroll-view>
				</view>
				<view class="category-content">
					<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'">
						<view class="list">
							<view class="item" v-for="(item, index) in childlist" :key="index" @click="gotoList(item.category_id)">
								<image :src="hasImages(item)" mode="aspectFit"></image>
								<text class="type-name">{{ item.name }}</text>
							</view>
						</view>
					</scroll-view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	components: {},
	data() {
		return {
			searchName: '搜索商品',
			/*展示方式*/
			show_type: 30,
			/*手机高度*/
			phoneHeight: 0,
			/*可滚动视图区域高度*/
			scrollviewHigh: 0,
			/*数据*/
			listData: [],
			childlist: [],
			active_index: 0,
			select_index: 0
		};
	},
	onLoad() {},
	mounted() {
		uni.showLoading({
			title: '加载中',
			mask:true
		});

		this.init();
		this.getData();
	},
	methods: {
		/*初始化*/
		init() {
			let _this = this;
			uni.getSystemInfo({
				success(res) {
					_this.phoneHeight = res.windowHeight;
					// 计算组件的高度
					let view = uni.createSelectorQuery().select('#searchBox');
					view.boundingClientRect(data => {
						let h = _this.phoneHeight - data.height;
						_this.scrollviewHigh = h;
					}).exec();
				}
			});
		},

		/*判断是否有图片*/
		hasImages(e) {
			if (e.images != null && e.images.file_path != null) {
				return e.images.file_path;
			} else {
				return '';
			}
		},

		/*获取数据*/
		getData() {
			let self = this;
			self._get('product.category/index', {}, function(res) {
				uni.hideLoading();
				self.listData = res.data.list;
				if (self.listData[0].child) {
					self.childlist = self.listData[0].child;
				}
				self.background = res.data.background;
				self.show_type = res.data.templet.category_style;
			});
		},

		/*选择分类*/
		selectCategory(e) {
			if (this.listData[e].child) {
				this.childlist = this.listData[e].child;
				this.select_index = e;
			}
		},

		/*跳转产品列表*/
		gotoList(e) {
			let category_id = e;
			let sortType = 'all';
			let search = '';
			let sortPrice = 0;
			uni.navigateTo({
				url: '/pages/product/list/list?category_id=' + category_id + '&sortType=' + sortType + '&search=' + search + '&sortPrice=' + sortPrice
			});
		},

		wxGetUserInfo: function(res) {
			if (!res.detail.iv) {
				uni.showToast({
					title: '您取消了授权,登录失败',
					icon: 'none'
				});
				return false;
			}
		},

		/*跳转搜索页面*/
		gotoSearch() {
			uni.navigateTo({
				url: '/pages/product/search/search'
			});
		},

		/**
		 * 设置分享内容
		 */
		onShareAppMessage() {
			let self = this;
			return {
				title: self.templet.share_title,
				path: '/pages/product/category?' + self.getShareUrlParams()
			};
		}
	}
};
</script>

<style lang="scss">
@import '@/common/mixin.scss';

.cotegory-type {
	line-height: 40rpx;
	background: #ffffff;
}

.cotegory-type image {
	width: 100%;
}

.cotegory-type-1 .list {
	padding: 0 20rpx;
}

.cotegory-type-1 .list .item {
	margin-top: 10rpx;
}

.cotegory-type-1 .list .item image {
	width: 710rpx;
	height: 710rpx;
}

.cotegory-type-2 .list,
.cotegory-type-3 .list {
	padding: 0 20rpx;
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-start;
}

.cotegory-type-2 .list .item,
.cotegory-type-3 .list .item {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
}

.cotegory-type-2 .list .item {
	padding: 0 16rpx;
	width: 200rpx;
	height: 300rpx;
	font-size: 28rpx;
}

.cotegory-type-2 .list .item image {
	width: 180rpx;
	height: 180rpx;
	margin-bottom: 20rpx;
}

.cotegory-type-3 {
	display: flex;
}

.cotegory-type-3 .category-tab {
	width: 200rpx;
	background: #FFFFFF;
	border-right: 1px solid #e3e3e3;
}

.cotegory-type-3 .category-tab .item {
	padding: 40rpx 0;
	font-size: 30rpx;
	text-align: center;
}

.cotegory-type-3 .category-tab .item.active {
	position: relative;
	background: #ffffff;
	font-weight: bold;
	color: $dominant-color;
}
.cotegory-type-3 .category-tab .item.active::after{
	position: absolute;
	content: '';
	top: 40rpx;
	bottom: 40rpx;
	left: 0;
	width: 10rpx;
	background: $dominant-color;
}

.cotegory-type-3 .category-content {
	flex: 1;
}

.cotegory-type-3 .list .item {
	width: 140rpx;
	height: 200rpx;
	margin-top: 40rpx;
	margin-right: 40rpx;
	font-size: 24rpx;
}
.cotegory-type-3 .list .item:nth-child(3n) {
	margin-right: 0;
}

.cotegory-type-3 .list .item image {
	width: 140rpx;
	height: 140rpx;
}
.cotegory-type-3 .list .item .type-name {
	display: block;
	margin-top: 20rpx;
	height: 80rpx;
	line-height: 60rpx;
	text-overflow: ellipsis;
	width: 100%;
	color: #818181;
	font-size: 30rpx;
	white-space: nowrap;
	overflow: hidden;
	text-align: center;
}
</style>
