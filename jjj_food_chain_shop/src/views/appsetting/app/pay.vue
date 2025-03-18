<template>
	<!--
      作者：luoyiming
      时间：2019-10-25
      描述：应用-基础设置
  -->
	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<div class="common-form">微信支付设置</div>

			<el-form-item label="微信支付商户号 MCHID">
				<el-input v-model="form.mchid" class="max-w460"></el-input>
			</el-form-item>
			<el-form-item label="微信支付密钥 APIKEY ">
				<el-input v-model="form.apikey" class="max-w460"></el-input>
			</el-form-item>
			<el-form-item label="证书序列号 SERIAL">
				<el-input v-model="form.serial_no" class="max-w460"></el-input>
			</el-form-item>

			<el-form-item label="apiclient_cert.pem">
				<el-input type="textarea" :rows="4" placeholder="使用文本编辑器打开apiclient_cert.pem文件，将文件的全部内容复制进来"
					v-model="form.cert_pem" class="max-w460"></el-input>
				<div class="tips">使用文本编辑器打开apiclient_key.pem文件，将文件的全部内容复制进来</div>
			</el-form-item>
			<el-form-item label="apiclient_key.pem">
				<el-input type="textarea" :rows="4" placeholder="使用文本编辑器打开apiclient_cert.pem文件，将文件的全部内容复制进来"
					v-model="form.key_pem" class="max-w460"></el-input>
				<div class="tips">使用文本编辑器打开apiclient_key.pem文件，将文件的全部内容复制进来</div>
			</el-form-item>
			<!--提交-->
			<div class="common-button-wrapper"><el-button type="primary" @click="onSubmit">提交</el-button></div>
		</el-form>
	</div>
</template>

<script>
	import AppSettingApi from '@/api/appsetting.js';
	import {
		formatModel
	} from '@/utils/base.js'
	export default {
		data() {
			return {
				form: {
					mchid: '',
					apikey: '',
					cert_pem: '',
					key_pem: '',
					serial_no: ''
				},
				app: {},
			};
		},
		created() {
			this.getData();
		},

		methods: {
			/*获取数据*/
			getData() {
				let self = this;
				AppSettingApi.payDetail({}, true)
					.then(res => {
						self.app = res.data.app;
						self.form = formatModel(self.form, res.data.app);
						console.log(self.form);
					})
					.catch(error => {
						console.log(error);
					});
			},

			//提交表单
			onSubmit() {
				let self = this;
				let params = self.form;
				AppSettingApi.editPay(params, true)
					.then(data => {
						ElMessage({
							message: '恭喜你，设置成功',
							type: 'success'
						});
					})
					.catch(error => {});
			},
		}
	};
</script>

<style>
</style>