<template>
	<!--
      作者 luoyiming
      时间：2019-10-26
      描述：设置-小票打印设置
  -->
	<div class="product-add">
		<!--form表单-->
		<el-form size="small" ref="form" :model="form" label-width="200px">
			<!--小票打印设置-->
			<div class="common-form">小票打印设置</div>

			<el-form-item label="是否开启商户小票打印">
				<div>
					<el-radio v-model="form.seller_open" :label="'1'">开启</el-radio>
					<el-radio v-model="form.seller_open" :label="'0'">关闭</el-radio>
				</div>
			</el-form-item>
			<el-form-item label="选择打印机" v-if="form.seller_open==1">
				<el-select v-model="form.seller_printer_id" placeholder="请选择">
					<el-option v-for="(item,index) in printerList" :key="index" :label="item.printer_name"
						:value="item.printer_id+''"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="是否开启顾客小票打印">
				<div>
					<el-radio v-model="form.buyer_open" :label="'1'">开启</el-radio>
					<el-radio v-model="form.buyer_open" :label="'0'">关闭</el-radio>
				</div>
			</el-form-item>
			<el-form-item label="选择打印机二" v-if="form.buyer_open==1">
				<el-select v-model="form.buyer_printer_id" placeholder="请选择">
					<el-option v-for="(item,index) in printerList" :key="index" :label="item.printer_name"
						:value="item.printer_id+''"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="是否开启厨房小票打印">
				<div>
					<el-radio v-model="form.room_open" :label="'1'">开启</el-radio>
					<el-radio v-model="form.room_open" :label="'0'">关闭</el-radio>
				</div>
			</el-form-item>
			<el-form-item label="选择打印机三" v-if="form.room_open==1">
				<el-select v-model="form.room_printer_id" placeholder="请选择">
					<el-option v-for="(item,index) in printerList" :key="index" :label="item.printer_name"
						:value="item.printer_id+''"></el-option>
				</el-select>
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
					buyer_open: '0',
					room_open: '0',
					seller_open: '0',
					buyer_printer_id: '',
					room_printer_id: '',
					seller_printer_id: ''
				},
				checked: false,
				printerList: [],
				loading: false,


			};
		},
		created() {
			this.getData()
		},

		methods: {
			getData() {
				let self = this;
				SettingApi.printingDetail({}, true)
					.then(data => {
						let vars = data.data.vars.values;
						self.form.buyer_open = vars.buyer_open;
						self.form.seller_open = vars.seller_open;
						self.form.room_open = vars.room_open;
						self.form.buyer_printer_id = '' + vars.buyer_printer_id;
						self.form.room_printer_id = '' + vars.room_printer_id;
						self.form.seller_printer_id = '' + vars.seller_printer_id;
						self.printerList = data.data.vars.printerList;
						if (vars.order_status != null && vars.order_status[0] == 20) {
							self.checked = true;
						}

					})
					.catch(error => {});
			},
			//提交表单
			onSubmit() {
				let self = this;
				let params = this.form;
				self.loading = true;
				SettingApi.editPrinting(params, true)
					.then(data => {
						self.loading = false;
						ElMessage({
							message: '恭喜你，打印设置成功',
							type: 'success'
						});
						// self.$router.push('/setting/Printing');

					})
					.catch(error => {
						self.loading = false;
					});
			},
			//监听复选框选中
			handleCheckedCitiesChange(e) {
				let self = this;
				if (e) {
					self.form.order_status[0] = 20;
				} else {
					self.form.order_status = [];
				}
			},

		}

	};
</script>

<style scoped>
	.tips {
		color: #ccc;
	}
</style>