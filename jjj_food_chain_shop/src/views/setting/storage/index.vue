<template>
	<!--
      作者 luoyiming
      时间：2019-10-26
      描述：设置-上传设置
  -->
	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<!--文件上传设置-->
			<div class="common-form">文件上传设置</div>
			<el-form-item label="最大图片上传">
				<el-input v-model="form.max_image" class="max-w460">
					<template #append>M</template>
				</el-input>
				<div class="tips">修改后请修改php上传配置后生效</div>
			</el-form-item>
			<el-form-item label="默认上传方式">
				<div>
					<el-radio v-model="form.radio" label="local" @change="getRadio">本地 (不推荐)</el-radio>
					<el-radio v-model="form.radio" label="qiniu" @change="getRadio">七牛云存储</el-radio>
					<el-radio v-model="form.radio" label="aliyun" @change="getRadio">阿里云OSS</el-radio>
					<el-radio v-model="form.radio" label="qcloud" @change="getRadio">腾讯云COS</el-radio>
				</div>
			</el-form-item>
			<!--七牛云存储-->
			<div v-if="form.radio == 'qiniu'">
				<el-form-item label="存储空间名称 Bucket">
					<el-input v-model="form.engine.qiniu.bucket" class="max-w460"></el-input>
				</el-form-item>
				<el-form-item label="ACCESS_KEY AK">
					<el-input v-model="form.engine.qiniu.access_key" class="max-w460"></el-input>
				</el-form-item>
				<el-form-item label="SECRET_KEY SK">
					<el-input v-model="form.engine.qiniu.secret_key" class="max-w460"></el-input>
				</el-form-item>
				<el-form-item label="空间域名 Domain">
					<el-input v-model="form.engine.qiniu.domain" class="max-w460"></el-input>
					<div class="tips">请补全http:// 或 https://，例如：http://static.cloud.com</div>
				</el-form-item>
			</div>
			<!--阿里云OSS-->
			<div v-if="form.radio == 'aliyun'">
				<el-form-item label="存储空间名称 Bucket">
					<el-input v-model="form.engine.aliyun.bucket" class="max-w460"></el-input>
				</el-form-item>
				<el-form-item label="AccessKeyId">
					<el-input v-model="form.engine.aliyun.access_key_id" class="max-w460"></el-input>
				</el-form-item>
				<el-form-item label="AccessKeySecret">
					<el-input v-model="form.engine.aliyun.access_key_secret" class="max-w460"></el-input>
				</el-form-item>
				<el-form-item label="空间域名 Domain">
					<el-input v-model="form.engine.aliyun.domain" class="max-w460"></el-input>
					<div class="tips">请补全http:// 或 https://，例如：http://static.cloud.com</div>
				</el-form-item>
			</div>
			<!--腾讯云COS-->
			<div v-if="form.radio == 'qcloud'">
				<el-form-item label="存储空间名称 Bucket">
					<el-input v-model="form.engine.qcloud.bucket"></el-input>
				</el-form-item>
				<el-form-item label="所属地域 Region">
					<el-input v-model="form.engine.qcloud.region"></el-input>
					<div class="tips">请填写地域简称，例如：ap-beijing、ap-hongkong、eu-frankfurt</div>
				</el-form-item>
				<el-form-item label="SecretId">
					<el-input v-model="form.engine.qcloud.secret_id"></el-input>
				</el-form-item>
				<el-form-item label="SecretKey">
					<el-input v-model="form.engine.qcloud.secret_key"></el-input>
				</el-form-item>
				<el-form-item label="空间域名 Domain">
					<el-input v-model="form.engine.qcloud.domain"></el-input>
					<div class="tips">请补全http:// 或 https://，例如：http://static.cloud.com</div>
				</el-form-item>
			</div>

			<!--提交-->
			<div class="common-button-wrapper">
				<el-button type="primary" @click="onSubmit" :loading="loading">提交</el-button>
			</div>
		</el-form>
	</div>
</template>

<script>
	import SettingApi from '@/api/setting.js';

	export default {
		data() {
			return {
				/*切换菜单*/
				// activeIndex: '1',
				/*form表单数据*/
				form: {
					radio: 'local',
					max_image: '',
					engine: {
						qiniu: {
							// domain: '',
							// access_key: '',
							// secret_key: '',
							// bucket: '',
						},
						aliyun: {
							// domain: '',
							// access_key_id: '',
							// access_key_secret: '',
							// bucket: '',
						},
						qcloud: {
							// domain: '',
							// region: '',
							// secret_id: '',
							// secret_key: '',
							// bucket: '',
						}
					}
				},
				loading: false
			};
		},
		created() {
			this.getData();
		},

		methods: {
			getData() {
				let self = this;
				SettingApi.storageDetail({}, true)
					.then(data => {
						let vars = data.data.vars.values;
						self.form.radio = vars.default;
						self.form.max_image = vars.max_image;
						self.form.engine.qiniu = vars.engine.qiniu;
						self.form.engine.aliyun = vars.engine.aliyun;
						self.form.engine.qcloud = vars.engine.qcloud;
					})
					.catch(error => {});
			},
			//提交表单
			onSubmit() {
				let self = this;
				self.loading = true;
				let params = this.form;
				SettingApi.editStorage(params, true)
					.then(data => {
						self.loading = false;
						ElMessage({
							message: '恭喜你，上传设置成功',
							type: 'success'
						});
						// self.$router.push('/setting/Storage');
					})
					.catch(error => {
						self.loading = false;
					});
			},
			//监听单选按钮
			getRadio(val) {}
		}
	};
</script>

<style scoped>
	.tips {
		color: #ccc;
	}
</style>