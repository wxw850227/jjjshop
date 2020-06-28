<template>
	<view class="diy-window" :style="{ background: itemData.style.background, padding: itemData.style.paddingTop + 'px ' + itemData.style.paddingLeft + 'px' }">
		<view class="data-list" v-if="itemData.style.layout > -1" :class="'column__' + itemData.style.layout">
			<view class="item" :key="index" v-for="(item, index) in itemData.data"  @click="gotoPage(item)">
				<view class="item-image" :style="{ padding: itemData.style.paddingTop + 'px ' + itemData.style.paddingLeft + 'px' }">
					<!-- <imageComponent :imgurl="item.imgUrl" mode="aspectFill"></imageComponent> -->
					<image :src="item.imgUrl" mode="aspectFill"></image>
				</view>
			</view>
		</view>
		<view class="display" v-else :style="{ padding: itemData.style.paddingTop + 'px ' + itemData.style.paddingLeft + 'px' }">
			<view class="img-box-wrap-1">
				<view class="img-box" @click="gotoPage(itemData.data[0])">
					<image :src="itemData.data[0].imgUrl" mode="aspectFill"></image>
				</view>
			</view>
			<view class="percent-w50 d-s-c d-c" >
				
					<view class="img-box-wrap-2" v-if="itemData.data.length >= 2">
						<view class="img-box" @click="gotoPage(itemData.data[1])">
							<image :src="itemData.data[1].imgUrl" mode="aspectFill"></image>
						</view>
					</view>
					<view class="d-s-c img-box-wrap-3">
						<view class="img-box-wrap-4" v-if="itemData.data.length >= 3">
							<view class="img-box" @click="gotoPage(itemData.data[2])">
								<image :src="itemData.data[2].imgUrl" mode="aspectFill"></image>
							</view>
						</view>
						<view class="img-box-wrap-4" v-if="itemData.data.length >= 4">
							<view class="img-box" @click="gotoPage(itemData.data[3])">
								<image :src="itemData.data[3].imgUrl" mode="aspectFill"></image>
							</view>
						</view>
					</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {gotopage} from '@/common/gotopage.js'
export default {
	data() {
		return {};
	},
	props: ['itemData'],
	methods: {
		/*跳转页面*/
		gotoPage(e){
			gotopage(e.linkUrl);
		}
	}
};
</script>

<style>
.diy-window .data-list {
	display: flex;
	flex-wrap: wrap;
}
.diy-window .data-list.column__2 .item {
	width: 50%;
}
.diy-window .data-list.column__3 .item {
	width: 33.333333333333%;
}
.diy-window .data-list.column__4 .item {
	width: 25%;
}
.diy-window .data-list.column__2 .item {
	position: relative;
	padding-top: 50%;
}
.diy-window .data-list.column__3 .item {
	position: relative;
	padding-top: 33.3333333%;
}
.diy-window .data-list.column__4 .item {
	position: relative;
	padding-top: 25%;
}
.diy-window .data-list .item .item-image,
.diy-window .display .img-box {
	position: absolute;
	box-sizing: border-box;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}

.diy-window .display {
	display: flex;
}
.diy-window .display .img-box-wrap-1 {
	position: relative;
	width: 50%;
	padding-top: 50%;
}
.diy-window .display .img-box-wrap-2 {
	position: relative;
	width: 100%;
	height: 50%;
}

.diy-window .display .percent-w50 {
	box-sizing: border-box;
	width: 50%;
}
.diy-window .display .img-box .item-image {
	height: 100%;
}
.diy-window .display .img-box-wrap-3 {
	width: 100%;
	height: 50%;
}
.diy-window .display .img-box-wrap-4 {
	position: relative;
	width: 50%;
	height: 100%;
}
</style>
