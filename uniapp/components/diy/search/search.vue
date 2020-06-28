<template>
	<view class="index-search-box" id="searchBox">
		<view class="index-search diy-search t-c" :class="'diy-search-style-'+itemData.style.searchStyle+' diy-search-textAlign-'+itemData.style.textAlign" @click="gotoSearch">
			<span class="icon iconfont icon-sousuo"></span>
			<text class="ml10">{{itemData.params.placeholder}}</text>
		</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{}
		},
		props:['itemData'],
		methods:{
			/*跳转搜索页面*/
			gotoSearch() {
				uni.navigateTo({
					url: '/pages/product/search/search'
				});
			}
		}
	}
</script>

<style>
	
	/*search*/
	.diy-search-style-3{}
	.diy-search-style-6{border-radius: 16rpx;}
	.diy-search-style-9{border-radius: 50rpx;}
	.diy-search-textAlign-3{justify-content: flex-start;}
	.diy-search-textAlign-6{justify-content: center;}
	.diy-search-textAlign-9{justify-content: flex-end;}
	.diy-search .svg-icon {
		margin-right: 10rpx;
	}
</style>
