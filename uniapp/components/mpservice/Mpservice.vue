<template>
	<Popup :show="isPopup" :width="width" :padding="0" @hidePopup="hidePopupFunc">
		<view class="d-s-s d-c p20 mpservice-wrap" >
			<view class="d-b-c p-30-0 f34 ww100 border-b" @click="copyQQ(dataModel.qq)">
				<text class="gray9" style="width: 140rpx;">QQ：</text>
				<text class="p-0-30 flex-1">{{dataModel.qq}}</text>
				<text class="blue">复制</text>
			</view>
			<view class="d-b-c p-30-0 f34 ww100" @click="copyQQ(dataModel.qq)">
				<text class="gray9" style="width: 140rpx;">微信号：</text>
				<text class="p-0-30 flex-1">{{dataModel.wechat}}</text>
				<text class="blue">复制</text>
			</view>
			<view class="mp-image">
				<image :src="dataModel.mp_image" mode="widthFix"></image>
			</view>
			<view class="ww100 pt10 tc f30 gray9">
				公众号
			</view>
		</view>
		<view class="d-c-c ww100">
			<view class="p20" @click="hidePopupFunc(true)"><text class="icon iconfont icon-guanbi"></text></view>
		</view>
	</Popup>
</template>

<script>
	import Popup from '@/components/uni-popup.vue';
	export default {
		components: {
			Popup
		},
		data(){
			return {
				/*是否打开*/
				isPopup:false,
				/*宽度*/
				width: 600,
				/*数据对象*/
				dataModel:{}
			}
		},
		created() {
			this.isPopup=true;
			this.getData();
		},
		methods:{
			
			/*获取数据*/
			getData() {
				let self = this;
				self._get(
					'index/mpService',
					{},
					function(res) {
						self.dataModel=res.data.mp_service;
					}
				);
			},
			
			/*关闭弹窗*/
			hidePopupFunc(e) {
				this.isPopup = false;
				this.$emit('close');
			},
			
			/*复制*/
			copyQQ(message) {
				var input = document.createElement("input");
				input.value = message;
				document.body.appendChild(input);
				input.select();
				input.setSelectionRange(0, input.value.length), document.execCommand('Copy');
				document.body.removeChild(input);
				uni.showToast({
				    title: '复制成功',
					icon: 'success',
					mask:true,
					duration:2000
				});
			}
		
		}
	}
</script>

<style>
.mpservice-wrap .mp-image{ width: 560rpx; margin-top: 40rpx;}
.mpservice-wrap .mp-image image{ width: 100%;}
</style>
