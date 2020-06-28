<template>
	<view class="refund-list">
		<view class="top-tabbar">
			<view :class="state_active==-1?'tab-item active':'tab-item'" @click="stateFunc(-1)">
				全部
			</view>
			<view :class="state_active==0?'tab-item active':'tab-item'" @click="stateFunc(0)">
				待处理
			</view>
		</view>
		<scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'" lower-threshold="50"
		 @scrolltoupper="scrolltoupperFunc" @scrolltolower="scrolltolowerFunc">
			<view :class="topRefresh?'top-refresh open':'top-refresh'">
				<view class="circle" v-for="(circle,n) in 3" :key="n"></view>
			</view>
			<view class="list">
				<view class="item bg-white p30 mb20" v-for="(item,index) in tableData" :key="index">
					<view class="d-b-c">
						<text>{{item.create_time}}</text>
						<text class="red">{{item.state_text}}</text>
					</view>
					<view class="one-product d-s-c pt20">
						<view class="cover">
							<imageComponent :imgurl="item.orderproduct.image.file_path" mode="aspectFit"></imageComponent>
						</view>
						<view class="flex-1">
							<view class="pro-info">{{item.orderproduct.product_name}}</view>
							<view class="pt10 p-0-30">
								<text class="f24 gray9">
									<!--斤:10斤; 颜色:红; 出产地:北方; 销量:大于1000; 大小:直径1分米; 口感:甜;-->
								</text>
							</view>
						</view>
					</view>
					<view class="d-e-c pt20">
						<view>
							商品金额：
							<text class="red">¥{{item.orderproduct.line_price}}</text>
						</view>
					</view>
					<view class="d-e-c pt10">
						<view>
							订单实付金额：
							<text class="red">¥{{item.orderproduct.total_price}}</text>
						</view>
					</view>
					<view class="d-e-c mt20 pt20 border-t">
						<button type="default" class="btn-gray-border" @click="gotoRefundDetail(item.order_refund_id)">查看详情</button>
					</view>
				</view>

				<!-- <view class="">
					<view class="bottom-refresh">
						<view class="d-c-c p30" v-if="tableData.length!=0&&no_more">
							<text class="gray3">亲, 没有更多了</text>
						</view>
						<view v-if="loading" class="d-c-c p30">
							<text class="gray3">加载中...</text>
						</view>
					</view>
				</view> -->
				<!-- 没有记录 -->
				<view class="d-c-c p30" v-if="tableData.length==0 && !loading">
					<text class="iconfont icon-wushuju"></text>
					<text class="cont">亲，暂无相关记录哦</text>
				</view>
				<uni-load-more v-else :loadingType="loadingType"></uni-load-more>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import imageComponent from '@/components/imageComponent/imageComponent.vue';
	import uniLoadMore from "@/components/uni-load-more.vue";
	export default {
		components: {
			imageComponent,
			uniLoadMore
		},
		data() {
			return {
				/*手机高度*/
				phoneHeight: 0,
				/*可滚动视图区域高度*/
				scrollviewHigh: 0,
				/*选中状态*/
				state_active: -1,
				/*页面数据*/
				tableData: [],
				list_rows: 5,
				last_page: 0,
				page: 1,
				no_more: false,
				loading: true,
				/*顶部刷新*/
				topRefresh: false,
				
			}
		},
		computed:{
			/*加载中状态*/
			loadingType(){
				if(this.loading){
					return 1;
				}else{
					if(this.tableData.length!=0&&this.no_more){
						return 2;
					}else{
						return 0;
					}
				}
			}
		},
		mounted() {
			this.init();
			/*获取页面数据*/
			this.getData();

		},
		onPullDownRefresh() {
			/*下拉到顶，页面值还原初始化*/
			//this.restoreData();
			//this.getData();
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
			/*页面数据*/
			getData() {
				let self = this;
				self.loading = true;
				let page = self.page;
				let state = self.state_active;
				let list_rows = self.list_rows;
				self._get('user.refund/lists', {
					state: state,
					page: page || 1,
					list_rows: list_rows,
				}, function(data) {
					self.loading = false;
					self.tableData =self.tableData.concat(data.data.list.data);
					// console.log(self.tableData);
					self.last_page =data.data.list.last_page;
					if (self.last_page <= 1) {
						self.no_more = true;
						return false;
					}
				});

			},
			/*类别切换*/
			stateFunc(e) {
				let self = this;
				let state = e;
				self.tableData = [];
				self.loading = true;
				self.page = 1;
				self.state_active = e;
				self.getData();
			},
			/*查看售后详情*/
			gotoRefundDetail(e) {
				uni.navigateTo({
					url: '/pages/order/refund/detail/detail?order_refund_id=' + e,
				});
			},
			/*可滚动视图区域到顶触发*/
			scrolltoupperFunc() {
				/* let self = this;
				self.topRefresh = true;
				self.no_more = false;
				setTimeout(function() {
					self.topRefresh = false;
				}, 2000);
				self.tableData = [];
				self.page = 1;
				self.getData(); */
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

<style>

</style>
