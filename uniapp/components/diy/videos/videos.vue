<template>
	<view class="diy-video" :style="{padding:itemData.style.paddingTop + 'px 0'}">
	  <video :style="{height:itemData.style.height + 'px'}" :src="itemData.params.videoUrl" :poster="itemData.params.poster"
	    :autoplay="itemData.params.autoplay == '1'" controls objectFit="contain">
	  </video>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		props:['itemData'],
		methods: {
			
		}
	}
</script>

<style>
.diy-video uni-video, .diy-video video{ width: 100%;}
</style>
