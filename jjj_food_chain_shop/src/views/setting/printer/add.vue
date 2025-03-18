<template>

	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<!--添加门店-->
			<div class="common-form">新增小票打印机</div>
			<el-form-item label="打印机名称 " prop="printer_name" :rules="[{required: true,message: ' '}]">
				<el-input v-model="form.printer_name" class="max-w460"></el-input>
			</el-form-item>
			<el-form-item label="打印机类型" prop="printer_type" :rules="[{required: true,message: ' '}]">
				<el-select v-model="form.printer_type" placeholder="请选择">
					<el-option v-for="(item,index) in type" :key="index" :label="item" :value="index">
					</el-option>
				</el-select>
				<div class="tips">目前支持 飞鹅打印机、365云打印</div>
			</el-form-item>


			<!-- 飞鹅打印机 -->
			<div v-if="form.printer_type=='FEI_E_YUN'">
				<el-form-item label="USER" prop="FEI_E_YUN.USER" :rules="[{required: true,message: ' '}]">
					<el-input v-model="form.FEI_E_YUN.USER" class="max-w460"></el-input>
					<div class="tips">飞鹅云后台注册用户名</div>
				</el-form-item>

				<el-form-item label="UKEY" prop="FEI_E_YUN.UKEY" :rules="[{required: true,message: ' '}]">
					<el-input v-model="form.FEI_E_YUN.UKEY" class="max-w460"></el-input>
					<div class="tips">飞鹅云后台登录生成的UKEY</div>
				</el-form-item>

				<el-form-item label="打印机编号" prop="FEI_E_YUN.SN" :rules="[{required: true,message: ' '}]">
					<el-input v-model="form.FEI_E_YUN.SN" class="max-w460"></el-input>
					<div class="tips">打印机编号为9位数字，查看飞鹅打印机底部贴纸上面的编号</div>
				</el-form-item>
			</div>

			<!-- 365云打印 -->
			<div v-if="form.printer_type=='PRINT_CENTER'">
				<el-form-item label="打印机编号 " prop="PRINT_CENTER.deviceNo" :rules="[{required: true,message: ' '}]">
					<el-input v-model="form.PRINT_CENTER.deviceNo" class="max-w460"></el-input>
				</el-form-item>

				<el-form-item label="打印机秘钥" prop="PRINT_CENTER.key" :rules="[{required: true,message: ' '}]">
					<el-input v-model="form.PRINT_CENTER.key" class="max-w460"></el-input>
				</el-form-item>
			</div>
			<el-form-item label="打印联数" prop="print_times" :rules="[{required: true,message: ' '}]">
				<el-input v-model="form.print_times" type="number" class="max-w460"></el-input>
				<div class="tips">同一订单，打印的次数</div>
			</el-form-item>

			<el-form-item label="排序">
				<el-input v-model="form.sort" type="number" class="max-w460"></el-input>
				<div class="tips">数字越小越靠前</div>
			</el-form-item>

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
					printer_name: '',
					printer_type: '',
					sort: 1,
					print_times: '',
					FEI_E_YUN: {
						USER: '',
						UKEY: '',
						SN: '',
					},
					PRINT_CENTER: {
						deviceNo: '',
						key: ''
					},
				},
				loading: false,
				type: [],

			};
		},
		created() {
			this.getData();
		},

		methods: {
			getData() {
				SettingApi.printerType({}, true)
					.then(data => {
						this.type = data.data.printerType;

					})
					.catch(error => {

					});
			},
			//提交表单
			onSubmit() {
				let self = this;
				let form = self.form;
				self.$refs.form.validate((valid) => {
					if (valid) {
						self.loading = true;
						SettingApi.addPrinter(form, true)
							.then(data => {
								self.loading = false;
								ElMessage({
									message: '恭喜你，添加成功',
									type: 'success'
								});
								self.$router.push('/setting/printer/index');

							}).catch(error => {
								self.loading = false;
							});
					}
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