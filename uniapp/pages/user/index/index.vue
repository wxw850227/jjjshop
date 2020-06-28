<template>
	<view class="user-index" v-if="!loadding">
		<!--个人信息-->
		<view class="user-header">
			<view class="user-info">
				<view class="photo"><image :src="detail.avatarUrl" mode="aspectFill"></image></view>
				<view class="info">
					<view class="name">{{ detail.nickName }}</view>
					<view class="tel">
						<text class="f24">ID:{{ detail.user_id }}</text>
						<text class="ml20" v-if="detail.grade_id > 0">{{ detail.grade.name }}</text>
					</view>
				</view>
			</view>

			<!--我的订单-->
			<view class="my-order">
				<view class="group-hd border-b-e">
					<view class="left"><text class="min-name">我的订单</text></view>
				</view>
				<view class="d-b-c">
					<view class="item" @click="gotoPage('/pages/order/myorder/myorder?dataType=all')">
						<view class="icon-box"><span class="icon iconfont icon-quanbudingdan"></span></view>
						<text>全部订单</text>
					</view>
					<view class="item" @click="gotoPage('/pages/order/myorder/myorder?dataType=payment')">
						<view class="icon-box pr">
							<span class="icon iconfont icon-icon"></span>
							<text class="dot d-c-c" v-if="orderCount.payment != null && orderCount.payment > 0">{{ orderCount.payment }}</text>
						</view>
						<text>待付款</text>
					</view>
					<view class="item" @click="gotoPage('/pages/order/myorder/myorder?dataType=received')">
						<view class="icon-box pr">
							<span class="icon iconfont icon-daishouhuo"></span>
							<text class="dot d-c-c" v-if="orderCount.received != null && orderCount.received > 0">{{ orderCount.received }}</text>
						</view>
						<text>待收货</text>
					</view>
					<view class="item" @click="gotoPage('/pages/order/refund/index/index')">
						<view class="icon-box pr">
							<span class="icon iconfont icon-tuikuan"></span>
							<text class="dot d-c-c" v-if="orderCount.refund != null && orderCount.refund > 0">{{ orderCount.refund }}</text>
						</view>
						<text>退款/售后</text>
					</view>
				</view>
			</view>
		</view>

		<!--菜单-->
		<view class="menu-wrap">
			<view class="group-bd">
				<view class="item d-b-c" v-for="(item, index) in menus" :key="index" @click="gotoPage(item.path)">
					<view class="d-s-c type">
						<text :class="'icon iconfont ' + item.icon"></text>
						<text class="name">{{ item.name }}</text>
					</view>
					<view class="right"><text class="icon iconfont icon-jiantou"></text></view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
import uniLoadMore from '@/components/uni-load-more.vue';
export default {
	components: {},
	data() {
		return {
			/*是否推荐*/
			is_recommend: false,
			/*推荐数据*/
			recommendData: {},
			/*签到数据*/
			sign: {},
			/*是否加载完成*/
			loadding: true,
			indicatorDots: true,
			autoplay: true,
			interval: 2000,
			duration: 500,
			/*菜单*/
			menus: [
				{
					name: '收货地址',
					path: '/pages/user/address/address',
					icon: 'icon-dizhi1'
				}
			],
			detail: {
				balance: 0,
				points: 0,
				grade: {
					name: ''
				}
			},
			orderCount: {},
			coupon: 0
		};
	},
	components: {
		uniLoadMore
	},
	onLoad() {
		/*如果是支付成功页面跳转过来的，直接跳转到我的订单*/
		if (this.$status.states.type == 'ok_pay') {
			uni.navigateTo({
				url: '/pages/order/myorder/myorder'
			});
		}
	},
	onShow() {
		uni.showLoading({
			title: '加载中',
			mask:true
		});
		/*获取个人中心数据*/
		this.getData();
	},
	methods: {
		/*获取数据*/
		getData() {
			let _this = this;
			_this._get('user.index/detail', {}, function(res) {
				_this.detail = res.data.userInfo;
				_this.is_recommend = res.data.is_recommend;
				_this.recommendData = res.data.recommendData;
				_this.sign = res.data.sign;
				_this.coupon = res.data.coupon;
				_this.orderCount = res.data.orderCount;
				_this.loadding = false;
				uni.hideLoading();
			});
		},

		/*跳转页面*/
		gotoPage(path) {
			uni.navigateTo({
				url: path
			});
		}
	}
};
</script>

<style lang="scss">
.user-header {
	position: relative;
	padding: 30rpx 30rpx 150rpx;
	display: flex;
	justify-content: space-between;
	align-items: center;
	background: linear-gradient(180deg, $dominant-color, #cc120a);
}

.user-header .user-info {
	display: flex;
	justify-content: flex-start;
	align-items: center;
}
.user-header .photo{margin-left: 30rpx;}
.user-header .photo,
.user-header .photo image {
	width: 160rpx;
	height: 160rpx;
	border-radius: 50%;
}

.user-header .photo {
	border: 10rpx solid rgba(255, 255, 255, 0.2);
}

.user-header .info {
	padding-left: 30rpx;
	box-sizing: border-box;
	overflow: hidden;
	color: #ffffff;
}

.user-header .info .name {
	font-weight: bold;
	font-size: 40rpx;
}

.user-header .info .tel {
	margin-top: 10rpx;
	font-size: 26rpx;
}

.user-header .sign-box {
	padding: 0 10rpx;
	height: 50rpx;
	border: 1px solid #ffffff;
	border-radius: 25rpx;
	font-size: 24rpx;
	color: #ffffff;
}
.user-header .sign-box .iconfont {
	color: #ffffff;
}

.user-header .my-order {
	position: absolute;
	padding: 0 30rpx;
	height: 240rpx;
	right: 30rpx;
	bottom: -150rpx;
	left: 30rpx;
	box-sizing: border-box;
	border-radius: 16rpx;
	box-shadow: 0 0 6rpx 0 rgba(0, 0, 0, 0.1);
	background: #ffffff;
}

.my-order .item {
	display: flex;
	padding: 20rpx 0;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	font-size: 26rpx;
}

.my-order .icon-box,
.my-assets .icon-box {
	width: 60rpx;
	height: 60rpx;
}

.my-order .icon-box .iconfont,
.my-assets .icon-box .iconfont {
	font-size: 50rpx;
	color: #333333;
}

.my-order .icon-box .dot {
	position: absolute;
	top: 0;
	left: 30rpx;
	height: 30rpx;
	min-width: 30rpx;
	padding: 4rpx;
	border-radius: 20rpx;
	font-size: 20rpx;
	background: #f00808;
	color: #ffffff;
}

.menu-wrap {
	margin-top: 180rpx;
	margin-right: 30rpx;
	margin-left: 30rpx;
	background: #ffffff;
	border-radius: 16rpx;
	box-shadow: 0 0 8rpx 0 rgba(0, 0, 0, 0.1);
}
.menu-wrap .group-bd {
}
.menu-wrap .item {
	padding: 0 30rpx;
	height: 120rpx;
	font-size: 32rpx;
}
.menu-wrap .icon-round {
	width: 40rpx;
	height: 40rpx;
	background: red;
	border-radius: 50%;
}
.menu-wrap .item .type .iconfont {
	font-size: 50rpx;
	color: #e2231a;
}
.menu-wrap .item .name {
	margin-left: 10rpx;
}
.menu-wrap .item .iconfont {
	font-size: 24rpx;
}
</style>
