<template>
	<Popup :show="isPopup" :width="width" :padding="0" @hidePopup="hidePopupFunc">
		<view class="d-c-c ww100 kf-close">
			<view class="p20" @click="hidePopupFunc(true)"><text class="icon iconfont icon-guanbi"></text></view>
		</view>
		<view class="d-s-s d-c p20 mpservice-wrap" v-if="!isloding">
			<view class="noDatamodel" v-if="dataModel==null||(dataModel.qq==''&&dataModel.wechat==''&&dataModel.phone=='')">该商家尚未设置客服</view>
			<template v-if="dataModel!=null">
				<view v-if="dataModel.qq!=''" class="d-b-c p-30-0 f34 ww100 border-b" @click="copyQQ(dataModel.qq)">
					<text class="gray9" style="width: 140rpx;"><text class='icon iconfont icon-qq'></text></text>
					<text class="p-0-30 flex-1">{{dataModel.qq}}</text>
					<text class="blue">复制</text>
				</view>
				<view v-if="dataModel.wechat!=''" class="d-b-c p-30-0 f34 ww100 border-b" @click="copyQQ(dataModel.qq)">
					<text class="gray9" style="width: 140rpx;"><text class='icon iconfont icon-weixin'></text></text>
					<text class="p-0-30 flex-1">{{dataModel.wechat}}</text>
					<text class="blue">复制</text>
				</view>
				<view v-if="dataModel.phone!=''" class="d-b-c p-30-0 f34 ww100" @click="callPhone(dataModel.phone)">
					<text class="gray9" style="width: 140rpx;"><text class='icon iconfont icon-002dianhua'></text></text>
					<text class="p-0-30 flex-1">{{dataModel.phone}}</text>
					<text class="blue">拨打</text>
				</view>
			</template>
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
				isloding:true,
				/*宽度*/
				width: 600,
				/*数据对象*/
				dataModel:{
					qq: '',
					wechat: '',
					phone: ''
				}
			}
		},
		props: ['shopSupplierId'],
		created() {
			this.isPopup=true;
			this.getData();
		},
		methods:{
			/*获取数据*/
			getData() {
				let self = this;
				self.isloding=true;
				self._get(
					'index/mpService',
					{
						shop_supplier_id: self.shopSupplierId
					},
					function(res) {
						self.dataModel=res.data.mp_service;
						self.isloding=false;
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
				//#ifdef MP-WEIXIN
				uni.setClipboardData({
				 //准备复制的数据
				  data: message,
				  success: function (res) {
					uni.showToast({
					  title: '复制成功',
					  icon: 'success',
					  mask:true,
					  duration:2000
					});
				  }
				});
				//#endif
				//#ifdef H5
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
				//#endif
			},
			/*拨打电话*/
			callPhone(phone){
				uni.makePhoneCall({
				    phoneNumber: phone
				});
			},
		}
	}
</script>

<style>
.mpservice-wrap{
	width: 100%;
	box-sizing: border-box;	
}
.mpservice-wrap .mp-image{ width: 560rpx; margin-top: 40rpx;}
.mpservice-wrap .mp-image image{ width: 100%;}
.icon-qq{
	color: #1296db;
	font-size: 64rpx;
}
.icon-weixin{
	color: #1afa29;
	font-size: 64rpx;
}
.icon-guanbi{
	font-size: 26rpx;
}
.icon-002dianhua{
	color: #1296db;
	font-size: 52rpx;
}
.kf-close{
	justify-content: flex-end;
}
.noDatamodel{
	font-size: 30rpx;
	width: 100%;
	text-align: center;
	height: 200rpx;
	line-height: 128rpx;
	color: #929292;
}
</style>
