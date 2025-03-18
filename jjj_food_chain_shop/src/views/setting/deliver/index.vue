<template>
	<!--
      作者 luoyiming
      时间：2019-10-26
      描述：设置-配送方式设置
  -->
	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<!--配送方式设置-->
			<div class="common-form">配送方式设置</div>
			<el-form-item label="配送方式">
				<div>
					<el-radio v-model="form.radio" label="local" @change="getRadio">商家配送</el-radio>
				</div>
			</el-form-item>
			<div v-if="form.radio == 'local'">
				<el-form-item label="自动配送时间(分钟)">
					<el-input v-model="form.engine.local.time" class="max-w460"></el-input>
					<div class="tips">设置0不自动配送，下单后未配送，设置时间后自动操作配送</div>
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
					engine: {
						local: {
							time: '',
						},
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
				SettingApi.deliverDetail({}, true)
					.then(data => {
						let vars = data.data.vars.values;
						self.form.radio = vars.default;
						self.form.engine.local = vars.engine.local;
					})
					.catch(error => {});
			},
			//提交表单
			onSubmit() {
				let self = this;
				self.loading = true;
				let params = this.form;
				SettingApi.editDeliver(params, true)
					.then(data => {
						self.loading = false;
						ElMessage({
							message: '恭喜你，上传设置成功',
							type: 'success'
						});
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