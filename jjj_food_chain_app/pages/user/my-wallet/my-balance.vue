<template>
	<view class="p-0-30 bg-white">
		<!--列表-->
		<view class="d-b-c border-b p-30-0" v-for="(item, index) in tableData" :key="index">
			<view class="d-s-s f-w d-c flex-1">
				<text class="30">{{ item.scene.text }}</text>
				<text class="pt10 gray9 f22">{{ item.create_time }}</text>
			</view>
			<view class="red" v-if="item.money > 0">+{{ item.money }}元</view>
			<view class="red" v-else>{{ item.money }}元</view>
		</view>
		<!-- 没有记录 -->
		<view class="d-c-c p30" v-if="tableData.length == 0 && !loading">
			<text class="iconfont icon-wushuju"></text>
			<text class="cont">亲，暂无相关记录哦</text>
		</view>
		<uni-load-more v-else :loadingType="loadingType"></uni-load-more>
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
				/*顶部刷新*/
				topRefresh: false,
				/*手机高度*/
				phoneHeight: 0,
				/*可滚动视图区域高度*/
				scrollviewHigh: 0,
				/*数据列表*/
				tableData: [],
				/*最后一页码数*/
				last_page: 0,
				/*当前页面*/
				page: 1,
				/*每页条数*/
				list_rows: 20,
				no_more: false,
				type: 'all'
			};
		},
		computed: {
			/*加载中状态*/
			loadingType() {
				if (this.loading) {
					return 1;
				} else {
					if (this.tableData.length != 0 && this.no_more) {
						return 2;
					} else {
						return 0;
					}
				}
			}
		},
		onLoad(e) {
			this.type = e.type;
			/*获取数据*/
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
			/*获取数据*/
			getData() {
				let self = this;
				let page = self.page;
				let list_rows = self.list_rows;
				self.loading = true;
				self._get(
					'balance.log/lists', {
						page: page || 1,
						list_rows: list_rows,
						type: self.type
					},
					function(data) {
						self.loading = false;
						self.tableData = self.tableData.concat(data.data.list.data);
						self.last_page = data.data.list.last_page;
						if (data.data.list.last_page <= 1) {
							self.no_more = true;
							return false;
						}
					}
				);
			}
		}
	};
</script>

<style></style>
