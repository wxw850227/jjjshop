<template>
	<!--
      作者：luoyiming
      时间：2019-10-25
      描述：应用-小程序设置
  -->
	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="250px">
			<!--小程序设置-->
			<div class="common-form">小程序设置</div>
			<el-form-item label="AppID 小程序ID">
				<el-input v-model="form.wxapp_id" class="max-w460"></el-input>
			</el-form-item>
			<el-form-item label="AppSecret 小程序密钥">
				<el-input v-model="form.wxapp_secret" type="password" class="max-w460"></el-input>
			</el-form-item>
			<!--提交-->
			<div class="common-button-wrapper">
				<el-button type="primary" @click="onSubmit">提交</el-button>
			</div>
		</el-form>


	</div>

</template>

<script>
	import AppSettingApi from '@/api/appsetting.js';

	export default {
		data() {
			return {
				/*切换菜单*/
				// activeIndex: '1',
				/*form表单数据*/
				form: {},


			};
		},
		created() {
			this.getData()
		},

		methods: {
			getData() {
				let self = this;
				AppSettingApi.appwxDetail({}, true)
					.then(data => {
						if (data.data.data != null) {
							self.form = data.data.data;
						}
					})
					.catch(error => {});

			},
			//提交表单
			onSubmit() {
				let self = this;
				let params = this.form;
				AppSettingApi.editAppWx(params, true)
					.then(data => {
						ElMessage({
							message: '恭喜你，设置成功',
							type: 'success'
						});
						self.$router.push('/appsetting/appwx/index');
					})
					.catch(error => {

					});
			},
		}

	};
</script>

<style scoped>
	.tips {
		color: #ccc;
	}
</style>