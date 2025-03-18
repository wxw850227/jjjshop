<template>
	<!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：区域-修改
    -->
	<el-dialog title="添加类型" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
		:close-on-press-escape="false">
		<el-form size="small" :model="form" :rules="formRules" ref="form">
			<el-form-item label="桌位编号" prop="table_no" :label-width="formLabelWidth">
				<el-input v-model="form.table_no" autocomplete="off"></el-input>
			</el-form-item>
			<el-form-item label="类型名称" prop="type_id" :label-width="formLabelWidth">
				<el-select v-model="form.type_id" placeholder="类型名称">
					<el-option v-for="item in type" :key="item.type_id" :label="item.type_name" :value="item.type_id">
					</el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="所属区域" prop="area_id" :label-width="formLabelWidth">
				<el-select v-model="form.area_id" placeholder="所属区域">
					<el-option v-for="item in area_list" :key="item.area_id" :label="item.area_name"
						:value="item.area_id">
					</el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="排序" prop="sort" :label-width="formLabelWidth">
				<el-input v-model.number="form.sort" autocomplete="off"></el-input>
			</el-form-item>
		</el-form>
		<template #footer>
			<div class="dialog-footer">
				<el-button @click="dialogFormVisible">取 消</el-button>
				<el-button type="primary" @click="addUser" :loading="loading">确 定</el-button>
			</div>
		</template>
	</el-dialog>
</template>

<script>
	import StoreApi from '@/api/store.js';
	export default {
		components: {},
		data() {
			return {
				form: {
					table_id: 0,
					table_no: '',
					area_id: 1,
					type_id: 1,
					sort: 100,
				},
				file_path: '',
				formRules: {
					table_no: [{
						required: true,
						message: '请输入桌位编号',
						trigger: 'blur'
					}],
					area_id: [{
						required: true,
						message: '请选择类型名称',
						trigger: 'blur'
					}],
					type_id: [{
						required: true,
						message: '请选择所属区域',
						trigger: 'blur'
					}],
					sort: [{
						required: true,
						message: '排序不能为空'
					}, {
						type: 'number',
						message: '排序必须为数字'
					}]
				},
				/*左边长度*/
				formLabelWidth: '120px',
				/*是否显示*/
				dialogVisible: false,
				loading: false,
				/*是否上传图片*/
				isupload: false,
			};
		},
		props: ['open_edit', 'editform', 'type', 'area_list'],
		created() {
			this.dialogVisible = this.open_edit;
			this.form.table_id = this.editform.model.table_id;
			this.form.table_no = this.editform.model.table_no;
			this.form.area_id = this.editform.model.area_id;
			this.form.type_id = this.editform.model.type_id;
			this.form.sort = this.editform.model.sort;
		},
		methods: {
			/*修改用户*/
			addUser() {
				let self = this;
				let params = self.form;
				console.log(params)
				self.$refs.form.validate((valid) => {
					if (valid) {
						self.loading = true;
						StoreApi.editTable(params, true).then(data => {
							self.loading = false;
							ElMessage({
								message: '修改成功',
								type: 'success'
							});
							self.dialogFormVisible(true);
						}).catch(error => {
							self.loading = false;
						});
					}
				});
			},
			/*关闭弹窗*/
			dialogFormVisible(e) {
				if (e) {
					this.$emit('closeDialog', {
						type: 'success',
						openDialog: false
					})
				} else {
					this.$emit('closeDialog', {
						type: 'error',
						openDialog: false
					})
				}
			},
		}
	};
</script>

<style scoped>
	.img {
		margin-top: 10px;
	}
</style>