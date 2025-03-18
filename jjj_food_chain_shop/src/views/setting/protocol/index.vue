<template>
	<div class="product-add" v-if="!loading">
		<el-tabs v-model="activeName" type="card">
			<el-tab-pane label="用户协议" name="service"></el-tab-pane>
			<el-tab-pane label="隐私协议" name="privacy"></el-tab-pane>
		</el-tabs>
		<!--用户协议-->
		<Service v-show="activeName == 'service'" :settingData="settingData"></Service>
		<!--隐私协议-->
		<Privacy v-show="activeName == 'privacy'" :settingData="settingData"></Privacy>
	</div>
</template>
<script>
	import SettingApi from '@/api/setting.js';
	import Service from './part/service.vue';
	import Privacy from './part/privacy.vue';
	export default {
		components: {
			Service,
			Privacy
		},
		data() {
			return {
				activeName: 'service',
				/*是否正在加载*/
				loading: true,
				/*form表单数据*/
				form: {
					service: '',
					privacy: ''
				},
			};
		},
		mounted() {
			this.getParams()
		},
		methods: {
			/*获取配置数据*/
			getParams() {
				let self = this;
				SettingApi.protocolDetail({}, true).then(res => {
						let vars = res.data.vars;
						self.settingData = vars;
						self.loading = false;
					})
					.catch(error => {
						self.loading = false;
					});
			},
		}

	};
</script>
<style>
</style>