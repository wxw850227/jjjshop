<template>
	<view>
		<view class="top-tabbar">
			<view :class="state_active==0?'tab-item active':'tab-item'" @click="stateFunc(0)">
				全部订单
			</view>
			<view :class="state_active==1?'tab-item active':'tab-item'" @click="stateFunc(1)">
				待付款
			</view>
			<view :class="state_active==2?'tab-item active':'tab-item'" @click="stateFunc(2)">
				待发货
			</view>
			<view :class="state_active==3?'tab-item active':'tab-item'" @click="stateFunc(3)">
				待收货
			</view>
			<view :class="state_active==4?'tab-item active':'tab-item'" @click="stateFunc(4)">
				待评价
			</view>
		</view>

		<!--列表-->
		<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'" lower-threshold="50"
		 @scrolltolower="scrolltolowerFunc">
			<view :class="topRefresh?'top-refresh open':'top-refresh'">
				<view class="circle" v-for="(circle,n) in 3" :key="n"></view>
			</view>
			<view class="order-list">
				<view class="item" v-for="(item, index) in listData" :key="index">
					<view class="order-head d-b-c">
						<view>
							<text class="state-text" v-if="item.order_source==10">普通订单</text>
							<text class="shop-name flex-1 fb">订单号：{{item.order_no}}</text>
						</view>
						<view class="state">
							<text class="red">{{item.state_text}}</text>
						</view>
					</view>
					<!--多个商品显示-->
					<view class="product-list pr" v-if="item.product.length>1" @click="gotoPage(item.order_id)">
						<scroll-view scroll-x="true">
							<view class="list d-s-c pr100">
								<view class="cover mr10" v-for="(img, num) in item.product" :key="num">
									<image :src="img.image.file_path" mode="aspectFit"></image>
								</view>
							</view>
						</scroll-view>
						<view class="total-count">
							<view class="left-shadow"></view>
							<view class="price f22">
								¥<text class="f40">{{item.order_price}}</text>
							</view>
							<view class="count">
								共{{item.product.length}}件
							</view>
						</view>
					</view>
					<!--一个商品显示-->
					<view class="one-product d-s-c" v-else @click="gotoPage(item.order_id)">
						<view class="cover" v-for="(img, num) in item.product" :key="num">
							<image :src="img.image.file_path" mode="aspectFit"></image>
						</view>
						<view class="pro-info flex-1">
							{{item.product[0].product_name}}
						</view>
						<view class="total-count">
							<view class="left-shadow"></view>
							<view class="price f22">
								¥<text class="f40">{{item.order_price}}</text>
							</view>
							<view class="count">
								共{{item.product[0].total_num}}件
							</view>
						</view>
					</view>
					<view class="order-bts">
						<block v-if="item.order_status.value != 20">
							<!-- 未支付取消订单 -->
							<button @click="cancelOrder(item.order_id)" type="default" v-if="item.pay_status.value==10">取消</button>
							<!-- 已支付取消订单 -->
							<block v-if="item.order_status.value!=21">
								<block v-if="item.pay_status.value==20 && item.delivery_status.value==10">
									<button @click="cancelOrder(item.order_id)" type="default">申请取消</button>
								</block>
							</block>
							<text v-else class="count">取消申请中</text>
							<!-- 订单付款 -->
							<block v-if="item.pay_status.value==10">
								<button class="btn-red" @click="payTypeFunc(20,item.order_id)">付款</button>
							</block>
							<!-- 确认收货 -->
							<block v-if="item.delivery_status.value==20 && item.receipt_status.value == 10">
								<button @click="orderReceipt(item.order_id)">确认收货</button>
							</block>
							<!-- 订单评价 -->
							<button type="default" v-if="item.order_status.value==30 && item.is_comment==0" @click="gotoEvaluate(item.order_id)">评价</button>
						</block>

					</view>
				</view>
				<view class="d-c-c p30" v-if="listData.length==0 && !loading">
					<text class="iconfont icon-wushuju"></text>
					<text class="cont">亲，暂无相关记录哦</text>
				</view>
				<uni-load-more v-else :loadingType="loadingType"></uni-load-more>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import Popup from '@/components/uni-popup.vue'
    import uniLoadMore from "@/components/uni-load-more.vue"
	import {pay} from '@/common/pay.js'
	export default {
		components: {
			Popup,
            uniLoadMore
		},
		data() {
			return {
				/*手机高度*/
				phoneHeight: 0,
				/*可滚动视图区域高度*/
				scrollviewHigh: 0,
				/*状态选中*/
				state_active: 0,
				/*顶部刷新*/
				topRefresh: false,
				/*数据*/
				listData: [],
				dataType: 'all',
				/*是否显示支付类别弹窗*/
				isPayPopup: false,
				/*支付方式*/
				pay_type: 20,
				/*订单id*/
				order_id: 0,
				/*最后一页码数*/
				last_page: 0,
				/*当前页面*/
				page: 1,
				/*每页条数*/
				list_rows: 10,
				no_more: false,
				loading: true,
				/*是否显示核销二维码*/
				isCodeImg: false,
				codeImg: ''
			}
		},
        computed:{

			/*加载中状态*/
            loadingType(){
                if(this.loading){
                    return 1;
                }else{
                    if(this.listData.length!=0&&this.no_more){
                        return 2;
                    }else{
                        return 0;
                    }
                }
            }
        },
		onLoad(e) {
			
			this.$status.setStates('type',null);
			
			this.dataType = e.dataType;
			if (this.dataType == 'payment') {
				this.state_active = 1;
			} else if (this.dataType == 'received') {
				this.state_active = 3;
			}

		},
		mounted() {
			this.init();
			/*获取订单列表*/
			this.getData();

		},
		methods: {
			/*初始化*/
			init() {
				let self = this;
				uni.getSystemInfo({
					success(res) {
						self.phoneHeight = res.windowHeight;
						// 计算组件的高度
						let view = uni.createSelectorQuery().select('.top-tabbar');
						view.boundingClientRect(data => {
							let h = self.phoneHeight - data.height;
							self.scrollviewHigh = h;
						}).exec();
					}
				});
			},
			/*状态切换*/
			stateFunc(e) {
				let self = this;
				self.page = 1;
				self.loading = true;
				self.state_active = e;
				switch (e) {
					case 0:
						self.listData = [];
						self.dataType = 'all';
						break;
					case 1:
						self.listData = [];
						self.dataType = 'payment';
						break;
					case 2:
						self.listData = [];
						self.dataType = 'delivery';
						break;
					case 3:
						self.listData = [];
						self.dataType = 'received';
						break;
					case 4:
						self.listData = [];
						self.dataType = 'comment';
						break;
				};
				self.getData();
			},
			/*获取数据*/
			getData() {
				let self = this;
                self.loading = true;
				let dataType = self.dataType;
				self._get('user.order/lists', {
					dataType: dataType,
					page: self.page,
					list_rows: self.list_rows,
				}, function(res) {
					self.loading = false;
					self.listData = self.listData.concat(res.data.list.data);
					self.last_page = res.data.list.last_page;
					if (res.data.list.last_page <= 1) {
						self.no_more = true;
                        return false;
					}
				});
			},
			/*跳转页面*/
			gotoPage(e) {
				uni.navigateTo({
					url: '/pages/order/order-detail/order-detail?order_id=' + e,
				});
			},

			/*去支付*/
			payTypeFunc(payType,orderId) {
				let self = this;
				self.isPayPopup = false;
				let pay_source = 'wx';
				// #ifdef  H5
				pay_source = 'mp';
				// #endif
				uni.showLoading({
					title: '加载中',
					mask:true
				});
				self._post('user.order/pay', {
					payType: payType,
					order_id: orderId,
					pay_source: pay_source
				}, function(res) {
					pay(res, self);
				});
			},


			/*确认收货*/
			orderReceipt(order_id) {
				let self = this;
				wx.showModal({
					title: "提示",
					content: "您确定要收货吗?",
					success: function(o) {
						uni.showLoading({
							title: '正在处理',
							mask:true
						});
						o.confirm && self._post('user.order/receipt', {
							order_id: order_id,
						}, function(res) {
							uni.hideLoading();
							uni.showToast({
								title: res.msg,
								duration: 2000,
								icon: 'success'
							});
							self.listData = [];
							self.getData();
						});
					}
				});

			},

			/*取消订单*/
			cancelOrder(e) {
				let self = this;
				let order_id = e;
				wx.showModal({
					title: "提示",
					content: "您确定要取消吗?",
					success: function(o) {
						uni.showLoading({
							title: '正在处理',
							mask:true
						});
						o.confirm && self._get('user.order/cancel', {
							order_id: order_id,
						}, function(res) {
							uni.hideLoading();
							uni.showToast({
								title: '操作成功',
								duration: 2000,
								icon: 'success'
							});
							self.listData = [];
							self.getData();
						});
					}
				});

			},

			/*去评论*/
			gotoEvaluate(e) {
				uni.navigateTo({
					url: '/pages/order/evaluate/evaluate?order_id=' + e,
				});
			},

			/*可滚动视图区域到底触发*/
			scrolltolowerFunc() {
				let self = this;
				if(self.no_more){return;}
				self.page++;
				if (self.page <= self.last_page) {
					self.getData();
				}else{
					self.no_more = true;
				}
			}
		
		}
	}
</script>

<style lang="scss">
	
	.order-list .order-head .state-text{padding: 4rpx 8rpx; margin-right: 10rpx; border-radius: 4rpx; background: #E2231A;  color:#FFFFFF;}
	
	.order-list  .item {
		margin-top: 30rpx;
		padding: 30rpx;
		background: #FFFFFF;
	}

	.order-list .product-list,
	.order-list .one-product {
		padding: 20rpx 0;
		height: 160rpx;
	}

	.one-product .pro-info {
		padding: 0 30rpx;
		display: -webkit-box;
		overflow: hidden;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		font-size: 28rpx;
		color: #666666;
	}

	.order-list .cover,
	.order-list .cover image {
		width: 160rpx;
		height: 160rpx;
	}

	.order-list .total-count {
		padding-left: 20rpx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: flex-end;
	}

	.total-count .count {
		padding-top: 10rpx;
		color: #666;
		font-size: 28rpx;
	}

	.product-list .total-count {
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		background: rgba(255, 255, 255, .9);
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
		background-image: radial-gradient(rgba(0, 0, 0, .2) 10%, rgba(0, 0, 0, .1) 40%, rgba(0, 0, 0, 0) 80%);
	}

	.order-list .order-bts {
		display: flex;
		justify-content: flex-end;
		align-items: center;
	}

	.order-list .order-bts button {
		margin: 0;
		padding: 0 30rpx;
		height: 60rpx;
		line-height: 60rpx;
		margin-left: 20rpx;
		border-radius: 30rpx;
		font-size: 24rpx;
		border: 1px solid #CCCCCC;
		white-space: nowrap;
		background: #FFFFFF;
	}

	.order-list .order-bts button::after {
		display: none;
	}

	.order-list .order-bts button.btn-border-red {
		border: 1px solid $dominant-color;
		font-size: 24rpx;
		color: $dominant-color;
	}

	.order-list .order-bts button.btn-red {
		background: $dominant-color;
		border: 1px solid $dominant-color;
		font-size: 24rpx;
		color: #FFFFFF;
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
		color: #04BE01;
		font-size: 50rpx;
	}

	.buy-checkout .iconfont.icon-yue {
		color: #f0de7c;
		font-size: 50rpx;
	}

	.buy-checkout .item.active .iconfont.icon-xuanze {
		color: #04BE01;
	}
</style>
