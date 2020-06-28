<template>
	<view class="diy-banner-box">
		<swiper class="swiper" 
		:indicator-dots="indicatorDots" 
		:indicator-active-color="indicatorActiveColor"
		:autoplay="autoplay" 
		:interval="interval" 
		:duration="duration">
			<swiper-item v-for="(item,index) in itemData.data" :key="index" @click="gotoPage(item)">
				<image :src="item.imgUrl"></image>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
	import {gotopage} from '@/common/gotopage.js'
	export default{
		data(){
			return{
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				indicatorActiveColor:'#ffffff'
			}
		},
		props:['itemData'],
		created() {
			this.interval=this.itemData.params.interval;
			this.indicatorActiveColor=this.itemData.style.btnColor;
		},
		methods:{
			
			/*跳转页面*/
			gotoPage(e){
				gotopage(e.linkUrl);
			}
		}
	}
</script>

<style>
	.diy-banner-box,.diy-banner-box .swiper {
		width: 750rpx;
		height: 310rpx;
	}
	
	.diy-banner-box image {
		margin: 20rpx 20rpx 0;
		width: 710rpx;
		height: 290rpx;
		border-radius: 16rpx;
	}
</style>
