<template>
	<!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：区域-修改
    -->
	<el-dialog title="修改区域" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
		:close-on-press-escape="false">
		<el-form size="small" :model="form" :rules="formRules" ref="form">
			<el-form-item label="区域名称" prop="name" :label-width="formLabelWidth">
				<el-input v-model="form.area_name" autocomplete="off"></el-input>
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
					area_id: 0,
					area_name: '',
					sort: ''
				},
				file_path: '',
				formRules: {
					area_name: [{
						required: true,
						message: '请输入区域名称',
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
			this.form.area_id = this.editform.model.area_id;
			this.form.area_name = this.editform.model.area_name;
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
						StoreApi.editArea(params, true).then(data => {
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