<template>
	<!--
      	描述：桌位管理
      -->
	<div class="common-seach-wrap">
		<!--商品管理-->
		<tables v-if="activeName=='tables'"></tables>
		<!--分类管理-->
		<tablearea v-if="activeName=='tablearea'"></tablearea>
		<!--分类管理-->
		<tabletype v-if="activeName=='tabletype'"></tabletype>
	</div>
</template>
<script>
	import {
		reactive,
		toRefs,
		defineComponent
	} from 'vue';
	import {
		useUserStore
	} from "@/store";
	import tables from './table/index.vue';
	import tablearea from './area/index.vue';
	import tabletype from './type/index.vue';
	export default defineComponent({
		components: {
			tables,
			tablearea,
			tabletype,
		},
		setup() {
			const {
				bus_emit,
				bus_off,
				bus_on
			} = useUserStore();
			const state = reactive({
				bus_emit,
				bus_off,
				bus_on,
				/*是否加载完成*/
				loading: true,
				form: {},
				/*参数*/
				param: {},
				/*当前选中*/
				activeName: 'tables',
				/*切换数组原始数据*/
				sourceList: [{
						key: 'tables',
						value: '桌位管理',
						path: '/store/table/table/index'
					},
					{
						key: 'tablearea',
						value: '区域管理',
						path: '/store/table/area/index'
					},
					{
						key: 'tabletype',
						value: '桌位类型',
						path: '/store/table/type/index'
					},

				],
				/*权限筛选后的数据*/
				tabList: [],
				paramsFlag: false
			})
			return {
				...toRefs(state),
			};


		},
		mounted() {
			this.tabList = this.authFilter();
			if (this.tabList.length > 0) {
				this.activeName = this.tabList[0].key;
			}

			if (this.$route.query.type != null) {
				this.activeName = this.$route.query.type;
			}

			/*监听传插件的值*/
			this.bus_on('activeValue', res => {
				this.activeName = res;
			});
			//发送类别切换
			let params = {
				active: this.activeName,
				list: this.tabList,
				tab_type: 'tablemanage',
			}
			this.bus_emit('tabData', params);
		},
		beforeUnmount() {
			//发送类别切换
			this.bus_emit('tabData', {
				active: null,
				tab_type: 'tablemanage',
				list: []
			});
			this.bus_off('activeValue');
		},
		methods: {
			/*权限过滤*/
			authFilter() {
				let list = [];
				for (let i = 0; i < this.sourceList.length; i++) {
					let item = this.sourceList[i];
					if (this.$filter.isAuth(item.path)) {
						list.push(item);
					}
				}
				return list;
			},

		}
	});
</script>

<style>

</style>