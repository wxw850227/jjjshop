<template>
	<view class="article-detail" v-if="loadding">
		<view class="title fb">{{ article.article_title }}</view>
		<view class="info d-b-c f24">
			<view>
				<text class="red">{{ article.category.name }}</text>
				<text class="ml30">{{ article.create_time }}</text>
			</view>
		</view>
		<view class="article-content" v-html="article.article_content"></view>
	</view>
</template>

<script>
import utils from '@/common/utils.js';
export default {
	data() {
		return {
			/*是否加载完成*/
			loadding: false,
			indicatorDots: true,
			autoplay: true,
			interval: 2000,
			duration: 500,
			/*文章id*/
			article_id: 0,
			/*文章详情*/
			article: {
				image: {}
			}
		};
	},
	onLoad(e) {
		/*分类id*/
		this.article_id = e.article_id;
		//#ifdef H5
		//            this.url = window.location.href;
		//#endif
	},
	mounted() {
		/*获取产品详情*/
		this.getData();
	},
	methods: {
		/*获取文章详情*/
		getData() {
			let self = this;
			uni.showLoading({
				title: '加载中'
			});
			self.loading = true;
			let article_id = self.article_id;
			self._get(
				'article.article/detail',
				{
					article_id: article_id
				},
				function(res) {
					/*详情内容格式化*/
					res.data.detail.article_content =utils.format_content(res.data.detail.article_content);
					console.log(res.data.detail.article_content);
					self.article = res.data.detail;
					self.loadding = true;
					uni.hideLoading();
				}
			);
		}
	}
};
</script>

<style>
.article-detail {
	padding: 30rpx;
	background: #ffffff;
}

.article-detail .title {
	font-size: 44rpx;
}

.article-detail .info {
	padding: 40rpx 0;
	color: #999999;
}

.article-detail .article-content {
	width: 100%;
	box-sizing: border-box;
	line-height: 60rpx;
	font-size: 34 rpx;
	overflow: hidden;
}
.article-detail .article-content image,.article-detail .article-content img {
	display: block;
	max-width: 100%;
}
</style>
