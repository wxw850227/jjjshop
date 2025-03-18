<template>
	<!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品编辑-高级设置
    -->
	<div class="buy-set-content pl16 pr16">
		<!--其他设置-->
		<div class="common-form mt50">其他设置</div>
		<el-form-item v-if="form.model.product_status!=40" label="商品状态："
			:rules="[{ required: true, message: '选择商品状态' }]" prop="model.product_status">
			<el-radio-group v-model="form.model.product_status">
				<el-radio :label="10">上架</el-radio>
				<el-radio :label="20">下架</el-radio>
			</el-radio-group>
		</el-form-item>
		<el-form-item label="初始销量：">
			<el-input type="number" min="0" v-model="form.model.sales_initial" class="max-w460"></el-input>
		</el-form-item>
		<el-form-item label="商品排序：" :rules="[{ required: true, message: ' ' }]" prop="model.product_sort">
			<el-input type="number" min="0" v-model="form.model.product_sort" class="max-w460"></el-input>
		</el-form-item>
		<el-form-item label="限购数量：" :rules="[{ required: true, message: ' ' }]" prop="model.limit_num">
			<el-input type="number" min="0" v-model="form.model.limit_num" class="max-w460"></el-input>
			<div class="gray9">每个会员购买的最大数量，0为不限购</div>
		</el-form-item>
	</div>
</template>

<script>
	export default {
		data() {
			return {};
		},
		created() {},
		inject: ['form'],
		methods: {}
	};
</script>

<style></style>