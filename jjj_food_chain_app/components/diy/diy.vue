<template>
	<view>
		<block v-for="(item,index) in diyItems" :key="index">
			<!--轮播图-->
			<template v-if="item.type==='banner'&&item.data!=null">
				<banner :itemData="item"></banner>
			</template>
			<!-- 橱窗-->
			<template v-if="item.type == 'window'&&item.data!=null">
				<windows :itemData="item"></windows>
			</template>

			<!--导航组-->
			<template v-if="item.type==='navBar'&&item.data!=null">
				<navBar :itemData="item" @scanQrcode="scanQrcode"></navBar>
			</template>
			<!--辅助空白-->
			<template v-if="item.type == 'blank'">
				<blank :itemData="item"></blank>
			</template>

			<!--辅助线-->
			<template v-if="item.type == 'guide'">
				<guide :itemData="item"></guide>
			</template>
			<!--广告导航-->
			<template v-if="item.type == 'adNav'&&item.data!=null">
				<adNav :itemData="item" @scanQrcode="scanQrcode"></adNav>
			</template>
		</block>
		
	</view>
</template>

<script>
	import banner from './banner/banner';
	import windows from './window/window';
	import navBar from './navBar/navBar';
	import blank from './blank/blank';
	import guide from './guide/guide';
	import adNav from './adNav/adNav';
	export default {
		components: {
			banner,
			windows,
			navBar,
			blank,
			guide,
			adNav
		},
		data() {
			return {

			}
		},
		props: ['diyItems'],
		created() {},
		methods: {
			scanQrcode() {
				this.$emit('scanQrcode')
			}
		}
	}
</script>

<style>
</style>
