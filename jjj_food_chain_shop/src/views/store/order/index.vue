<template>
	<!--
      作者：luoyiming
      时间：2019-10-25
      描述：订单列表
  -->
	<div class="user">
		<!--搜索表单-->
		<div class="common-seach-wrap">
			<el-form size="small" :inline="true" :model="searchForm" class="demo-form-inline">
				<el-form-item label="订单号">
					<el-input size="small" v-model="searchForm.order_no" placeholder="请输入订单号"></el-input>
				</el-form-item>
				<el-form-item label="配送方式">
					<el-select size="small" v-model="searchForm.style_id" placeholder="请选择">
						<el-option label="全部" value=""></el-option>
						<el-option v-for="(item, index) in exStyle" :key="index" :label="item.name"
							:value="item.value"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="起始时间">
					<div class="block">
						<span class="demonstration"></span>
						<el-date-picker size="small" v-model="searchForm.create_time" type="daterange"
							value-format="YYYY-MM-DD" range-separator="至" start-placeholder="开始日期"
							end-placeholder="结束日期"></el-date-picker>
					</div>
				</el-form-item>
				<el-form-item>
					<el-button size="small" type="primary" icon="Search" @click="onSubmit">查询</el-button>
				</el-form-item>
				<el-form-item>
					<el-button size="small" type="success" @click="onExport">导出</el-button>
				</el-form-item>
			</el-form>
		</div>
		<!--内容-->
		<div class="product-content">
			<div class="table-wrap">
				<el-tabs v-model="activeName" @tab-change="handleClick">
					<el-tab-pane label="全部订单" name="all">
						<template #label>
							<span>全部订单 <el-tag size="mini">{{order_count.all}}</el-tag></span>
						</template>
					</el-tab-pane>
					<el-tab-pane :label="'未付款'" name="payment">
						<template #label>
							<span>未付款 <el-tag size="mini">{{order_count.payment}}</el-tag></span>
						</template>
					</el-tab-pane>
					<el-tab-pane :label="'进行中'" name="process">
						<template #label>
							<span>进行中 <el-tag size="mini">{{order_count.process}}</el-tag></span>
						</template>
					</el-tab-pane>
					<el-tab-pane :label="'已取消'" name="cancel">
						<template #label>
							<span>已取消 <el-tag size="mini">{{order_count.cancel}}</el-tag></span>
						</template>
					</el-tab-pane>
					<el-tab-pane :label="'已完成'" name="complete">
						<template #label>
							<span>已完成 <el-tag size="mini">{{order_count.complete}}</el-tag></span>
						</template>
					</el-tab-pane>
					<el-tab-pane :label="'已退款'" name="refund">
						<template #label>
							<span>已退款({{order_count.refund}})</span>
						</template>
					</el-tab-pane>
				</el-tabs>
				<el-table size="small" :data="tableData.data" :span-method="arraySpanMethod" border style="width: 100%"
					v-loading="loading">
					<el-table-column prop="order_no" label="订单信息" width="400">
						<template #default="scope">
							<div class="order-code" v-if="scope.row.is_top_row">
								<span class="state-text"
									:class="{'state-text-red':scope.row.order_source != 10}">{{scope.row.order_source_text}}</span>
								<span class="c_main">订单号：{{ scope.row.order_no }}</span>
								<span class="pl16">下单时间：{{ scope.row.create_time }}</span>
								<!--是否取消申请中-->
								<span class="pl16" v-if="scope.row.order_status == 21">
									<el-tag effect="dark" size="mini">取消申请中</el-tag>
								</span>
							</div>
							<template v-else>
								<div class="product-info" v-for="(item, index) in scope.row.product" :key="index">
									<div class="pic"><img v-img-url="item.image.file_path" alt="" /></div>
									<div class="info">
										<div class="name gray3 product-name">
											<span>{{ item.product_name }}</span>
										</div>
										<div class="gray9" v-if="item.product_attr">{{ item.product_attr }}</div>
										<div class="orange" v-if="item.refund">
											{{ item.refund.type.text }}-{{ item.refund.status.text }}
										</div>
									</div>
									<div class="d-c-c d-c">
										<div class="orange">￥ {{ item.product_price }}</div>
										<div class="gray3">x{{ item.total_num }}</div>
									</div>
								</div>
							</template>
						</template>
					</el-table-column>
					<el-table-column prop="pay_price" label="实付款">
						<template #default="scope">
							<div v-if="!scope.row.is_top_row">
								<div class="orange">{{ scope.row.pay_price }}</div>
								<p class="gray9" v-if="scope.row.service_money>0">(含服务费：{{ scope.row.service_money }})
								</p>
								<p class="gray9" v-if="scope.row.bag_price>0">(含包装费：{{ scope.row.bag_price }})</p>
								<p class="red" v-if="scope.row.refund_money>0">(已退款：{{ scope.row.refund_money }})</p>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="" label="买家">
						<template #default="scope">
							<div v-if="!scope.row.is_top_row&&scope.row.user">
								<div>{{ scope.row.user.nickName }}</div>
								<div class="gray9">ID：({{ scope.row.user.user_id }})</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="supplier.name" label="门店名称"></el-table-column>
					<el-table-column prop="state_text" label="交易状态">
						<template #default="scope">
							<div v-if="!scope.row.is_top_row">
								{{ scope.row.state_text }}
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="pay_type.text" label="支付方式">
						<template #default="scope">
							<div v-if="!scope.row.is_top_row">
								<span class="gray9">{{ scope.row.pay_type.text }}</span>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="delivery_type.text" label="配送方式">
						<template #default="scope">
							<div v-if="!scope.row.is_top_row">
								<span class="green">{{ scope.row.delivery_type.text }}</span>
								<div class="red" v-if="scope.row.callNo">取餐码：{{ scope.row.callNo }}</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column fixed="right" label="操作" width="160">
						<template #default="scope">
							<div v-if="!scope.row.is_top_row">
								<el-button @click="addClick(scope.row)" type="text" size="small"
									v-auth="'/store/order/detail'">订单详情
								</el-button>
								<el-button
									v-if="scope.row.order_status.value==10&&scope.row.pay_status.value==20&&scope.row.refund_money==0"
									@click="refundClick(scope.row)" type="text" size="small"
									v-auth="'/store/operate/refund'">退款</el-button>
								<el-button v-if="scope.row.order_status.value==10&&scope.row.pay_status.value==20"
									@click="cancelClick(scope.row)" type="text" size="small"
									v-auth="'/store/operate/orderCancel'">取消
								</el-button>
								<el-button v-if="scope.row.order_status.value==10&&scope.row.pay_status.value==20"
									@click="verifyClick(scope.row)" type="text" size="small"
									v-auth="'/store/operate/extract'">核销
								</el-button>
								<el-button
									v-if="scope.row.pay_type.value == 20 &&scope.row.pay_status.value == 20 && scope.row.wx_delivery_status==10 && scope.row.pay_source=='wx' && is_send_wx==true"
									@click="wxdeliveryClick(scope.row)" link type="primary" size="small"
									v-auth="'/store/order/wxDelivery'">小程序发货</el-button>
							</div>
						</template>
					</el-table-column>
				</el-table>
			</div>

			<!--分页-->
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background
					:current-page="curPage" :page-size="pageSize" layout="total, prev, pager, next, jumper"
					:total="totalDataNumber"></el-pagination>
			</div>
		</div>
		<!--处理-->
		<Cancel v-if="open_edit" :open_edit="open_edit" :order_no="order_no" :order_id="order_id"
			@closeDialog="closeDialogFunc($event, 'edit')">
		</Cancel>
		<!--处理-->
		<refund v-if="open_refund" :open_edit="open_refund" :order_no="order_no" :order_id="order_id"
			@closeDialog="closerefundDialogFunc($event, 'edit')">
		</refund>
	</div>
</template>

<script>
	import OrderApi from '@/api/order.js';
	import Cancel from './dialog/cancel.vue';
	import refund from './dialog/refund.vue';
	import qs from 'qs';
	import {
		useUserStore
	} from '@/store';
	export default {
		components: {
			Cancel,
			refund,
		},
		data() {
			return {
				/*切换菜单*/
				activeName: 'all',
				/*是否加载完成*/
				loading: true,
				/*列表数据*/
				tableData: [],
				/*一页多少条*/
				pageSize: 20,
				/*一共多少条数据*/
				totalDataNumber: 0,
				/*当前是第几页*/
				curPage: 1,
				/*横向表单数据模型*/
				searchForm: {
					order_no: '',
					style_id: '',
					create_time: ''
				},
				/*配送方式*/
				exStyle: [],
				/*门店列表*/
				shopList: [],
				/*时间*/
				create_time: '',
				/*统计*/
				order_count: {
					all: 0,
					payment: 0,
					delivery: 0,
					received: 0,
					cancel: 0,
					refund: 0
				},
				/*是否打开编辑弹窗*/
				open_edit: false,
				open_refund: false,
				/*当前编辑的对象*/
				order_no: 0,
				order_id: 0,
				/*小程序发货*/
				is_send_wx: '',
			};
		},
		created() {
			/*获取列表*/
			this.getData();
		},
		methods: {
			/*跨多列*/
			arraySpanMethod(row) {
				if (row.rowIndex % 2 == 0) {
					if (row.columnIndex === 0) {
						return [1, 8];
					}
				}
			},
			/*选择第几页*/
			handleCurrentChange(val) {
				let self = this;
				self.curPage = val;
				self.getData();
			},

			/*每页多少条*/
			handleSizeChange(val) {
				this.curPage = 1;
				this.pageSize = val;
				this.getData();
			},

			/*切换菜单*/
			handleClick(tab, event) {
				let self = this;
				self.curPage = 1;
				self.getData();
			},

			/*获取列表*/
			getData() {
				let self = this;
				let Params = this.searchForm;
				Params.dataType = self.activeName;
				Params.page = self.curPage;
				Params.list_rows = self.pageSize;
				self.loading = true;
				OrderApi.storeOrderlist(Params, true)
					.then(res => {
						let list = [];
						for (let i = 0; i < res.data.list.data.length; i++) {
							let item = res.data.list.data[i];
							let topitem = {
								order_no: item.order_no,
								create_time: item.create_time,
								order_source: item.order_source,
								order_source_text: item.order_source_text,
								is_top_row: true,
								order_status: item.order_status.value,
							};
							list.push(topitem);
							list.push(item);
						}
						self.tableData.data = list;
						self.totalDataNumber = res.data.list.total;
						self.exStyle = res.data.ex_style;
						self.order_count = res.data.order_count.order_count;
						self.is_send_wx = res.data.is_send_wx;
						self.loading = false;
					})
					.catch(error => {});
			},

			/*打开添加*/
			addClick(row) {
				let self = this;
				let params = row.order_id;
				self.$router.push({
					path: '/store/order/detail',
					query: {
						order_id: params
					}
				});
			},
			/*核销*/
			verifyClick(row) {
				let self = this;
				let extract_form = {};
				ElMessageBox.confirm('确定要核销吗?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					extract_form.order_id = row.order_id;
					OrderApi.storeExtract(extract_form,
							true
						)
						.then(data => {
							self.loading = false;
							ElMessage({
								message: '恭喜你，操作成功',
								type: 'success'
							});
							self.getData();
						})
						.catch(error => {
							self.loading = false;
						});
				}).catch(() => {
					ElMessage({
						type: 'info',
						message: '已取消核销'
					});
				});

			},
			getLogistics(row) {
				let self = this;
				let Params = {
					order_id: row.order_id,
				}
				self.loading = true;
				OrderApi.queryLogistics(Params, true)
					.then(res => {
						self.logisticsData = res.data.express.list;
						self.loading = false;
						self.openLogistics()
					})
					.catch(error => {
						self.loading = false;
					});
			},
			openLogistics() {
				this.isLogistics = true;
			},
			closeLogistics() {
				this.isLogistics = false;
			},
			wxdeliveryClick(row) {
				let self = this;
				let order_id = row.order_id;
				ElMessageBox.confirm('确定发货吗?', '提示', {
					type: 'warning'
				}).then(() => {
					OrderApi.storeWxDelivery({
								order_id
							},
							true
						)
						.then(data => {
							self.loading = false;
							ElMessage({
								message: '恭喜你，操作成功',
								type: 'success'
							});
							self.getData();
						})
						.catch(error => {
							self.loading = false;
						});
				}).catch(() => {
					ElMessage({
						type: 'info',
						message: '已取消发货'
					});
				});
			
			},
			/*搜索查询*/
			onSubmit() {
				this.curPage = 1;
				this.tableData = [];
				this.getData();
			},
			onExport: function() {
				let baseUrl = window.location.protocol + '//' + window.location.host;
				const {
					token,
					userInfo
				} = useUserStore();
				this.searchForm.token = token;
				this.searchForm.AppID = userInfo.AppID;
				window.location.href = baseUrl + '/index.php/shop/store.operate/export?' + qs.stringify(this
					.searchForm);
			},
			/*打开取消*/
			cancelClick(item) {
				this.order_no = item.order_no;
				this.order_id = item.order_id;
				this.open_edit = true;
			},
			refundClick(item) {
				this.order_no = item.order_no;
				this.order_id = item.order_id;
				this.open_refund = true;
			},
			/*关闭弹窗*/
			closeDialogFunc(e, f) {
				if (f == 'edit') {
					this.open_edit = e.openDialog;
					if (e.type == 'success') {
						this.getData();
					}
				}
			},
			/*关闭弹窗*/
			closerefundDialogFunc(e, f) {
				if (f == 'edit') {
					this.open_refund = e.openDialog;
					if (e.type == 'success') {
						this.getData();
					}
				}
			},
		}
	};
</script>
<style lang="scss">
	.product-info {
		padding: 10px 0;
		border-top: 1px solid #eeeeee;
	}

	.order-code .state-text {
		padding: 2px 4px;
		border-radius: 4px;
		background: #808080;
		color: #ffffff;
	}

	.order-code .state-text-red {
		background: red;
	}

	.table-wrap .product-info:first-of-type {
		border-top: none;
	}

	.table-wrap .el-table__body tbody .el-table__row:nth-child(odd) {
		background: #f5f7fa;
	}
</style>