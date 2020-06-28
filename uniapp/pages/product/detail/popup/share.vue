<template>

	<view class="bottom-panel" :class="Visible?'bottom-panel open':'bottom-panel close'" @click="closePopup">
		<view class="popup-bg"></view>
		<view class="content" v-on:click.stop>

			<view class="module-box module-share">
				<view class="hd d-c-c">
					分享
				</view>
				<view class="p30 box-s-b">
					<view class="d-c-c">
						<view class="item flex-1 d-c-c d-c">
							<button open-type="share" @click="share">
								<view class="icon-box d-c-c share-friend">
									<text class="iconfont icon-fenxiang"></text>
								</view>
								<text class="pt20">分享好友</text>
							</button>
						</view>
						<view class="item flex-1 d-c-c d-c">
							<button @click="genePoster">
								<view class="icon-box d-c-c">
									<text class="iconfont icon-edit"></text>
								</view>
								<text class="pt20">生成海报</text>
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
	export default {
		data() {
			return {
				/*是否可见*/
				Visible: false,
				poster_img: ''
			}
		},
		props: ['isbottmpanel', 'product_id'],
		watch: {
			'isbottmpanel': function(n, o) {
				if (n != o) {
					this.Visible = n;
				}
			}
		},
		methods: {
			/*关闭弹窗*/
			closePopup(type) {
				this.$emit('close', {
					type: type,
					poster_img:this.poster_img
				})
			},
			//发送给朋友
			share: function() {
				//#ifdef H5
				console.log('显示分享的指引');
				//#endif
			},
			//生成海报
			genePoster: function() {
				let self = this;
				uni.showLoading({
					title: '加载中',
					mask:true
				});
				let source = 'wx';
				//#ifdef H5
				source = 'mp';
				//#endif
				self._get('product.product/poster', {
					product_id: self.product_id,
					source: source
				}, (result) => {
					self.poster_img = result.data.qrcode;
					self.closePopup(2);
				}, null, () => {
					uni.hideLoading();
				});
			},
		}
	}
</script>

<style>
	.bottom-panel .popup-bg {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0, 0, 0, .6);
		z-index: 98;
	}

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
