<template>
	<view>
		<view class="uni-mask" v-show="show" :style="{top:offsetTop + 'px'}" @click="hide"></view>
		<view :class="['uni-popup','uni-popup-'+type]" v-show="show" :style="'width:'+width+'rpx; heigth:'+heigth+'rpx;padding:'+padding+'rpx;background-color:'+backgroundColor+';box-shadow:'+boxShadow+';'">
			<view class="popup-head" v-if="msg!=''">
		 	{{msg}}
			</view>	
			<slot></slot>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			type: {
				type: String,
				//top - 顶部， middle - 居中, bottom - 底部
				default: 'middle'
			},
			width: {
				type: Number,
				default: 600
			},
			heigth: {
				type: Number,
				default: 800
			},
			padding:{
				type: Number,
				default: 30
			},
			backgroundColor:{
				type: String,
				default: '#ffffff'
			},
			boxShadow:{
				type: String,
				default: '0 0 30upx rgba(0, 0, 0, .1)'
			},
			msg: {
				type: String,
				default: ""
			}
		},
		data() {
			let offsetTop = 0;
			//#ifdef H5
			offsetTop = 0;
			//#endif
			return {
				offsetTop: offsetTop
			}
		},
		methods: {
			hide: function() {
				this.$emit('hidePopup');
			}
		}
	}
</script>
<style>
	.uni-mask {
		position: fixed;
		z-index: 998;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background-color: rgba(0, 0, 0, .3);
	}

	.uni-popup {
		position: absolute;
		z-index: 999;
	}

	.uni-popup-middle {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		width: 600upx;
		/* height:800upx; */
		border-radius: 10upx;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		justify-content: flex-start;
		padding: 30upx;
		overflow: auto;
	}
	.popup-head{ width: 100%; padding-bottom: 40rpx; box-sizing: border-box; font-size: 30rpx; font-weight: bold;}
	.uni-popup-top {
		top: 0;
		left: 0;
		width: 100%;
		height: 100upx;
		line-height: 100upx;
		text-align: center;
	}

	.uni-popup-bottom {
		left: 0;
		bottom: 0;
		width: 100%;
		line-height: 100upx;
		text-align: center;
	}
</style>
