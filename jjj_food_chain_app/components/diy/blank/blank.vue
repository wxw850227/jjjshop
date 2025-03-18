<template>
	<!-- <view class="diy-blank" :style="{height: itemData.style.height +'px', background:itemData.style.background }">
	</view> -->
	<view :style="{
		background: itemData.style.bgcolor,
		paddingLeft: itemData.style.paddingLeft + 'px',
		paddingRight: itemData.style.paddingLeft + 'px',
		paddingTop: itemData.style.paddingTop + 'px',
		paddingBottom: itemData.style.paddingBottom + 'px'
	}">
		<view :style="{
				height: itemData.style.height + 'px',
				background: itemData.style.background,
				borderTopLeftRadius: itemData.style.topRadio + 'px',
				borderTopRightRadius: itemData.style.topRadio + 'px',
				borderBottomLeftRadius: itemData.style.bottomRadio + 'px',
				borderBottomRightRadius: itemData.style.bottomRadio + 'px'
			}">
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