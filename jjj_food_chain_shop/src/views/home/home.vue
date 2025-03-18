<template>
	<div class="home" v-loading="loading">
		<!-- 第一行 -->
		<div class="home-t1">
			<div class="home-t1-item">
				<div class="t1-item-t">
					<div class="t1-item-t-tips">今日</div>
					<div class="f14 mb10">用户</div>
					<div class="f30 fb mb10">{{top_data.user_total_today}}</div>
					<div class="d-s-c f13" :class="top_data.user_rate>0?'up':'down'">
						<div class="gray6 mr25">昨日：{{top_data.user_total_yesterday}}</div>
						<div>{{top_data.user_rate}}%</div>
						<div class="icon iconfont icon-shuzhixiajiang" v-if="top_data.user_rate<0"></div>
						<div class="icon iconfont icon-shuzhishangsheng" v-if="top_data.user_rate>0"></div>
					</div>
				</div>
				<div class="t1-item-b">
					<div>累计用户总数</div>
					<div>{{top_data.user_total}}</div>
				</div>
			</div>
			<div class="home-t1-item">
				<div class="t1-item-t">
					<div class="t1-item-t-tips">今日</div>
					<div class="f14 mb10">营业额</div>
					<div class="f30 fb mb10">{{top_data.total_money_today}}</div>
					<div class="d-s-c f13" :class="top_data.total_rate>0?'up':'down'">
						<div class="gray6 mr25">昨日：{{top_data.total_money_yesterday}}</div>
						<div>{{top_data.total_rate}}%</div>
						<div class="icon iconfont icon-shuzhixiajiang" v-if="top_data.total_rate<0"></div>
						<div class="icon iconfont icon-shuzhishangsheng" v-if="top_data.total_rate>0"></div>
					</div>
				</div>
				<div class="t1-item-b">
					<div>累计营业额总数</div>
					<div>{{top_data.total_money}}</div>
				</div>
			</div>
			<div class="home-t1-item">
				<div class="t1-item-t">
					<div class="t1-item-t-tips">今日</div>
					<div class="f14 mb10">订单数</div>
					<div class="f30 fb mb10">{{top_data.order_total_today}}</div>
					<div class="d-s-c f13" :class="top_data.order_rate>0?'up':'down'">
						<div class="gray6 mr25">昨日：{{top_data.order_total_yesterday}}</div>
						<div>{{top_data.order_rate}}%</div>
						<div class="icon iconfont icon-shuzhixiajiang" v-if="top_data.order_rate<0"></div>
						<div class="icon iconfont icon-shuzhishangsheng" v-if="top_data.order_rate>0"></div>
					</div>
				</div>
				<div class="t1-item-b">
					<div>累计订单总数</div>
					<div>{{top_data.order_total}}</div>
				</div>
			</div>
			<div class="home-t1-item">
				<div class="t1-item-t">
					<div class="t1-item-t-tips">今日</div>
					<div class="f14 mb10">退款</div>
					<div class="f30 fb mb10">{{top_data.refund_money_today}}</div>
					<div class="d-s-c f13" :class="top_data.refund_rate>0?'up':'down'">
						<div class="gray6 mr25">昨日：{{top_data.refund_money_yesterday}}</div>
						<div>{{top_data.refund_rate}}%</div>
						<div class="icon iconfont icon-shuzhixiajiang" v-if="top_data.refund_rate<0"></div>
						<div class="icon iconfont icon-shuzhishangsheng" v-if="top_data.refund_rate>0"></div>
					</div>
				</div>
				<div class="t1-item-b">
					<div>累计退款总数</div>
					<div>{{top_data.refund_money}}</div>
				</div>
			</div>
		</div>
		<!-- 第二行 -->
		<div class="d-b-s home-t2">
			<div class="flex-1 home-t2-l">
				<div class="d-s-c home-t">
					<div class="common-form-tltle">待办事项</div>
				</div>
				<div class="t2-b">
					<div class="d-s-c t2-b-content">
						<div class="t2-b-item">
							<div class="f20 fb t2-r-number" @click="gotoPage('/takeout/order/index')">
								{{wait_data.order.takeout}}
							</div>
							<div class="f13 gray6">待处理外卖订单</div>
						</div>
						<div class="t2-b-item">
							<div class="f20 fb t2-r-number" @click="gotoPage('/store/order/index')">
								{{wait_data.order.store}}
							</div>
							<div class="f13 gray6">待处理店内订单</div>
						</div>
						<div class="t2-b-item">
							<div class="f20 fb t2-r-number" @click="gotoPage('/product/takeaway/index')">
								{{wait_data.stock.takeaway}}
							</div>
							<div class="f13 gray6">外卖库存告急</div>
						</div>
						<div class="t2-b-item">
							<div class="f20 fb t2-r-number" @click="gotoPage('/product/store/index')">
								{{wait_data.stock.store}}
							</div>
							<div class="f13 gray6">店内库存告急</div>
						</div>
					</div>
				</div>
			</div>
			<div class="flex-1 home-t2-r">
				<div class="d-s-c home-t">
					<div class="common-form-tltle">营业数据 </div>
				</div>
				<div class="t2-b">
					<div class="d-b-c t2-b-content">
						<div class="t2-b-item">
							<view class="f13 gray6">用户</view>
							<div class="f20 fb t2-r-number">
								{{month_data.user_total}}
							</div>
							<div class="f12 gray9 gray6">上月:{{month_data.user_total_last}}</div>
						</div>
						<div class="t2-b-item">
							<view class="f13 gray6">营业额</view>
							<div class="f20 fb t2-r-number">
								{{month_data.total_money}}
							</div>
							<div class="f12 gray9">上月:{{month_data.total_money_last}}</div>
						</div>
						<div class="t2-b-item">
							<view class="f13 gray6">订单数</view>
							<div class="f20 fb t2-r-number">
								{{month_data.order_total}}
							</div>
							<div class="f12 gray9">上月:{{month_data.order_total_last}}</div>
						</div>
						<div class="t2-b-item">
							<view class="f13 gray6">退款</view>
							<div class="f20 fb t2-r-number">
								{{month_data.refund_money}}
							</div>
							<div class="f12 gray9">上月:{{month_data.refund_money_last}}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="d-b-c o-h ww100" style="overflow: hidden;margin-bottom: 16px;">
			<div class="flex-1 home-t3-l">
				<div class="d-s-c home-t border-b">
					<div class="common-form-tltle ">支付金额概况</div>
					<div class="f13 gray9 flex-1">更新时间：{{update_time}}</div>
					<div class="">
						<el-radio-group v-model="formData.sale_time" size="small" @change="getData()">
							<el-radio-button :label="1">本日</el-radio-button>
							<el-radio-button :label="2">近7天</el-radio-button>
							<el-radio-button :label="3">近15天</el-radio-button>
							<el-radio-button :label="4">近30天</el-radio-button>
						</el-radio-group>
					</div>
				</div>
				<div>
					<div class="home-t3-l-t">
						<div class="d-s-c">
							<span class="f13 mr10">支付金额 (元)</span>
							<el-popover placement="top-start" :width="200" trigger="hover" content="统计时间内，订单的实际支付金额。">
								<template #reference>
									<el-icon size="20px" color="#999">
										<InfoFilled />
									</el-icon>
								</template>
							</el-popover>
						</div>
						<div class="f30 fb">{{saleData && saleData.saleMoney}}</div>
					</div>
					<div>
						<SaleChart ref="SaleChart" :listData="saleData"></SaleChart>
					</div>
				</div>
			</div>
			<div class="flex-1 home-t3-r">
				<div class="d-b-c home-t border-b">
					<div class="common-form-tltle ">订单概况</div>
					<div class="">
						<el-radio-group v-model="formData.order_time" size="small" @change="getData()">
							<el-radio-button :label="1">本日</el-radio-button>
							<el-radio-button :label="2">近7天</el-radio-button>
							<el-radio-button :label="3">本月</el-radio-button>
							<el-radio-button :label="4">本年</el-radio-button>
						</el-radio-group>
					</div>
				</div>
				<div>
					<div class="home-t3-r-t">
						<div class="d-s-c">
							<div style="width: 20%;" v-for="(item,index) in orderData">
								<div class="f13 gray6">{{item.name}}</div>
								<div class="f30 gray3 fb">{{item.value}}</div>
							</div>
						</div>
						<div class="f30 fb">{{orderData && orderData.saleMoney}}</div>
					</div>
					<div>
						<OrderChart ref="OrderChart" :listData="orderData"></OrderChart>
					</div>

				</div>
			</div>
		</div>


		<div class="home-t3">
			<div class="flex-1 home-t3-r mr14">
				<div class="d-s-c home-t border-b">
					<div class="common-form-tltle flex-1">商品销量排行</div>
					<div class="">
						<el-radio-group v-model="formData.product_time" size="small" @change="getData()">
							<el-radio-button :label="1">本日</el-radio-button>
							<el-radio-button :label="2">近7天</el-radio-button>
							<el-radio-button :label="3">本月</el-radio-button>
							<el-radio-button :label="4">本年</el-radio-button>
						</el-radio-group>
					</div>
				</div>
				<el-table class="rank-table" :data="salesNumRank">
					<el-table-column prop="index" label="排名" width="80px">
						<template #default="scope">
							<div class="rankball" :class="'rankball_' + scope.$index">
								{{ scope.$index + 1 }}
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="product_name" label="商品名称"></el-table-column>
					<el-table-column align="center" prop="total_num" label="销量" width="120px"></el-table-column>
				</el-table>
			</div>
			<div class="flex-1 home-t3-r">
				<div class="d-s-c home-t border-b">
					<div class="common-form-tltle flex-1">商品销售额排行</div>
					<div class="">
						<el-radio-group v-model="formData.product_sale_time" size="small" @change="getData()">
							<el-radio-button :label="1">本日</el-radio-button>
							<el-radio-button :label="2">近7天</el-radio-button>
							<el-radio-button :label="3">本月</el-radio-button>
							<el-radio-button :label="4">本年</el-radio-button>
						</el-radio-group>
					</div>
				</div>
				<el-table class="rank-table" :data="salesMoneyRank">
					<el-table-column prop="index" label="排名" width="80px">
						<template #default="scope">
							<div class="rankball" :class="'rankball_' + scope.$index">
								{{ scope.$index + 1 }}
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="product_name" label="商品名称"></el-table-column>
					<el-table-column align="center" prop="total_price" label="销售额" width="120px"></el-table-column>
				</el-table>
			</div>
		</div>
	</div>
</template>

<script>
	import {
		useUserStore
	} from '@/store';
	import IndexApi from '@/api/index.js';
	import SaleChart from '@/views/home/part/SaleChart.vue';
	import OrderChart from '@/views/home/part/OrderChart.vue';
	export default {
		components: {
			SaleChart,
			OrderChart
		},
		data() {
			return {
				/*是否加载完成*/
				loading: true,
				month_data: {},
				top_data: {},
				/*待办事项*/
				wait_data: {
					order: {},
					stock: {},
				},
				salesMoneyRank: [],
				salesNumRank: [],
				formData: {
					/* 1,2,3,4 */
					product_time: 1,
					/* 1今日  2 7天 3本月 4本年*/
					product_sale_time: 1,
					sale_time: 1,
					order_time: 1
				},
				type: 1,
				/* 销售额折线图列表 */
				saleData: null,
				orderData: null,
				update_time: '',
				user_type: 0

			};
		},
		created() {
			console.log('created');
			/*获取数据*/
			this.getData();
			this.getBaseInof();

		},
		methods: {
			async getBaseInof() {
				const {
					userInfo
				} = useUserStore();
				this.user_type = userInfo.user_type;
			},
			/*获取数据*/
			getData() {
				let self = this;
				IndexApi.getCount(this.formData).then(data => {
					self.loading = false;
					let res = data.data.data;
					self.saleData = res.saleData;
					self.orderData = res.orderData;
					self.top_data = res.top_data;
					self.month_data = res.month_data;
					self.wait_data = res.wait_data;
					self.salesNumRank = res.salesNumRank;
					self.salesMoneyRank = res.salesMoneyRank;
					self.update_time = res.update_time;
					self.$refs.OrderChart.changeData(self.orderData);
					self.$refs.SaleChart.changeData(self.saleData);
					console.log(self.orderData)
					console.log(self.saleData)
				}).catch(error => {

				});
			},
			gotoPage(url) {
				this.$router.push(url);
			}
		}
	};
</script>

<style lang="scss" scoped>
	.subject-wrap>.home {
		background: none;
		padding: 0;
		margin: 13px;
	}

	.home-t1 {
		display: flex;
		justify-content: center;
		align-items: center;
		margin-bottom: 14px;

		.home-t1-item {
			position: relative;
			flex: 1;
			margin-right: 12px;
			padding: 27px 12px 0 12px;
			background-color: #fff;

			// height: 192px;
			.t1-item-t {
				border-bottom: 1px solid #EBF3FC;
				height: 114px;

				.t1-item-t-tips {
					border: 1px solid #3A8EE6;
					border-radius: 5px;
					color: #3A8EE6;
					font-size: 14px;
					background: #EBF3FC;
					width: 38px;
					height: 28px;
					display: flex;
					justify-content: center;
					align-items: center;
					position: absolute;
					right: 12px;
					top: 20px;
				}

				.up {
					color: #EA7C7C;

					.icon-shuzhishangsheng {
						color: #EA7C7C;
						font-size: 12px;
					}
				}

				.down {
					color: #5EC3C3;

					.icon-shuzhixiajiang {
						color: #5EC3C3;
						font-size: 12px;
					}
				}
			}

			.t1-item-b {
				font-size: 13px;
				color: #666;
				display: flex;
				justify-content: space-between;
				align-items: center;
				height: 50px;
			}
		}

		.home-t1-item:last-child {
			margin: 0;
		}
	}

	.home-t2 {
		margin-bottom: 14px;


		.home-t2-l,
		.home-t2-r {
			height: 152px;
		}

		.home-t2-r {
			background-color: #fff;
			padding: 0 12px;
		}

		.home-t2-l {
			background-color: #fff;
			margin-right: 13px;
			padding: 0 12px;
			padding-top: 16px;
		}

		.home-t2-r {
			.t2-b-content {
				padding: 0 20px;
			}

			.t2-b-item {
				// width: 25%;
				display: flex;
				justify-content: center;
				align-items: center;
				flex-direction: column;

				.t2-r-number {
					font-size: 30px;
					// cursor: pointer;
					color: #333;
				}
			}
		}

		.home-t2-l {
			background-color: #fff;
			padding: 0 12px;

			.t2-b {
				// padding-top: 20px;

				.t2-b-title {
					color: #333;
					font-size: 14px;
					margin-bottom: 6px;
				}

				.t2-b-content {
					margin-bottom: 12px;
				}

				.t2-b-item {
					width: 25%;
					display: flex;
					justify-content: center;
					align-items: center;
					flex-direction: column;

					.t2-r-number {
						font-size: 30px;
						cursor: pointer;
						color: #3A8EE6;
					}
				}

			}
		}

	}

	.home-t3 {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;

		.home-t3-l {
			background-color: #fff;
			margin-right: 13px;
			padding: 0 12px;
		}

		.home-t3-r {
			background-color: #fff;
			padding: 0 12px;

			:deep(.el-table th.el-table__cell) {
				background-color: #fff;
			}

			:deep(.el-table td.el-table__cell) {
				border: none;
			}

			:deep(.el-table th.el-table__cell.is-leaf) {
				border: none;
			}

			:deep(.el-table__inner-wrapper::before) {
				display: none;
			}

			.rankball {
				width: 26px;
				height: 26px;
				border-radius: 50%;
				background: #D0D0D0;
				font-size: 14px;
				color: #FFFFFF;
				display: flex;
				justify-content: center;
				align-items: center;
			}

			.rankball_0 {
				background: #f1aaaa;
			}

			.rankball_1 {
				background: #ABB4C7;
			}

			.rankball_2 {
				background: #EBCA80;
			}
		}


	}

	.home-t3-l,
	.home-t3-r {
		height: 462px;
		flex: 1;
		flex-shrink: 0;
		background-color: #fff;
		padding: 0 12px;
		padding-bottom: 22px;
		box-sizing: border-box;
	}

	.home-t3-l {
		margin-right: 14px;


		.home-t3-l-t {
			padding-top: 14px;
		}
	}

	.home-t3-r {
		.home-t3-r-t {
			padding-top: 18px;
		}
	}

	.home-t {
		height: 60px;
		// border-bottom: 1px solid #eee;
	}

	.home-t.border-b {
		border-bottom: 1px solid #eee;
	}

	.common-form-tltle {
		font-size: 16px;
		font-weight: bold;
		margin: 0;
		padding-left: 0;
		margin-right: 17px;

	}

	.mr25 {
		margin-right: 25px;
	}

	.mr14 {
		margin-right: 14px;
	}
</style>