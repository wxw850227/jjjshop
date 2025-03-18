<template>
	<!--
      作者 luoyiming
      时间：2019-10-26
      描述：设置-清理缓存
  -->
	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<!--清理缓存-->
			<div class="common-form">清理缓存</div>


			<el-form-item label="数据缓存:">
				<el-checkbox-group v-model="form.keys" @change="handleCheckedCitiesChange">
					<el-checkbox v-for="(item,index) in list" :label="index" :key="index"
						:checked="true">{{item.name}}</el-checkbox>
				</el-checkbox-group>
			</el-form-item>


			<!--提交-->
			<div class="common-button-wrapper">
				<el-button type="primary" @click="onSubmit">提交</el-button>
			</div>



		</el-form>


	</div>

</template>

<script>
	import SettingApi from '@/api/setting.js';

	export default {
		data() {
			return {
				form: {
					keys: [],
				},
				list: [],
			};
		},
		created() {
			this.getData()
		},

		methods: {
			getData() {
				let self = this;
				SettingApi.clearDetail({}, true)
					.then(data => {
						self.list = data.data.cacheList;
					})
					.catch(error => {});
			},
			//提交表单
			onSubmit() {
				let self = this;
				let params = this.form;
				SettingApi.editCache(params, true)
					.then(data => {
						ElMessage({
							message: '恭喜你，清理成功',
							type: 'success'
						});
						self.$router.push('/setting/clear/index');

					})
					.catch(error => {

					});
			},
			//监听复选框选中
			handleCheckedCitiesChange(val) {},
		}
	};
</script>

<style scoped>
	.tips {
		color: #ccc;
	}
</style>