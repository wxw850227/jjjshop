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
					<el-select size="small" v-model="searchForm.deliver_source" placeholder="请选择">
						<el-option label="全部" value=""></el-option>
						<el-option v-for="(item, index) in deliver_source" :key="index" :label="item.name"
							:value="item.value">
						</el-option>
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
				<el-table size="small" :data="tableData.data" border style="width: 100%" v-loading="loading">
					<el-table-column prop="order_no" label="订单号"></el-table-column>
					<el-table-column prop="orders.pay_price" label="订单金额"></el-table-column>
					<el-table-column prop="price" label="配送费"></el-table-column>
					<el-table-column prop="status_text" label="订单状态"></el-table-column>
					<el-table-column prop="deliver_status_text" label="配送状态"></el-table-column>
					<el-table-column prop="deliver_source_text" label="配送方式"></el-table-column>
					<el-table-column prop="linkman" label="骑手姓名"></el-table-column>
					<el-table-column prop="phone" label="骑手电话"></el-table-column>
					<el-table-column prop="create_time" label="操作时间" width="140"></el-table-column>
					<el-table-column fixed="right" label="操作" width="120">
						<template #default="scope">
							<el-button @click="detailClick(scope.row)" type="text" size="small"
								v-auth="'/takeout/deliver/detail'">详情
							</el-button>
							<el-button @click="cancelClick(scope.row)"
								v-if="scope.row.deliver_source==20||scope.row.deliver_source==40||scope.row.deliver_source==50&&scope.row.status==10"
								type="text" size="small" v-auth="'/takeout/deliver/cancel'">取消配送
							</el-button>
							<el-button @click="verifyClick(scope.row)"
								v-if="scope.row.deliver_source==10&&scope.row.status==10" type="text" size="small"
								v-auth="'/takeout/deliver/verify'">确认送达
							</el-button>
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
		<Cancel v-if="open_edit" :open_edit="open_edit" :deliver_id="deliver_id" :order_no="order_no"
			@closeDialog="closeDialogFunc($event, 'edit')">
		</Cancel>
	</div>
</template>

<script>
	import TakeOutApi from '@/api/takeout.js';
	import Cancel from './dialog/cancel.vue';
	import qs from 'qs';
	import {
		useUserStore
	} from '@/store';
	export default {
		components: {
			Cancel,
		},
		data() {
			return {
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
					deliver_source: '',
					create_time: ''
				},
				/*时间*/
				create_time: '',
				/*是否打开编辑弹窗*/
				open_edit: false,
				/*当前编辑的对象*/
				order_no: 0,
				deliver_id: 0,
				deliver_source: []
			};
		},
		created() {
			/*获取列表*/
			this.getData();
		},
		methods: {
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
			/*获取列表*/
			getData() {
				let self = this;
				let Params = this.searchForm;
				Params.page = self.curPage;
				Params.list_rows = self.pageSize;
				self.loading = true;
				TakeOutApi.deliverList(Params, true)
					.then(res => {
						self.tableData.data = res.data.list.data;
						self.totalDataNumber = res.data.list.total;
						self.deliver_source = res.data.deliver_source;
						self.loading = false;
					})
					.catch(error => {});
			},
			/*打开添加*/
			detailClick(row) {
				let self = this;
				let params = row.deliver_id;
				self.$router.push({
					path: '/takeout/deliver/detail',
					query: {
						deliver_id: params
					}
				});
			},
			/*确认送达*/
			verifyClick(row) {
				let self = this;
				ElMessageBox.confirm('此操作将确认送达, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					self.loading = true;
					TakeOutApi.verify({
							deliver_id: row.deliver_id
						}, true)
						.then(data => {
							self.loading = false;
							if (data.code == 1) {
								ElMessage({
									message: '恭喜你，操作成功',
									type: 'success'
								});
								self.getData();
							}
						})
						.catch(error => {
							self.loading = false;
						});

				}).catch(() => {
					self.loading = false;
				});
			},
			/*打开取消*/
			cancelClick(item) {
				this.deliver_id = item.deliver_id;
				this.order_no = item.order_no;
				this.open_edit = true;
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
				window.location.href = baseUrl + '/index.php/shop/takeout.deliver/export?' + qs.stringify(this
					.searchForm);
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