<template>
	<view class="request-loading-view" v-if="loadding">
		<!-- <view class="loading-view"><view class="loading"></view></view> -->
		<view class="loading-view"><image class="loading-img" src="../static/loading.gif" mode="aspectFill"></image></view>
	</view>
</template>

<script>
export default {
	data() {
		return {};
	},
	props:['loadding'],
	computed: {
		//计算属性判断vuex中的显示状态

	}
};
</script>

<style scoped>
.request-loading-view {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 999;
	background-color: rgba(0, 0, 0, 0.001);
	display: flex;
	justify-content: center;
	align-items: center;
}
.loading-view {
	width: 160upx;
	height: 160upx;
	/* background-color: rgba(0, 0, 0, 0.6); */
	border-radius: 20upx;
	display: flex;
	justify-content: center;
	align-items: center;
}

/* 动画样式 */
.loading {
	border: 10upx solid rgba(0, 0, 0, 0.01);
	border-radius: 50%;
	border-top: 10upx solid #666666;
	border-right: 10upx solid #666666;
	border-bottom: 10upx solid #666666;
	width: 60upx;
	height: 60upx;
	-webkit-animation: spin 1.4s linear infinite;
	animation: spin 1.4s linear infinite;
}
.loading-img{
	width: 60upx;
	height: 60upx;
}
@-webkit-keyframes spin {
	0% {
		-webkit-transform: rotate(0deg);
	}

	100% {
		-webkit-transform: rotate(360deg);
	}
}

@keyframes spin {
	0% {
		transform: rotate(0deg);
	}

	100% {
		transform: rotate(360deg);
	}
}
</style>