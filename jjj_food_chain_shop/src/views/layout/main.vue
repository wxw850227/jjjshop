<template>
	<div :class="hasChild != null ? 'main' : 'main right-big'">
		<!--left menu-->
		<LeftMenu @selectMenu="selectMenuFunc"></LeftMenu>

		<!--right content-->
		<RightContent></RightContent>
	</div>
</template>

<script>
import LeftMenu from '@/views/layout/LeftMenu.vue';
import RightContent from '@/views/layout/RightContent.vue';
export default {
	components: {
		/*左菜单组件*/
		LeftMenu,
		/*右边内容容器*/
		RightContent
	},
	data() {
		return {
			/*是否有子菜单*/
			hasChild: null,
			/*系统基本数据*/
			baseInfo: {
				shop_name: '',
				supplier_name:'',
				user: {
					 user_type: null
				},
				version: ''
			}
		};
	},
	methods: {
		/*左边子组件传来的参数*/
		selectMenuFunc(param) {
			this.hasChild = param;
		},
	}
};
</script>

<style></style>