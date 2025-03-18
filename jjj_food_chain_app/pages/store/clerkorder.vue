<template>
	<view class="order-datail">
		<!--详情状态-->
		<view class="order-state d-s-c">
			<view class="icon-box"><span class="icon iconfont icon-gantanhao"></span></view>
			<view class="state-cont flex-1">
				<view class="state-txt d-b-c">
					<text class="desc f34">{{ detail.state_text }}</text>
				</view>
			</view>
			<view class="dot-bg"></view>
		</view>

		<!-- 上门自提：自提门店 -->
		<view class="order-express p30 d-s-c" v-if="detail.delivery_type.value == 20">
			<view class="flow-delivery__title m-top20">
				<span class="icon iconfont icon-dizhi1">自提门店</span>
			</view>
			<view class="cont-text ml20">
				<view class="express-text">
					{{extractStore.store_name }} {{extractStore.phone }}
					<view class="f24 gray9 pt10">
						{{ extractStore.region.province }} {{ extractStore.region.city}}
						{{ extractStore.region.region }}
						{{ extractStore.address }}
					</view>
				</view>
			</view>
		</view>

		<!--购物列表-->
		<view class="shops group bg-white">
			<view class="group-hd border-b-e">
				<view class="left">
					<text class="min-name">商品</text>
				</view>
			</view>
			<view class="list">
				<view class="one-product p-20-0" v-for="(item, index) in detail.product" :key="index">
					<view class="d-s-c">
						<view class="cover">
							<image :src="item.image.file_path" mode="aspectFit"></image>
						</view>
						<view class="flex-1">
							<view class="pro-info">{{ item.product_name }}</view>
							<view class="pt10 p-0-30 d-b-c">
								<view class="price f22">
									¥
									<text class="f40">{{ item.product_price }}</text>
								</view>
								<view class="f24 gray9">x{{ item.total_num }}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>

		<!--订单信息-->
		<view class="group bg-white f28">
			<view class="p-20-0">
				<text class="gray9">订单编号：</text>
				<text>{{ detail.order_no }}</text>
			</view>
			<view class="p-20-0">
				<text class="gray9">下单时间：</text>
				<text>{{ detail.create_time }}</text>
			</view>
			<view class="p-20-0">
				<text class="gray9">支付方式：</text>
				<text>{{ detail.pay_type.text }}</text>
			</view>
			<view class="p-20-0">
				<text class="gray9">配送方式：</text>
				<text>{{ detail.delivery_type.text }}</text>
			</view>
			<view class="p-20-0 d-b-c">
				<text class="gray9">商品金额</text>
				<text>¥ {{ detail.order_price }}</text>
			</view>
			<view class="p-20-0 d-b-c">
				<text class="gray9">运费</text>
				<text>+ ¥ {{ detail.express_price }}</text>
			</view>
			<view class="p-20-0 d-e-c fb f34">
				应付金额：
				<text class="red">¥ {{ detail.order_price }}</text>
			</view>
		</view>

		<!-- 操作栏 -->
		<view v-if="detail.order_status.value != 20 " class="flow-fixed-footer b-f">
			<!-- 订单核销 -->
			<view v-if="detail.pay_status.value==20 && detail.delivery_type.value ==20 && detail.delivery_status.value == 10 ">
				<button class="btn-red" @click="onSubmitExtract(detail.order_id)">确认核销</button>
			</view>
		</view>
	</view>
</template>

<script>
	import Popup from '@/components/uni-popup.vue'
	import utils from '@/common/utils.js'
	export default {
		data() {
			return {
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				/*是否显示支付类别弹窗*/
				isPayPopup: false,
				/*订单id*/
				order_no: 0,
				/*订单详情*/
				detail: {
					order_status: [],
					address: {
						region: []
					},
					product: [],
					pay_type: [],
					delivery_type: [],
					pay_status: []
				},
				extractStore: {},
			};
		},
		components: {},
		onLoad(e) {
			this.order_no = e.order_no;
		},
		mounted() {
			/*获取订单详情*/
			this.getData();
		},
		methods: {
			
			/*获取数据*/
			getData() {
				let self = this;
				uni.showLoading({
					title: '加载中'
				});
				self._get(
					'store.order/detail', {
						order_no: self.order_no
					},
					function(res) {
						self.detail = res.data.order;
						self.extractStore = res.data.order.extractStore;
						uni.hideLoading();
					},
					function(res) {
						uni.switchTab({
							url:'/pages/user/my_shop/my_shop'
						})
					}
				);
			},


			/*核销*/
			onSubmitExtract(order_id) {
				let self = this;
				uni.showModal({
					title: "提示",
					content: "您确定要核销吗?",
					success: function(o) {
						o.confirm && self._post(
							'store.order/extract', {
								order_id: order_id
							},
							function(res) {
								uni.showToast({
									title: res.msg,
									duration: 2000,
									icon: 'success',
								});
								setTimeout(function () {
								    self.getData();
								}, 2000);
							}
						);
					}
				});
			},
		}
	};
</script>

<style scoped>
	.order-express {
		background: #ffffff;
	}

	.order-express .icon-box .iconfont {
		font-size: 50rpx;
	}

	.order-datail {
		padding-bottom: 90 rpx;
	}
</style>
