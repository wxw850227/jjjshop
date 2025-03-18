<template>
	<div class="pb50" v-loading="loading">
		<!--订单进度-->
		<!--内容-->
		<div class="product-content">
			<div class="common-form">实时概况</div>
			<div class="table-wrap">
				<el-row :gutter="20">
					<el-col :span="6">
						<div class="pb16 grid-content bg-purple">
							<div>营业总额(元)</div>
							<div class="detail_prici">{{ detail.total_price }}</div>
						</div>
					</el-col>
					<el-col :span="6">
						<div class="pb16 grid-content bg-purple">
							<div>预计收入(元)</div>
							<div class="detail_prici">{{ detail.income_money }}</div>
						</div>
					</el-col>
					<el-col :span="6">
						<div class="pb16 grid-content bg-purple">
							<div>有效订单(个)</div>
							<div class="detail_prici">{{ detail.order_count }}</div>
						</div>
					</el-col>
					<el-col :span="6">
						<div class="pb16 grid-content bg-purple">
							<div>退款金额(元)</div>
							<div class="detail_prici">{{ detail.refund_money }}</div>
						</div>
					</el-col>
				</el-row>
			</div>
			<!--内容-->
			<div class="product-content d-b-c ww100">
				<div class="right-box d-s-s d-c flex-1 pr16">
					<div class="lh30 f16 tl">销量排行榜</div>
					<div class="list ww100">
						<ul v-if="salesNumRank.length>0">
							<li v-for="(item, index) in salesNumRank" :key="index" class="d-s-c p-6-0 border-b-d">
								<span class="key-box">{{ index + 1 }}</span>
								<span class="text-ellipsis-2 flex-1 ml10">{{ item.product_name }}</span>
								<span class="gray9 tr" style="width: 100px;">
									销量：{{ item.total_num }}
								</span>
								<span class="gray9 tr" style="width: 100px;">
									销售额：{{ item.total_price }}
								</span>

							</li>
						</ul>
						<div v-else class="tc pt30">暂无上榜记录</div>
					</div>
				</div>
				<div class="right-box d-s-s d-c flex-1 pr16">
					<div class="lh30 f16 tl">销售额排行榜</div>
					<div class="list ww100">
						<ul v-if="salesMoneyRank.length>0">
							<li v-for="(item, index) in salesMoneyRank" :key="index" class="d-s-c p-6-0 border-b-d">
								<span class="key-box">{{ index + 1 }}</span>
								<span class="text-ellipsis-2 flex-1 ml10">{{ item.product_name }}</span>
								<span class="gray9 tr" style="width: 100px;">
									销量：{{ item.total_num }}
								</span>
								<span class="gray9 tr" style="width: 100px;">
									销售额：{{ item.total_price }}
								</span>
							</li>
						</ul>
						<div v-else class="tc pt30">暂无上榜记录</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import TakeOutApi from '@/api/takeout.js';
	export default {
		data() {
			return {
				active: 0,
				/*是否加载完成*/
				loading: true,
				/*订单数据*/
				detail: {
					total_price: '',
					income_money: '',
					order_count: '',
					refund_money: ''
				},
				activeName: 'sale',
				salesNumRank: [],
				salesMoneyRank: []
			};
		},
		created() {
			/*获取列表*/
			this.getParams();
		},
		methods: {
			/*获取参数*/
			getParams() {
				let self = this;
				self.loading = true;
				TakeOutApi.takeSurvey({},
						true
					)
					.then(data => {
						self.detail = data.data.detail;
						self.salesNumRank = data.data.salesNumRank;
						self.salesMoneyRank = data.data.salesMoneyRank;
						self.loading = false;
					})
					.catch(error => {
						self.loading = false;
					});
			},
			handleClick() {
				this.getParams()
			}
		}
	};
</script>
<style lang="scss">
	.el-row {
		margin-bottom: 20px;

		&:last-child {
			margin-bottom: 0;
		}
	}

	.el-col {
		border-radius: 4px;
	}

	.grid-content {
		padding: 20px;
		border-radius: 4px;
		min-height: 36px;
	}

	.bg-purple {
		background: #f4f4f4;
	}

	.table-wrap {
		padding: 20px;
		padding-top: 0;
	}

	.common-form-data {
		margin-left: 15px;
		font-weight: 500;
	}

	.tips {
		padding: 15px;
		margin-bottom: 20px;
	}

	.tips_tit {
		font-size: 22px;
		margin-bottom: 10px;
	}

	.tips_txt {
		line-height: 25px;
		color: #666;
		font-size: 14px;
	}

	.detail_prici {
		font-size: 20px;
		color: #000;
		font-weight: bold;
		margin-top: 10px;
		max-width: 250px;
	}
</style>