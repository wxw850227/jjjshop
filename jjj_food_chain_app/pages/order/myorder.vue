<template>
	<view :data-theme="theme()" :class="theme() || ''">
		<view class="top-tabbar d-a-c">
			<view :class="dataType == 1 ? 'tab-item active' : 'tab-item'" @click="orderStateFunc(1)">当前订单</view>
			<view :class="dataType == 2 ? 'tab-item active' : 'tab-item'" @click="orderStateFunc(2)">历史订单</view>
		</view>
		<!--列表-->
		<view class="order-list">
			<view class="item" v-for="(item, index) in listData" :key="index" @click.stop="gotoOrder(item.order_id)">
				<view class="d-b-c pb14">
					<text class="order-tips">{{item.order_label}}</text>
					<view class="item-dianpu flex-1">
						<view class="item-d-l mr10">
							<text class="gray3 f28 fb text-ellipsis"
								v-if="item.supplier">{{ item.supplier.name }}</text>
						</view>
					</view>
					<view class="state">
						<text class="gray9 f24">{{ item.state_text }}</text>
					</view>
				</view>
				<!--多个商品显示-->
				<view class="product-list pr d-b-c">
					<view class="o-a flex-1">
						<view class="list d-s-c pr100">
							<template v-for="(img, num) in item.product">
								<view class="cover mr20" :key="num" v-if="num<=2">
									<image :src="img.image ? img.image.file_path : ''" mode="aspectFit"></image>
									<view class="mt10 tc f24 text-ellipsis">{{ img.product_name }}</view>
								</view>
							</template>
						</view>
					</view>
					<view>
						<view class="theme-price f30">￥<text class="f36">{{item.pay_price}}</text></view>
						<view class="f20 gray6 tr">共{{item.productNum}}件</view>
					</view>
				</view>
				<text class="shop-name flex-1">下单时间：{{ item.create_time }}</text>
				<view class="order-bts" v-if="item.order_status.value == 10">
					<!-- 未支付取消订单 -->
					<button class="default" @click.stop="cancelOrder(item.order_id)" type="default"
						v-if="item.pay_status.value == 10&&item.order_source != 30">取消订单</button>
					<!-- 订单付款 -->
					<template v-if="item.pay_status.value == 10&&item.order_source != 30">
						<button class="theme-btn fb" @click.stop="onPayOrder(item.order_id)">立即支付</button>
					</template>
					<template v-if="item.pay_status.value == 10&&item.order_source == 30&&item.supplier.pay_open==1">
						<button class="theme-btn fb" @click.stop="onPayOrder(item.order_id)">立即支付</button>
					</template>
				</view>
			</view>
			<view class="d-c-c d-c p30" v-if="listData.length == 0 && !loading">
				<image style="width: 268rpx;height: 263rpx;margin-top: 123rpx;" src="/static/no-order.png"
					mode="aspectFill"></image>
				<view class="f26 gray9">暂无记录</view>
				<view><button class="btn-normal theme-btn" @click="gotoPage('/pages/index/index')">立即点单</button></view>
			</view>
			<uni-load-more v-else :loadingType="loadingType"></uni-load-more>
		</view>
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
				/*顶部刷新*/
				topRefresh: false,
				/*数据*/
				listData: [],
				/*数据类别*/
				dataType: 1,
				/*订单id*/
				order_id: 0,
				/*最后一页码数*/
				last_page: 0,
				/*当前页面*/
				page: 1,
				/*每页条数*/
				list_rows: 10,
				/*有没有等多*/
				no_more: false,
				/*是否正在加载*/
				loading: true,
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
		onShow() {
			this.initData();
			this.getData();
		},
		onReachBottom() {
			let self = this;
			if (self.page < self.last_page) {
				self.page++;
				self.getData();
			}
			self.no_more = true;
		},
		methods: {
			initData() {
				let self = this;
				self.page = 1;
				self.listData = [];
				self.no_more = false;
			},
			/*状态切换*/
			orderStateFunc(e) {
				let self = this;
				if (self.loading) {
					return;
				}
				if (self.dataType != e) {
					self.page = 1;
					self.loading = true;
					self.listData = [];
					self.dataType = e;
					self.getData();
				}
			},
			/*获取数据*/
			getData() {
				if (!this.getUserId()) {
					this.loading = false;
					return;
				}
				let self = this;
				self.loading = true;
				self._get(
					'user.order/lists', {
						dataType: self.dataType,
						page: self.page,
						list_rows: self.list_rows
					},
					function(res) {
						self.loading = false;
						self.listData = self.listData.concat(res.data.list.data);
						self.last_page = res.data.list.last_page;
						if (res.data.list.last_page <= 1) {
							self.no_more = true;
						} else {
							self.no_more = false;
						}
					}
				);
			},
			/*跳转页面*/
			gotoOrder(e) {
				this.gotoPage('/pages/order/order-detail?order_id=' + e);
			},

			/*支付方式选择*/
			onPayOrder(orderId) {
				let self = this;
				let pages = '/pages/order/cashier?order_type=10&order_id=' + orderId;
				self.gotoPage(pages, 'reLaunch');
			},

			/*确认收货*/
			orderReceipt(order_id) {
				let self = this;
				uni.showModal({
					title: '提示',
					content: '您确定要收货吗?',
					success: function(o) {
						if (o.confirm) {
							uni.showLoading({
								title: '正在处理',
								mask: true
							});
							self._post(
								'user.order/receipt', {
									order_id: order_id
								},
								function(res) {
									uni.hideLoading();
									uni.showToast({
										title: res.msg,
										duration: 2000,
										icon: 'success'
									});
									self.listData = [];
									self.getData();
								}
							);
						} else {
							uni.showToast({
								title: '取消收货',
								duration: 1000,
								icon: 'none'
							});
						}
					}
				});
			},

			/*取消订单*/
			cancelOrder(e) {
				let self = this;
				let order_id = e;
				uni.showModal({
					title: '提示',
					content: '您确定要取消吗?',
					success: function(o) {
						if (o.confirm) {
							uni.showLoading({
								title: '正在处理',
								mask: true
							});
							self._get(
								'user.order/cancel', {
									order_id: order_id
								},
								function(res) {
									uni.hideLoading();
									uni.showToast({
										title: '操作成功',
										duration: 2000,
										icon: 'success'
									});
									self.listData = [];
									self.getData();
								}
							);
						}
					}
				});
			},
			gohome() {
				this.gotoPage('/pages/index/index');
			}
		}
	};
</script>

<style lang="scss">
	.icon-guanbi1.icon {
		position: absolute;
		right: 26rpx;
		top: 26rpx;
		z-index: 1;
		font-size: 30rpx;
		color: #333;
	}

	.pop-code {
		width: 280rpx;
		height: 280rpx;
		margin-bottom: 18rpx;
		margin-top: 28rpx;
	}

	.order-list .order-head .state-text {
		padding: 10rpx 12rpx;
		margin-right: 21rpx;
		border-radius: 4rpx;
		background: #ffe7e4;
		font-size: 22rpx;
		@include font_color('font_color');
	}

	.shop-name {
		font-size: 24rpx;
		font-family: PingFang SC;
		font-weight: 500;
		color: #999;
	}

	.order-list {
		padding: 28rpx 30rpx;
	}

	.order-list .item {
		margin-bottom: 22rpx;
		padding: 28rpx 22rpx;
		background: #ffffff;
		opacity: 1;
		border-radius: 20rpx;
	}

	.order-list .product-list,
	.order-list .one-product {
		padding: 30rpx 0 27rpx 0;
	}

	.one-product .pro-info {
		padding: 0 21rpx 0 37rpx;
		display: -webkit-box;
		width: 361rpx;
		overflow: hidden;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		font-size: 26rpx;
		color: #333333;
	}

	.order-list .cover {
		height: 100%;
		text-align: start;
		font-size: 24rpx;
	}

	.order-list .cover image {
		width: 148rpx;
		height: 148rpx;
		border-radius: 8rpx;
	}

	.order-list .total-count {
		padding-left: 20rpx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: flex-end;
	}

	.order-list .total-count .price {
		color: #ff5800;
		font-size: 32rpx;
		font-family: PingFang SC;
		font-weight: 800;
	}

	.total-count .count {
		font-size: 20rpx;
		font-family: PingFang SC;
		font-weight: 400;
		line-height: 28rpx;
		color: #282828;
		opacity: 0.5;
	}

	.order-list .product-list {
		display: flex;
		justify-content: space-between;
		align-items: center;
		// width: 544rpx;
		margin: 0rpx 0 20rpx 0;
		padding: 0;
	}

	.order-list .product-list .list {
		overflow-x: auto;
	}

	.product-list .total-count {
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		background: rgba(255, 255, 255, 0.9);
	}

	.product-list .total-count .left-shadow {
		position: absolute;
		top: 0;
		bottom: 0;
		left: -24rpx;
		width: 24rpx;
		overflow: hidden;
	}

	.product-list .total-count .left-shadow::after {
		position: absolute;
		top: 0;
		bottom: 0;
		width: 24rpx;
		right: -12rpx;
		display: block;
		content: '';
		background-image: radial-gradient(rgba(0, 0, 0, 0.2) 10%, rgba(0, 0, 0, 0.1) 40%, rgba(0, 0, 0, 0) 80%);
	}

	.order-list .order-bts {
		display: flex;
		justify-content: flex-end;
		align-items: center;
	}

	.order-list .order-bts button {
		margin: 0;
		padding: 0 27rpx;
		height: 60rpx;
		line-height: 60rpx;
		margin-left: 20rpx;
		font-size: 28rpx;
		border: 1px solid;
		border-radius: 80rpx;
		background: #ffffff;
		white-space: nowrap;
		font-family: PingFang SC;
		box-sizing: border-box;
	}

	.order-list .order-bts button::after {
		display: none;
	}

	.order-list .order-bts button.default {
		border: 1px solid #D2D2D2;
		font-size: 28rpx;
		color: #282828;
	}

	.order-list .order-bts button.btn-red {
		@include background_color('background_color');
		font-size: 28rpx;
		font-family: PingFang SC;
		@include text_color('text_color');
		border: 1px solid;
		@include border_color('border_color');
	}

	.buy-checkout {
		width: 100%;
	}

	.buy-checkout .item {
		min-height: 50rpx;
		line-height: 50rpx;
		padding: 20rpx;
		display: flex;
		justify-content: space-between;
		font-size: 28rpx;
	}

	.buy-checkout .iconfont.icon-weixin {
		color: #04be01;
		font-size: 50rpx;
	}

	.buy-checkout .iconfont.icon-yue {
		color: #f0de7c;
		font-size: 50rpx;
	}

	.buy-checkout .item.active .iconfont.icon-xuanze {
		color: #04be01;
	}

	.item-dianpu {
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 24rpx;
		line-height: 30rpx;
	}

	.item-dianpu .icon-jiantou {
		font-size: 24rpx;
		color: #333333;
	}

	.item-d-l {
		display: flex;
	}

	.icon-dianpu1 {
		margin-right: 20rpx;
		color: #333333;
		font-size: 32rpx;
	}

	.bg-grayf2 {
		padding: 8rpx;
		background-color: #f2f2f2;
		border-radius: 8rpx;
	}

	.tab-item-top {
		width: 187rpx;
		height: 60rpx;
		color: #ffffff;
		@include font_color('font_color');
		font-size: 32rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		border: 2rpx solid;
		@include border_color('border_color');
		box-sizing: border-box;
	}

	.tab-item {
		font-size: 28rpx;
		font-family: PingFang SC;
		line-height: 42rpx;
		color: #282828;
		opacity: 0.8;
	}

	.tab-item.active {
		font-size: 28rpx;
		font-family: PingFang SC;
		font-weight: bold;
		line-height: 42rpx;
		color: #282828;
	}

	.tab-item-top.left {
		border-radius: 30rpx 0px 0px 30rpx;
	}

	.tab-item-top.right {
		border-radius: 0px 30rpx 30rpx 0px;
	}

	.tab-item-top.active {
		@include background_color('background_color');
		color: #ffffff;
		@include font_color('font_color_inverse');
		//@include text_color('text_color');
	}

	.delivery_type {
		width: 120rpx;
		height: 50rpx;
		@include font_color('font_color');
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 8rpx;
		border: 1px solid;
		@include border_color('border_color');
		box-sizing: border-box;
		background-color: #ffffff;
	}

	.delivery_type.active {
		@include background_color('background_color');
		color: #ffffff;
	}

	.head_top {
		position: relative;
		width: 100%;
		height: 30px;
		line-height: 30px;
		color: #ffffff;
	}

	.sup_img {
		width: 44rpx;
		height: 44rpx;
		background: rgba(0, 0, 0, 0);
		opacity: 1;
		border-radius: 50%;
		margin-right: 10rpx;
		display: block;
	}

	.btn-normal {
		width: 302rpx;
		height: 93rpx;
		border-radius: 65rpx;
		margin-top: 40rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 36rpx;
		font-weight: 600;
		@include font_color('font_color_inverse');
	}

	.pop-bg {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100vh;
		background: rgba(0, 0, 0, 0.5);
		z-index: 1000;

		.pop-content {
			position: fixed;
			z-index: 1001;
			bottom: 0;
			width: 100%;
			height: 719rpx;
			padding: 40rpx 24rpx 0 23rpx;
			box-sizing: border-box;
			transform: translate3d(0, 0, 0);
			transition: transform 0.2s cubic-bezier(0, 0, 0.25, 1);
			background-color: #ffffff;
			border-radius: 15rpx 15rpx 0rpx 0rpx;

			.icon.icon-guanbi {
				background: #dedede;
				border-radius: 50%;
				color: #ffffff;
				position: absolute;
				right: 26rpx;
				top: 40rpx;
				font-size: 22rpx;
				display: flex;
				width: 40rpx;
				height: 40rpx;
				justify-content: center;
				align-items: center;
			}
		}
	}

	.close-img {
		width: 32rpx;
		height: 32rpx;
		position: absolute;
		right: 32rpx;
		top: 32rpx;
	}

	.pop-bg.close {
		// display: none;
		height: 0;

		.pop-content {
			transform: translate3d(0, 2000rpx, 0);
		}
	}

	.cashier-item {
		height: 89rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-bottom: 1px solid #eeeeee;

		.icon-box {
			width: 38rpx;
			height: 38rpx;
			border: 1px solid #dddddd;
			border-radius: 50%;

			.icon-tijiaochenggong {
				font-size: 26rpx;
				color: #ffffff;
			}
		}

		.icon-box.border {
			border-radius: 50%;
		}
	}

	.cashier-item.active .icon-box {
		border: 1px solid #72deed;
		@include border_color('border_color');
		@include background_color('background_color');
	}

	.pay-btn {
		width: 750rpx;
		height: 96rpx;
		font-size: 32rpx;
		color: #ffffff;
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		left: 0;
		bottom: 0;
		@include background_color('background_color');
	}

	.order-tips {
		@include font_color('full-reduction-font');
		@include background_color('full-reduction-bg');
		width: 72rpx;
		height: 42rpx;
		border-radius: 5rpx;
		font-size: 24rpx;
		line-height: 42rpx;
		text-align: center;
		display: inline-block;
		margin-right: 7rpx;
	}
</style>