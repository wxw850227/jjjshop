<template>
	<div :class="hasChild != null ? 'main right-big' : 'main right-big'">
		<!--left menu-->
		<LeftMenu @selectMenu="selectMenuFunc"></LeftMenu>

		<!--right content-->
		<RightContent></RightContent>
	</div>
</template>

<script>
import LeftMenu from '@/views/layout/LeftMenu.vue';
import RightContent from '@/views/layout/RightContent.vue';
import {
	reactive,
	toRefs,
	defineComponent,
} from 'vue';
export default defineComponent({
	setup() {
		const state = reactive({
			hasChild: null
		});
		return {
			...toRefs(state),
		};
	},
	components: {
		/*左菜单组件*/
		LeftMenu,
		/*右边内容容器*/
		RightContent
	},
	watch: {
		// 路由发生变化页面刷新
		$route(to, from) {
			if (from.path != to.path) {
				this.$router.go(0);
			}
		}
	},
	methods: {
		/*左边子组件传来的参数*/
		selectMenuFunc(param) {
			this.hasChild = param;
		}
	}
});
</script>

<style></style>