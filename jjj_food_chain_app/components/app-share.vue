<template>
	<view class="bottom-panel" :class="Visible?'bottom-panel open':'bottom-panel close'" @click="closePopup">
		<view class="content" v-on:click.stop>
			<view class="module-box module-share">
				<view class="hd d-c-c">
					分享
				</view>
				<view class="p30 box-s-b">
					<view class="d-c-c">
						<view class="item flex-1 d-c-c d-c">
							<button open-type="share" @click="share(0, 'WXSceneSession')">
								<view class="icon-box d-c-c share-friend">
									<text class="iconfont icon-fenxiang"></text>
								</view>
								<text class="pt20">微信好友</text>
							</button>
						</view>
						<view class="item flex-1 d-c-c d-c">
							<button @click="share(0, 'WXSenceTimeline')">
								<view class="icon-box d-c-c">
									<text class="iconfont icon-edit"></text>
								</view>
								<text class="pt20">微信朋友圈</text>
							</button>
						</view>
					</view>
				</view>
				<view class="btns">
					<button type="default" @click="closePopup(1)">取消</button>
				</view>
			</view>

		</view>
	</view>

</template>

<script>
	import config from '@/env/config.js';
	export default {
		data() {
			return {
				/*是否可见*/
				Visible: false,
				/*分享配置*/
				shareConfig: {},
				// logo
				logo: ''
			}
		},
		created() {
			this.getData();
		},
		props: ['isAppShare', 'appParams'],
		watch: {
			'isAppShare': function(n, o) {
				if (n != o) {
					this.Visible = n;
				}
			}
		},
		methods: {
			getData(){
				let self = this;
				self._get(
					'settings/appShare',{},
					function(res) {
						self.shareConfig = res.data.appshare;
						self.logo = res.data.logo;
					}
				);
			},
			/*关闭弹窗*/
			closePopup(type) {
				this.$emit('close');
			},
			// 分享
			share: function(shareType, scene) {
				let shareOPtions = {
					provider: "weixin",
					scene: scene, //WXSceneSession”分享到聊天界面，“WXSenceTimeline”分享到朋友圈
					type: shareType,
					success: function (res) {
					    console.log("success:" + JSON.stringify(res));
					},
					fail: function (err) {
					    console.log("fail:" + JSON.stringify(err));
					}
				}
				if(this.shareConfig.type != 2){
					shareOPtions.summary = this.appParams.summary;
					shareOPtions.imageUrl = this.logo;
					shareOPtions.title = this.appParams.title;
					// 公众号/h5
					if(this.shareConfig.type == 1){
						shareOPtions.href = this.shareConfig.open_site + this.appParams.path;
					}else if(this.shareConfig.type == 3){
						//下载页
						if(this.shareConfig.bind_type == 1){
							shareOPtions.href = this.shareConfig.down_url;
						}else{
							shareOPtions.href = config.app_url + "/index.php/api/user.useropen/invite?app_id="+ config.app_id +"&referee_id=" + uni.getStorageSync('user_id');
						}
					}
				}else{
					// 分享到小程序
					shareOPtions.scene = 'WXSceneSession';
					shareOPtions.type = 5;
					shareOPtions.imageUrl = this.appParams.image ? this.appParams.image : this.logo;
					shareOPtions.title = this.appParams.title;
					shareOPtions.miniProgram = {
						id: this.shareConfig.gh_id,
						path: this.appParams.path,
						webUrl: this.shareConfig.web_url,
						type:0
					};
				}
				uni.share(shareOPtions);
			},
		}
	}
</script>

<style scoped>
	.bottom-panel .popup-bg {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0, 0, 0, .6);
		z-index: 98;
	}
	.bottom-panel .popup-bg .wechat-box{ padding-top: var(--window-top);}
	.bottom-panel .popup-bg .wechat-box image{ width: 100%;}

	.bottom-panel .content {
		position: fixed;
		width: 100%;
		bottom: 0;
		min-height: 200rpx;
		max-height: 900rpx;
		background-color: #fff;
		transform: translate3d(0, 980rpx, 0);
		transition: transform .2s cubic-bezier(0, 0, .25, 1);
		bottom: env(safe-area-inset-bottom);
		z-index: 99;
	}

	.bottom-panel.open .content {
		transform: translate3d(0, 0, 0);
	}

	.bottom-panel.close .popup-bg {
		display: none;
	}

	.module-share .hd {
		height: 90rpx;
		line-height: 90rpx;
		font-size: 36rpx;
	}
	
	.module-share .item button,.module-share .item button::after{ background: none; border: none;}
	

	.module-share .icon-box {
		width: 100rpx;
		height: 100rpx;
		border-radius: 50%;
		background: #f6bd1d;
	}

	.module-share .icon-box .iconfont {
		font-size: 60rpx;
		color: #FFFFFF;
	}

	.module-share .btns {
		margin-top: 30rpx;
	}

	.module-share .btns button {
		height: 90rpx;
		line-height: 90rpx;
		border-radius: 0;
		border-top: 1px solid #EEEEEE;
	}

	.module-share .btns button::after {
		border-radius: 0;
	}

	.module-share .share-friend {
		background: #04BE01;
	}
</style>
