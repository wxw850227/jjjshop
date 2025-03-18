<template>
	<div class="product-add mt30">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<div class="buy-set-content pl16 pr16">
				<div class="common-form">隐私协议</div>
				<el-form-item label="">
					<div class="edit_container">
						<Uediter :text="form.privacy" :config="ueditor.config" ref="ue"
							@contentChange="contentChangeFunc" :id="ueditor.id">
						</Uediter>
					</div>
				</el-form-item>
			</div>
			<div class="common-button-wrapper">
				<el-button type="primary" @click="onSubmit" :loading="loading">提交</el-button>
			</div>
		</el-form>
	</div>
</template>

<script>
	import Uediter from '@/components/UE.vue';
	import SettingApi from '@/api/setting.js';
	export default {
		components: {
			/*编辑器*/
			Uediter,
		},
		data() {
			return {
				/*富文本配置*/
				ueditor: {
					text: '',
					config: {
						initialFrameWidth: 400,
						initialFrameHeight: 500
					},
					id: 'privacy'
				},
			};
		},
		props: {
			settingData: Object,
		},
		created() {
			this.form = this.settingData.privacy;
		},
		methods: {
			/*获取富文本内容*/
			contentChangeFunc(e) {
				this.form.privacy = e;
			},
			/*提交*/
			onSubmit() {
				let self = this;
				let params = {};
				params.type = 'privacy';
				params.value = self.form.privacy;
				self.$refs.form.validate((valid) => {
					if (valid) {
						self.loading = true;
						SettingApi.editProtocol(params, true)
							.then(data => {
								self.loading = false;
								ElMessage({
									message: '恭喜你，设置成功',
									type: 'success'
								});
								self.$router.push('/setting/protocol/index');
							})
							.catch(error => {
								self.loading = false;
							});
					}
				});

			},
		}
	};
</script>

<style></style>