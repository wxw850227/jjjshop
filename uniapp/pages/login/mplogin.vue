<template>
	<view>

	</view>
</template>

<script>
	export default {
		data() {
			return {

			};
		},
		onLoad(options) {
			uni.setStorageSync('token', options.token);
			uni.setStorageSync('user_id', options.user_id);
			// 执行回调函数
			uni.reLaunch({
				url: '/' + uni.getStorageSync('currentPage')
			});
		},
	}
</script>

<style>

</style>
