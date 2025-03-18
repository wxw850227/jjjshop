<template>
	<!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：区域-修改
    -->
	<el-dialog title="修改类型" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
		:close-on-press-escape="false">
		<el-form size="small" :model="form" :rules="formRules" ref="form">
			<el-form-item label="类型名称" prop="type_name" :label-width="formLabelWidth">
				<el-input v-model="form.type_name" autocomplete="off"></el-input>
			</el-form-item>
			<el-form-item label="最少人数" prop="min_num" :label-width="formLabelWidth">
				<el-input v-model.number="form.min_num" autocomplete="off"></el-input>
			</el-form-item>
			<el-form-item label="最多人数" prop="max_num" :label-width="formLabelWidth">
				<el-input v-model.number="form.max_num" autocomplete="off"></el-input>
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
					type_id: 0,
					type_name: '',
					min_num: 1,
					max_num: 1,
					sort: 100,
				},
				file_path: '',
				formRules: {
					type_name: [{
						required: true,
						message: '请输入类型名称',
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
		props: ['open_edit', 'editform'],
		created() {
			this.dialogVisible = this.open_edit;
			this.form.type_id = this.editform.model.type_id;
			this.form.type_name = this.editform.model.type_name;
			this.form.min_num = this.editform.model.min_num;
			this.form.max_num = this.editform.model.max_num;
			this.form.sort = this.editform.model.sort;
		},
		methods: {
			/*修改用户*/
			addUser() {
				let self = this;
				let params = self.form;
				self.$refs.form.validate((valid) => {
					if (valid) {
						self.loading = true;
						StoreApi.editType(params, true).then(data => {
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