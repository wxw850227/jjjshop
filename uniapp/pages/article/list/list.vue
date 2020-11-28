<template>
	<view class="article-list-wrap">
		<view class="top-tabbar">
			<scroll-view scroll-x="true" scroll-with-animation="true">
				<view class="type-list d-s-c">
					<view :class="type_active == 0 ? 'tab-item  active' : 'tab-item '"  @click="tabTypeFunc(0)">
						全部
					</view>
					<view :class="type_active == item.category_id ? 'tab-item  active' : 'tab-item '" v-for="(item, index) in categorys" :key="index" @click="tabTypeFunc(item.category_id)">
						{{ item.name }}
					</view>
				</view>
			</scroll-view>
		</view>

		<template>
			<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'" lower-threshold="50" @scrolltolower="scrolltolowerFunc">
				<view class="article-list">
					<view class="item" v-for="(item, index) in listData" :key="index" @click="gotoDetail(item.article_id)">
						<view class="info">
							<view class="title">{{ item.article_title }}</view>
							<view class="summary">{{ item.dec }}</view>
							<view class="datatime d-s-c">
								<text>{{ item.create_time }}</text>
								<text class="icon iconfont icon-chakan ml30"></text>
								<text class="ml10">{{ item.actual_views }}</text>
							</view>
						</view>
						<view class="pic" v-if="item.image != null"><image :src="item.image.file_path" mode="aspectFill"></image></view>
					</view>
				</view>

				<!-- 没有记录 -->
				<view class="d-c-c p30" v-if="listData.length == 0 && !loading">
					<text class="iconfont icon-wushuju"></text>
					<text class="cont">亲，暂无相关记录哦</text>
				</view>
				<uni-load-more v-else :loadingType="loadingType"></uni-load-more>
			</scroll-view>
		</template>
	</view>
</template>

<script>
import uniLoadMore from '@/components/uni-load-more.vue';
export default {
	components: {
		uniLoadMore
	},
	data() {
		return {
			/*是否加载完成*/
			loading: true,
			/*手机高度*/
			phoneHeight: 0,
			/*可滚动视图区域高度*/
			scrollviewHigh: 0,
			/*数据列表*/
			listData: [],
			/*是否有更多*/
			no_more: null,
			/*一页多少条*/
			list_rows: 10,
			/*当前第几页*/
			page: 1,
			/*分类数据*/
			categorys: [],
			/*当前选中的类别*/
			type_active: 0
		};
	},
	computed: {
		/*加载中状态*/
		loadingType() {
			if (this.loading) {
				return 1;
			} else {
				if (this.listData.length != 0 && this.no_more) {
					return 2;
				} else {
					return 0;
				}
			}
		}
	},
	mounted() {
		this.init();
		/*获取分类*/
		this.getCategory();
		/*获取新闻列表*/
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
					let view = uni.createSelectorQuery().select('.top-tabbar');
					view.boundingClientRect(data => {
						let h = _this.phoneHeight - data.height;
						_this.scrollviewHigh = h;
					}).exec();
				}
			});
		},
		
		/*获取文章分类*/
		getCategory() {
			let self = this;
			self._get('article.article/category', {}, function(res) {
				self.categorys = res.data.category;
			});
		},

		/*tab切换*/
		tabTypeFunc(e) {
			if(e!=this.type_active){
				this.type_active=e;
				this.page=1;
				this.listData=[];
				this.getData();
			}
		},

		/*获取数据*/
		getData() {
			let self = this;
			let page = self.page;
			let list_rows = self.list_rows;
			self.loading = true;
			uni.showLoading({
				title: '加载中'
			});
			self._get(
				'plus.article.article/index',
				{
					page: page || 1,
					list_rows: list_rows,
					category_id:self.type_active
				},
				function(res) {
					self.listData = self.listData.concat(res.data.list.data);
					self.last_page = res.data.list.last_page;
					if (res.data.list.last_page <= 1) {
						self.no_more = true;
					}
					self.loading = false;
					uni.hideLoading();
				}
			);
		},

		/*可滚动视图区域到底触发*/
		scrolltolowerFunc() {
			let self = this;
			self.bottomRefresh = true;
			self.page++;
			self.loading = true;
			if (self.page > self.last_page) {
				self.loading = false;
				self.no_more = true;
				return;
			}
			self.getData();
		},

		/*跳转文章详情*/
		gotoDetail(e) {
			uni.navigateTo({
				url: '/pages/article/detail/detail?article_id=' + e
			});
		}
	}
};
</script>

<style>
.article-list-wrap .type-list .tab-item {
	padding: 0 30rpx;
	font-size: 34rpx;
	height: 86rpx;
	line-height: 86rpx;
	white-space: nowrap;
	border-bottom: 4rpx solid #FFFFFF;
}
.article-list-wrap .type-list .tab-item.active{
	border-bottom: 4rpx solid #E2231A;
	margin-bottom: 0;
}

.article-list {
	background: #ffffff;
}

.article-list .item {
	padding: 30rpx;
	display: flex;
	justify-content: center;
	align-items: center;
	border-bottom: 1px solid #e3e3e3;
}

.article-list .item .info {
	flex: 1;
	overflow: hidden;
}

.article-list .item .title {
	font-size: 36rpx;
}

.article-list .item .summary {
	margin-top: 20rpx;
	font-size: 28rpx;
	color: #999999;
}

.article-list .item .title,
.article-list .item .summary {
	display: -webkit-box;
	overflow: hidden;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
}

.article-list .item .pic {
	padding-left: 30rpx;
}

.article-list .item .pic,
.article-list .item .pic image {
	width: 160rpx;
	height: 160rpx;
}

.article-list .item .datatime {
	margin-top: 20rpx;
	font-size: 24rpx;
	color: #999999;
}
</style>
