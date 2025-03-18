<template>
	<el-dialog title="修改密码" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
		:close-on-press-escape="false" width="30%">
		<el-form size="small" :model="form" ref="form">
			<el-form-item label="原始密码" :label-width="formLabelWidth" prop="oldpass"
				:rules="[{required: true,message: ' '}]">
				<el-input type="password" v-model="form.oldpass" autocomplete="off"></el-input>
			</el-form-item>
			<el-form-item label="新密码" :label-width="formLabelWidth" prop="password"
				:rules="[{required: true,message: ' '}]">
				<el-input type="password" v-model="form.password" autocomplete="off"></el-input>
			</el-form-item>
			<el-form-item label="确认新密码" :label-width="formLabelWidth" prop="confirmPass"
				:rules="[{required: true,message: ' '}]">
				<el-input type="password" v-model="form.confirmPass" autocomplete="off"></el-input>
			</el-form-item>
		</el-form>
		<template #footer>
			<el-button @click="dialogFormVisible">取 消</el-button>
			<el-button type="primary" @click="submitFunc(form.user_id)" :loading="loading">确 定</el-button>
		</template>
	</el-dialog>
</template>

<script>
import UserApi from '@/api/user.js';
export default {
	data() {
		return {
			loading: false,
			/*左边长度*/
			formLabelWidth: '100px',
			/*是否显示*/
			dialogVisible: true,
			/*表单*/
			form: {

			}
		};
	},
	props: [],
	created() {},
	methods: {
		/*确认事件*/
		submitFunc(e) {
			let self = this;
			let form = self.form;
			self.$refs.form.validate((valid) => {
				if (valid) {
					self.loading = true;
					UserApi.EditPass(form, true).then(data => {
						self.loading = false;
						if (data.code == 1) {
							ElMessage({
								message: data.msg,
								type: 'success'
							});
							self.dialogFormVisible(true);
						} else {
							self.loading = false;
						}
					})
						.catch(error => {
							self.loading = false;
						});
				}
			});
		},

		/*关闭弹窗*/
		dialogFormVisible() {
			this.$emit('close', false);
		}
	}
};
</script>

<style></style>