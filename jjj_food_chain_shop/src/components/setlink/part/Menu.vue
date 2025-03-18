<template>
	<!--
        作者：luoyiming
        时间：2020-06-08
        描述：超链接选择-页面
    -->
	<el-select v-model="activePage" placeholder="请选择" class="percent-w100" @change="changeFunc" value-key="url">
		<el-option v-for="item in pages" :key="item.url" :value-key="item.name" :label="item.name"
			:value="item"></el-option>
	</el-select>
</template>

<script>
	export default {
		data() {
			return {
				/*页面数据*/
				pages: [{
					url: '/pages/user/address/address',
					name: '收货地址',
					type: '菜单'
				}, {
					url: '/pages/user/set/set',
					name: '设置',
					type: '菜单',
				}, {
					url: 'scanQrcode',
					name: '扫一扫',
					type: '菜单',
				}],
				/*选中的值*/
				activePage: '收货地址'
			}
		},
		created() {
			/*初始化*/
			this.changeFunc(this.pages[0]);
		},
		methods: {
			/*选中的值*/
			changeFunc(e) {
				this.$emit('changeData', e);
			}
		}
	}
</script>

<style>
</style>