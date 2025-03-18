<template>
	<!-- <view class="diy-guide" :style="{padding: itemData.style.paddingTop +'px '+'0', background:itemData.style.background }"> -->
	<view class="diy-guide" 
		:style="{
          background: itemData.style.background,
          paddingLeft: itemData.style.paddingLeft + 'px',
          paddingRight: itemData.style.paddingLeft + 'px',
          paddingTop: itemData.style.paddingTop + 'px',
          paddingBottom: itemData.style.paddingBottom + 'px'
        }"
	>
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
