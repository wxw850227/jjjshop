<template>
	<view>
		<view class="diy-richText" :style="{background: itemData.style.background, padding: itemData.style.paddingTop + 'px ' + itemData.style.paddingLeft + 'px'}"
		  v-html="itemData.params.content">
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		props:['itemData'],
		created(){
		},
		methods: {
			scroll(e){
			}
		}
	}
</script>

<style>

</style>
