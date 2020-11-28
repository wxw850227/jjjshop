<template>
	<view class="diy-notice" :style="{padding: itemData.style.paddingTop + 'px' + ' 10px', background: itemData.style.background}"
	 @click="gotoPage(item)">
		<view class="notice-icon">
			<image :src="itemData.params.icon" mode="aspectFill"></image>
		</view>
		<view class="notice-text text-ellipsis flex-1">
			<!-- <text :style="{color: itemData.style.textColor}">{{itemData.params.text}}</text> -->
			<marquee broadcastType='text' direction="left" :broadcastIconIsDisplay="true" :touchEvent="true" :broadcastData='broadcastData'
			 :broadcastStyle='broadcastStyle'>
			</marquee>
		</view>
	</view>
</template>

<script>
	import marquee from '@/components/marquee/marquee.vue'
	import {
		gotopage
	} from '@/common/gotopage.js'
	export default {
		components: {
			marquee
		},
		data() {
			return {
				broadcastData: [],
				broadcastStyle: {
					speed: 1, //
					font_size: "32", //字体大小(rpx)
					text_color: "#333", //字体颜色
					back_color: "#ffffff", //背景色
				},
			};
		},
		props: ['itemData'],
		created() {
			this.broadcastStyle.back_color = this.itemData.style.background;
			this.broadcastStyle.text_color = this.itemData.style.textColor;
			this.broadcastData.push(this.itemData.params.text);
		},
		methods: {
			/*跳转页面*/
			gotoPage(e) {
				gotopage(e.linkUrl);
			}
		}
	};
</script>

<style>
	.diy-notice {
		display: flex;
		justify-content: flex-start;
		align-items: center;
	}

	.diy-notice .notice-icon {
		width: 32px;
		height: 32px;
	}

	.diy-notice .notice-text {
		margin-left: 10px;
		font-size: 28rpx;
		overflow: hidden;
		white-space: nowrap;
	}
</style>
