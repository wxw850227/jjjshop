<template>
	<div class="pb50">
		<el-form size="small" ref="form" :model="form" label-width="150px">
			<el-form-item label="店内营业时间" prop="store_time"
				:rules="[{ required: true, message: '请选择店内营业时间', trigger: 'change' }]">
				<el-col :span="11">
					<el-time-picker is-range v-model="form.store_time" range-separator="至" start-placeholder="开始时间"
						end-placeholder="结束时间" placeholder="选择时间范围" format="HH:mm" value-format="HH:mm">
					</el-time-picker>
				</el-col>
			</el-form-item>
			<el-form-item label="自提营业时间" prop="pick_time"
				:rules="[{ required: true, message: '请选择自提营业时间', trigger: 'change' }]">
				<el-col :span="11">
					<el-time-picker is-range v-model="form.pick_time" range-separator="至" start-placeholder="开始时间"
						end-placeholder="结束时间" placeholder="选择时间范围" format="HH:mm" value-format="HH:mm">
					</el-time-picker>
				</el-col>
			</el-form-item>
			<el-form-item label="外卖营业时间" prop="delivery_time"
				:rules="[{ required: true, message: '请选择外卖营业时间', trigger: 'change' }]">
				<el-col :span="11">
					<el-time-picker is-range v-model="form.delivery_time" range-separator="至" start-placeholder="开始时间"
						end-placeholder="结束时间" placeholder="选择时间范围" format="HH:mm" value-format="HH:mm">
					</el-time-picker>
				</el-col>
			</el-form-item>
			<el-form-item label="外卖配送">
				<el-checkbox-group v-model="form.delivery_set">
					<el-checkbox v-for="(item,index) in deliver_type" :label="item.value"
						:key="index">{{item.name}}</el-checkbox>
				</el-checkbox-group>
				<div class="tips">注：外卖配送方式至少选择一个</div>
			</el-form-item>
			<el-form-item label="店内配置">
				<el-checkbox-group v-model="form.store_set">
					<el-checkbox v-for="(item,index) in store_type" :label="item.value"
						:key="index">{{item.name}}</el-checkbox>
				</el-checkbox-group>
				<div class="tips">注：店内用餐方式至少选择一个</div>
			</el-form-item>
			<el-form-item label="外卖包装费类型">
				<el-radio-group v-model="form.bag_type">
					<el-radio :label="0">按商品收费</el-radio>
					<el-radio :label="1">按单收费</el-radio>
				</el-radio-group>
			</el-form-item>
			<el-form-item v-if="form.bag_type==1" label="包装费" :rules="[{ required: true, message: '请输入包装费' }]"
				prop="bag_price">
				<div class=" d-s-c">
					<div class="max-w60">
						<el-input v-model="form.bag_price" type="number"></el-input>
					</div>
					<div class="ml10">元</div>
				</div>
			</el-form-item>
			<el-form-item label="店内包装费类型">
				<el-radio-group v-model="form.storebag_type">
					<el-radio :label="0">按商品收费</el-radio>
					<el-radio :label="1">按单收费</el-radio>
				</el-radio-group>
			</el-form-item>
			<el-form-item v-if="form.storebag_type==1" label="包装费" :rules="[{ required: true, message: '请输入包装费' }]"
				prop="bag_price">
				<div class=" d-s-c">
					<div class="max-w60">
						<el-input v-model="form.storebag_price" type="number"></el-input>
					</div>
					<div class="ml10">元</div>
				</div>
			</el-form-item>
			<el-form-item label="外卖配送范围" :rules="[{ required: true, message: '请输入外卖配送范围' }]" prop="delivery_distance">
				<div class=" d-s-c">
					<div class="max-w60">
						<el-input class="" v-model="form.delivery_distance" type="number"></el-input>
					</div>
					<div class="ml10">km</div>
				</div>
			</el-form-item>
			<el-form-item label="最低消费" :rules="[{ required: true, message: '请输入最低消费' }]" prop="min_money">
				<div class=" d-s-c">
					<div class="max-w60">
						<el-input v-model="form.min_money" type="number"></el-input>
					</div>
					<div class="ml10">元</div>
				</div>
			</el-form-item>
			<el-form-item label="外卖配送费" :rules="[{ required: true, message: '请输入外卖配送费' }]" prop="shipping_fee">
				<div class=" d-s-c">
					<div class="max-w60">
						<el-input v-model="form.shipping_fee" type="number"></el-input>
					</div>
					<div class="ml10">元</div>
				</div>
			</el-form-item>
			<!--提交-->
			<div class="common-button-wrapper">
				<el-button size="small" @click="cancelFunc">取消</el-button>
				<el-button size="small" type="primary" @click="onSubmit" :loading="loading">提交</el-button>
			</div>
		</el-form>
	</div>
</template>

<script>
	import SupplierApi from '@/api/supplier.js';
	import {
		formatModel
	} from '@/utils/base.js'
	export default {
		data() {
			return {
				/*表单对象*/
				form: {
					shop_supplier_id: 0,
					/*活动时间*/
					store_time: '',
					pick_time: '',
					delivery_time: '',
					bag_type: 0,
					bag_price: 0,
					storebag_type: 0,
					storebag_price: 0,
					delivery_distance: '',
					delivery_set: [],
					store_set: [],
					min_money: 0,
					shipping_fee: 0,
				},
				deliver_type: [{
					'name': '外卖配送',
					'value': 10
				}, {
					'name': '到店自提',
					'value': 20
				}],
				store_type: [{
					'name': '打包带走',
					'value': 30
				}, {
					'name': '店内就餐',
					'value': 40
				}],
				/*是否正在加载*/
				loading: false
			};
		},
		created() {
			this.form.shop_supplier_id = this.$route.query.shop_supplier_id;
			this.getData();
		},
		methods: {
			/*获取参数*/
			getData() {
				let self = this;
				SupplierApi.getsetSupplier({
						shop_supplier_id: self.form.shop_supplier_id
					}, true)
					.then(res => {
						self.form = formatModel(self.form, res.data.model);
						self.form.delivery_set = res.data.model.delivery_set;
						// 转成整数，兼容组件
						for (let i = 0; i < self.form.delivery_set.length; i++) {
							self.form.delivery_set[i] = parseInt(self.form.delivery_set[i]);
						}
						self.form.store_set = res.data.model.store_set;
						// 转成整数，兼容组件
						for (let i = 0; i < self.form.store_set.length; i++) {
							self.form.store_set[i] = parseInt(self.form.store_set[i]);
						}
						console.log(self.form)
					})
					.catch(error => {

					});
			},
			/*提交表单*/
			onSubmit() {
				let self = this;
				let params = self.form;
				if (params.delivery_set.length < 1) {
					ElMessage({
						message: '外卖配送至少选择一种！',
						type: 'warning'
					});
					return;
				}
				if (params.store_set.length < 1) {
					ElMessage({
						message: '店内配置至少选择一种！',
						type: 'warning'
					});
					return;
				}
				self.$refs.form.validate(valid => {
					if (valid) {

						console.log(params)
						self.loading = true;
						SupplierApi.setSupplier(params, true)
							.then(data => {
								self.loading = false;
								ElMessage({
									message: '恭喜你，设置成功',
									type: 'success'
								});
								self.cancelFunc();
							})
							.catch(error => {
								self.loading = false;
							});
					}
				});
			},
			/*取消*/
			cancelFunc() {
				this.$router.back(-1);
			}
		}
	};
</script>

<style scoped>
	.max-w60 {
		max-width: 60px;
	}
</style>