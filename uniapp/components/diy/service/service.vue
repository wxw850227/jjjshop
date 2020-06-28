<template>
	<view class="diy-service drag optional drag__nomove"
	  :style="{ right: itemData.style.right + 'px', bottom: itemData.style.bottom + 'px', opacity: itemData.style.opacity / 100 }">
	  <view class="service-icon" v-if="itemData.params.type=='phone'" @click="callPhone">
		  <image :src="itemData.params.image" mode="aspectFill"></image>
	  </view>
	  <button class="service-icon" open-type="contact" session-from="wxapp" v-if="itemData.params.type=='chat'">
		  <image :src="itemData.params.image" mode="aspectFill"></image>
	  </button>
	</view>
</template>

<script>
	export default {
		data() {
			return {};
		},
		props: ['itemData'],
		methods: {
			
			/*拨打电话*/
			callPhone(){
				let self=this;
				uni.makePhoneCall({
				    phoneNumber: self.itemData.params.phone_num //仅为示例
				});
			}
			
		}
	};
</script>

<style>
	.diy-service{ position: fixed; width: 120rpx; height: 120rpx; z-index: 90; right: 0; bottom: 0;}
	.diy-service .service-icon{ padding: 0; width: 120rpx; height: 120rpx; background: none; }
	
</style>
