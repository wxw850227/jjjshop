<template>
	<view class="diy-page">
		<!-- #ifdef APP-PLUS -->
		<view class="tc d-b-c  header"
			:style="topBarHeight() == 0 ? '': 'height:'+topBarHeight()+'px;padding-top:'+(topBarTop() + 5)+'px'">
			<view class="reg180" :style="topBarHeight() == 0 ? '': 'height:'+topBarHeight()+'px;'">
				<text   v-if="hasPage()" @click="goback"  class="icon iconfont icon-jiantou"></text>
			</view>
			<view class="gray3 f28">{{page_info.params.name}}</view>
			<view class="gray3 m20" @click="showShare()"><text class="icon iconfont icon-share1"></text></view>
		</view>
		<view :style="topBarHeight() == 0 ? '': 'height:'+topBarHeight()+'px;padding-top:'+(topBarTop() + 5)+'px'"
			class="ww100"></view>
		<!-- #endif -->
		<diy :diyItems="items"></diy>
		<!--底部弹窗-->
		<share :isMpShare="isMpShare" @close="closeBottmpanel"></share>
	</view>
</template>

<script>
	import diy from '@/components/diy/diy.vue';
	import share from '@/components/mp-share.vue';
	export default {
		components: {
			diy,
			share
		},
		data() {
			return {
				/*页面ID*/
				page_id:null,
				/*diy类别*/
				items:{},
				/*页面信息*/
				page_info:{
					params: {}
				},
				/*分享*/
				isMpShare: false,
				appParams: {
					title: '',
					summary: '',
					path: ''
				},
				url: '',
			}
		},
		onLoad(e) {
			this.page_id=e.page_id;
			this.getData();
			//#ifdef H5
			this.url = window.location.href;
			//#endif
		},
		/*分享当前页面*/
		onShareAppMessage() {
			let self = this;
			let params = self.getShareUrlParams({
				page_id: self.page_id
			});
			return {
				title: self.page_info.params.name,
				path: '/pages/diy-page/diy-page?' + params
			};
		},
		methods: {
			hasPage(){
				var pages = getCurrentPages();
				return pages.length > 1;
			},
			goback() {
				uni.navigateBack();
			},
			/*获取数据*/
			getData(page_id) {
				let self = this;
				self._get('index/diy', {
					page_id: self.page_id,
					url: self.url
				}, function(res) {
					self.page_info = res.data.page;
					self.items = res.data.items;
					self.setPage(self.page_info);
					// 配置微信分享参数
					//#ifdef H5
					if (self.url != '') {
						let params = {
							page_id: self.page_id
						};
						self.configWx(res.data.share.signPackage, res.data.share.shareParams, params);
					}
					//#endif
				});
			},
			
			/*设置页面*/
			setPage(page){
				
				uni.setNavigationBarTitle({
				    title: page.params.name
				});
				
				let colors='#000000';
				if(page.style.titleTextColor=='white'){
					//字母要小写
					colors='#ffffff'
				}
				uni.setNavigationBarColor({
				    frontColor: colors,
				    backgroundColor: page.style.titleBackgroundColor
				})
				
			},
			
			//分享按钮
			showShare() {
				let self = this;
				//#ifdef MP-WEIXIN
					return;
				//#endif
				//#ifndef APP-PLUS
				self.isMpShare = true;
				//#endif
			},
			//关闭分享
			closeBottmpanel(data) {
				this.isMpShare = false;
			}
		},
	}
</script>

<style>

</style>
