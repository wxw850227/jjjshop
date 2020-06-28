<template>
	<view v-loading="loading">
		<!--diy-->
		<diy :diyItems="items"></diy>
	</view>
</template>

<script>
import diy from '@/components/diy/diy.vue';
export default {
	components: {
		diy
	},
	data() {
		return {
			/*diy数据*/
			items: [],
			/*页面设置*/
			setPage: {}
		};
	},
	onLoad() {
		uni.showLoading({
			title: '加载中',
			mask:true
		});

		this.getData();
	},
	methods: {
		/*获取数据*/
		getData() {
			let self = this;
			self._get('index/index', {}, function(res) {
				uni.hideLoading();
				self.items = res.data.items;
				self.setPage = res.data.page;
				/*设置页面*/
				uni.setNavigationBarTitle({
					title: self.setPage.params.title
				});
				uni.setNavigationBarColor({
					frontColor: self.setPage.style.titleTextColor == 'white' ? '#ffffff' : '#000000',
					backgroundColor: self.setPage.style.titleBackgroundColor,
					animation: {
						duration: 400,
						timingFunc: 'easeIn'
					}
				});
			});
		},

		/* 分享当前页面*/
		onShareAppMessage() {
			let self = this;
			return {
				title: self.setPage.params.share_title,
				path: '/pages/index/index?'
			};
		}
	}
};
</script>

<style>
.banner {
	height: 240upx;
	width: 710upx;
}

.banner image {
	width: 710rpx;
	height: 240rpx;
}

.new-people .group-bd {
	display: flex;
	justify-content: space-between;
}

.new-people .list {
	padding-right: 30rpx;
}

.new-people .list .item {
	width: 180rpx;
	text-align: center;
}

.new-people .list .price {
	font-size: 24rpx;
}

.new-people .list .price .num {
	font-size: 34rpx;
	font-weight: bold;
}

.new-people .list button {
	font-size: 24rpx;
	background: #6170ff;
}

.new-people .list image {
	width: 180rpx;
	height: 180rpx;
	border: 1px dashed #cccccc;
}

.new-people .other {
	width: 230rpx;
}

.new-people .other image {
	width: 100%;
}

.group-seckill .left .iconfont {
	margin-right: 8rpx;
	color: rgb(226, 35, 26);
	font-size: 40rpx;
}

.group-seckill .list .item {
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: center;
	width: 150rpx;
	height: 230rpx;
	border: 1px dashed #cccccc;
}

.group-seckill .list text {
	line-height: 60rpx;
	color: #e2231a;
}

.group-seckill .list image {
	width: 150rpx;
	height: 150rpx;
}

.group-hd .datetime .time {
	padding: 4rpx;
	background: #e2231a;
	color: #ffffff;
	border-radius: 4rpx;
}

.group-hd .datetime .point {
	padding: 0 10rpx;
	color: #e2231a;
}

.every-day-hard .list .item {
	width: 200rpx;
}

.every-day-hard .list image {
	width: 200rpx;
	height: 200rpx;
}

.every-day-hard .list .pic {
	position: relative;
	width: 200rpx;
	height: 200rpx;
	border: 1px dashed #cccccc;
}

.every-day-hard .list .tips {
	position: absolute;
	left: -1px;
	bottom: -1px;
	padding: 0 10rpx;
	display: flex;
	justify-content: flex-start;
	align-items: center;
	border-radius: 0 4rpx 0 0;
	color: #ffffff;
	background: #ff8a00;
	font-size: 22rpx;
}

.every-day-hard .list .tips .iconfont {
	margin-right: 6rpx;
	font-size: 24rpx;
	color: #ffffff;
}

.every-day-hard .list .tips .svg-icon {
	width: 20rpx;
	height: 20rpx;
	margin-right: 6rpx;
	color: #ffffff;
}

.every-day-hard .list .bottom-box {
	display: flex;
	height: 80rpx;
	justify-content: space-between;
	align-items: center;
}

.every-day-hard .list .bottom-box .people {
	font-size: 24rpx;
	color: #fb8138;
}

.every-day-hard .list .bottom-box .unit {
	font-size: 22rpx;
	color: #e2231a;
}

.every-day-hard .list .bottom-box .price {
	font-size: 30rpx;
	color: #e2231a;
}

.collection-box {
	position: fixed;
	width: 380rpx;
	padding: 20rpx;
	top: 20rpx;
	right: 20rpx;
	line-height: 40rpx;
	font-size: 24rpx;
	border-radius: 16rpx;
	background: #ffffff;
	border: 1px solid #eeeeee;
	box-shadow: 0 0 6rpx 0 rgba(0, 0, 0, 0.08);
}

.collection-box::after {
	position: absolute;
	content: '';
	display: block;
	right: 140rpx;
	top: -15rpx;
	transform: rotate(45deg);
	width: 30rpx;
	height: 30rpx;
	transform: rotate;
	background: #ffffff;
	border-left: 1px solid #eeeeee;
	border-top: 1px solid #eeeeee;
}

.collection-box .point {
	width: 20rpx;
	height: 20rpx;
	font-size: 60rpx;
	line-height: 0;
	color: #666666;
}

.collection-box .point-big {
	font-size: 80rpx;
}

.collection-box .close-btn {
	position: absolute;
	padding: 0;
	right: 10rpx;
	top: 10rpx;
	width: 40rpx;
	height: 40rpx;
	line-height: 30rpx;
	background: #ffffff;
	color: #999999;
	border-radius: 50%;
}

.follow-gzh {
	position: fixed;
	left: 0;
	right: 0;
	bottom: calc(var(--window-bottom));
	border-radius: 16rpx;
	box-shadow: 0 0 20rpx 0 rgba(0, 0, 0, 0.1);
	background: #ffffff;
	z-index: 10;
}

.follow-gzh .iconfont {
	display: block;
	position: absolute;
	right: 10rpx;
	top: 10rpx;
	z-index: 99;
}
</style>
