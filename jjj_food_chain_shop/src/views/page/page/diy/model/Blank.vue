<template>

	<div
		class="drag optional"
		:class="{ selected: index === selectedIndex }"
		@click.stop="$parent.$parent.onEditer(index)"
		:style="{
			background: item.style.bgcolor,
			paddingLeft: item.style.paddingLeft + 'px',
			paddingRight: item.style.paddingLeft + 'px',
			paddingTop: item.style.paddingTop + 'px',
			paddingBottom: item.style.paddingBottom + 'px'
		}"
	>
		<div
			class="diy-blank"
			:style="{
				height: item.style.height + 'px',
				background: item.style.background,
				borderTopLeftRadius: item.style.topRadio + 'px',
				borderTopRightRadius: item.style.topRadio + 'px',
				borderBottomLeftRadius: item.style.bottomRadio + 'px',
				borderBottomRightRadius: item.style.bottomRadio + 'px'
			}"
		></div>
		<div class="btn-edit-del"><div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div></div>
	</div>
</template>

<script>
export default {
	data() {
		return {};
	},
	props: ['item', 'index', 'selectedIndex'],
	methods: {}
};
</script>

<style lang="scss" scoped></style>
