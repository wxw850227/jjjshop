<template>
	<view class="diy-guide" :style="{padding: itemData.style.paddingTop +'px '+'0', background:itemData.style.background }">
	  <view class="line" :style="{borderTopWidth: itemData.style.lineHeight +'px',borderTopColor:itemData.style.lineColor,borderTopStyle: itemData.style.lineStyle }">
	  </view>
	</view>
</template>

<script>
	export default {
		data() {
			return {};
		},
		props: ['itemData'],
		methods: {}
	};
</script>

<style>
</style>
