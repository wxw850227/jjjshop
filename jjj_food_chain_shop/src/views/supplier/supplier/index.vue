<template>
	<!--
          作者：luoyiming
          时间：2019-10-25
          描述：门店-门店管理
      -->
	<div class="user clearfix">
		<div class="common-seach-wrap fr" v-auth="'/auth/shop'">
			<el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
				<el-form-item label="">
					<el-input v-model="formInline.search" placeholder="请输入门店名称"></el-input>
				</el-form-item>

				<el-form-item>
					<el-button type="primary" icon="Search" @click="onSubmit">查询</el-button>
				</el-form-item>
			</el-form>
		</div>
		<!--内容-->
		<div class="product-content">
			<div class="table-wrap">
				<el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading">
					<el-table-column prop="shop_supplier_id" label="门店ID" width="120"></el-table-column>
					<el-table-column prop="name" label="门店名称">
						<template #default="scope">
							<span>{{scope.row.name}}</span>
						</template>
					</el-table-column>
					<el-table-column prop="superUser.user_name" label="登录账号"></el-table-column>
					<el-table-column prop="link_name" label="联系人" width="120"></el-table-column>
					<el-table-column prop="link_phone" label="联系电话" width="120"></el-table-column>
					<el-table-column prop="address" label="联系地址"></el-table-column>
					<el-table-column prop="create_time" label="添加时间" width="150"></el-table-column>
					<el-table-column fixed="right" label="操作" width="150">
						<template #default="scope">
							<el-button @click="editClick(scope.row)" type="text" size="small"
								v-auth="'/supplier/supplier/edit'">编辑</el-button>
							<el-button @click="settingClick(scope.row)" type="text" size="small"
								v-auth="'/supplier/supplier/setting'">设置</el-button>
						</template>
					</el-table-column>
				</el-table>
			</div>

			<!--分页-->
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background
					:current-page="curPage" :page-size="pageSize" layout="total, prev, pager, next, jumper"
					:total="totalDataNumber">
				</el-pagination>
			</div>
		</div>
	</div>
</template>

<script>
	import SupplierApi from '@/api/supplier.js';
	export default {
		components: {},
		data() {
			return {
				/*是否加载完成*/
				loading: true,
				/*列表数据*/
				tableData: [],
				/*门店列表数据*/
				storeList: [],
				/*一页多少条*/
				pageSize: 20,
				/*一共多少条数据*/
				totalDataNumber: 0,
				/*当前是第几页*/
				curPage: 1,
				/*横向表单数据模型*/
				formInline: {
					shop_id: '',
					search: ''
				},
				/*是否打开添加弹窗*/
				open_add: false,
				/*是否打开编辑弹窗*/
				open_edit: false,
				is_chain: '',
			};
		},
		created() {

			/*获取列表*/
			this.getTableList();

		},
		methods: {

			/*选择第几页*/
			handleCurrentChange(val) {
				let self = this;
				self.curPage = val;
				self.loading = true;
				self.getTableList();
			},

			/*每页多少条*/
			handleSizeChange(val) {
				this.curPage = 1;
				this.pageSize = val;
				this.getTableList();
			},

			/*获取列表*/
			getTableList() {
				let self = this;
				let Params = {};
				Params.page = self.curPage;
				Params.list_rows = self.pageSize;
				SupplierApi.supplierList(Params, true)
					.then(res => {
						self.loading = false;
						self.tableData = res.data.list.data;
						self.totalDataNumber = res.data.list.total;
						self.is_chain = res.data.is_chain;
					})
					.catch(error => {
						self.loading = false;
					});
			},

			/*搜索查询*/
			onSubmit() {
				this.curPage = 1;
				this.getTableList();
			},
			/*打开编辑*/
			editClick(row) {
				let self = this;
				let params = row.shop_supplier_id;
				this.$router.push({
					path: '/supplier/supplier/edit',
					query: {
						shop_supplier_id: params
					}
				})
			},
			/*打开编辑*/
			settingClick(row) {
				let self = this;
				let params = row.shop_supplier_id;
				this.$router.push({
					path: '/supplier/supplier/setting',
					query: {
						shop_supplier_id: params
					}
				})
			},

		}
	};
</script>

<style></style>