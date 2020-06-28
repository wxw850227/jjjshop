<template>
	<view>
		  <view class="diy-imageSingle" :style="{ paddingBottom: itemData.style.paddingTop + 'px', background: itemData.style.background}">
		    <view class="item-image" :key="index" v-for="(imageSingle, index) in itemData.data" :style="{padding: itemData.style.paddingTop + 'px ' + itemData.style.paddingLeft + 'px 0'}" @click="gotoPage(imageSingle)">
		      <imageComponent :imgurl="imageSingle.imgUrl" mode="widthFix"></imageComponent>
		    </view>
		  </view>
	</view>
</template>

<script>
	import {gotopage} from '@/common/gotopage.js'
	export default {
		data() {
			return {
				
			}
		},
		props:['itemData'],
		methods: {
			/*跳转页面*/
			gotoPage(e){
				gotopage(e.linkUrl);
			}
		}
	}
</script>

<style>
.diy-imageSingle .item-image{ min-height: 10rpx;}
</style>
