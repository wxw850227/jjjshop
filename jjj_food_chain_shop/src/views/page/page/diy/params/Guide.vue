<template>

	<div>
		<div class="common-form">
			<span>{{ curItem.name }}</span>
		</div>
		<el-form size="small" :model="curItem" label-width="100px">
			<div class="f16 gray3 form-subtitle">样式设置</div>
			<!--背景颜色-->
			<div class="form-item mt10">
				<div class="form-label">背景颜色：</div>
				<div class="flex-1 d-s-c" style="height: 36px;">
					<el-color-picker size="default" v-model="curItem.style.background"></el-color-picker>
					<el-input class="ml10" v-model="curItem.style.background" />
					<el-button style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'background', '#f2f2f2')" type="primary" link>重置</el-button>
				</div>
			</div>
			<!-- 线条样式 -->
			<el-form-item label="线条样式：">
				<el-radio-group @change="changeType" v-model="curItem.style.lineStyle"  size="medium">
					<el-radio-button :label="'solid'">实线</el-radio-button>
					<el-radio-button :label="'dashed'">虚线</el-radio-button>
					<el-radio-button :label="'dotted'">点状</el-radio-button>
				</el-radio-group>
			</el-form-item>
			<!--线条颜色-->
			<div class="form-item">
				<div class="form-label">线条颜色：</div>
				<div class="flex-1 d-s-c" style="height: 36px;">
					<el-color-picker size="default" v-model="curItem.style.lineColor"></el-color-picker>
					<el-input class="ml10" v-model="curItem.style.lineColor" />
					<el-button style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'lineColor', '#eeeeee')" type="primary" link>重置</el-button>
				</div>
			</div>
			<!--线条高度-->
			<div class="form-item">
				<div class="form-label">线条高度：</div>
				<el-slider v-model="curItem.style.lineHeight" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
			</div>
			<!--上边距-->
			<div class="form-item">
				<div class="form-label">上边距：</div>
				<el-slider v-model="curItem.style.paddingTop" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
			</div>
			<!--下边距-->
			<div class="form-item">
				<div class="form-label">下边距：</div>
				<el-slider v-model="curItem.style.paddingBottom" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
			</div>
			<!--左右边距-->
			<div class="form-item">
				<div class="form-label">左右边距：</div>
				<el-slider v-model="curItem.style.paddingLeft" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
			</div>
		</el-form>
	</div>
</template>

<script>
export default {
	data() {
		return {};
	},
	props: ['curItem', 'selectedIndex', 'opts'],
	created() {
		this.curItem.style.lineHeight = parseInt(this.curItem.style.lineHeight);
		this.curItem.style.paddingTop = parseInt(this.curItem.style.paddingTop);
	},
	methods: {}
};
</script>

<style lang="scss" scoped></style>
