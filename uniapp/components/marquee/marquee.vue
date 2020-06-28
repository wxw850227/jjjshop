<template>
	<!--跑马灯-->
	<view class="marquee_box" @touchstart='touchstartFn' @touchend='touchendFn'>
		<view class="" v-if="direction=='right'||direction=='left'">
			<view v-if="broadcastType=='text'" class="marquee_container" :style="'background:'+broadcastStylees.back_color+';'">
				<!-- <view v-if="broadcastIconIsDisplay" class="broadIcon" :style="'background:'+broadcastStylees.back_color+';font-size:28rpx;'">
					<text class="iconfont icon-guangbozheng-"></text>
					<text style="margin-left:12rpx;">{{broadcast_tit}}:</text>
				</view> -->
				<view class="boradcast_text_left_rig" :class='[direction=="left"?"marquee_text_left":direction=="right"?"marquee_text_right":"",animation_paused?"animation_pausedcss":""]'
				 :style="'--marqueeWidth--:'+(-broadcastStylees.width_mal)+'px;--speed--:'+broadcastStylees.time+'s;width:'+broadcastStylees.width_mal+'px;'">
					<view v-for="(item,index) in broadcastDataes" :key="index" :style="'color:'+broadcastStylees.text_color+';margin-left:'+(index!=0?item.starspos:0)+'px;font-size:'+broadcastStylees.font_size+'rpx;'">
						{{item.text}}
					</view>
				</view>


			</view>
			<view v-if="broadcastType=='mould'" class="mould">
				<view class="" style="width: 100%;position: relative;overflow: hidden;" :style="'height:'+view_height+'rpx;'">
					<view class="broadcastTopBtm dsf" :class="[direction=='left'?'broadcastDataTopBtmDatacss_let':direction=='right'?'broadcastDataTopBtmDatacss_rig':'',animation_paused?'animation_pausedcss':'']"
					 :style="'--scrollWidth--:'+(-scrlloWidth)+'px;--scrollSpeed2--:'+(scrlloWidth)/broadcastStyle.speed+'s;width:'+scrlloWidth*2+'px;'">
						<view class="bml01" style="display: flex;justify-content: space-between;">
							<slot />
						</view>
						<view class="" style="display: flex;justify-content: space-between;z-index: 9999;">
							<slot />
						</view>
					</view>
				</view>
			</view>

		</view>


		<view v-if="direction=='top'||direction=='bottom'" class="broadcastTopBtm" :style="'font-size:'+broadcastStyle.font_size+'rpx;color:'+broadcastStyle.text_color+';background:'+broadcastStyle.back_color+';height:'+view_height+'rpx;--scrollHeight--:'+(-broadcastTopBtmHeight/2)+'px;--scrollSpeed--:'+(broadcastTopBtmHeight/2)/broadcastStyle.speed+'s;'">
			<view v-if="broadcastType=='text'" :class="[direction=='top'?'broadcastDataTopBtmDatacss_top':direction=='bottom'?'broadcastDataTopBtmDatacss_bottom':'',animation_paused?'animation_pausedcss':'']">
				<view v-for="(item,index) in broadcastDataTopBtmDataes" :key="index" class="bdbd_item">
					{{item}}
				</view>
			</view>
			<view v-if="broadcastType=='mould'" :class="[direction=='top'?'broadcastDataTopBtmDatacss_top':direction=='bottom'?'broadcastDataTopBtmDatacss_bottom':'',animation_paused?'animation_pausedcss':'']">
				<view class="">
					<slot />
				</view>
				<view class="">
					<slot />
				</view>
			</view>
		</view>

	</view>
</template>

<script>
	/**
	 * author:G.bro
	 * date:20200513
	 * * **/
	export default {
		data() {
			return {
				broadcastDataes: [],
				broadcastDataTopBtmDataes: [],
				broadcastStylees: {},
				broadcastTopBtmHeight: 0,
				scrlloWidth: 0,
				animation_paused: false,
				qualifications:true
			}
		},
		props: {
			broadcastType: {
				type: String,
				default: 'text' //mould
			},
			// broadcastDataTopBtmData: {
			// 	type: Array
			// },
			broadcastData: {
				type: Array
			},
			broadcastStyle: {
				type: Object,
				default () {
					return {
						speed: 90, //滚动速度，每秒3个字
						font_size: "28", //字体大小(rpx)
						text_color: "#fff", //字体颜色
						back_color: "#815CD4", //背景色
					}
				}
			},
			broadcastIconIsDisplay: { //图标是否显示
				type: Boolean,
				default: false
			},
			// broadcast_icon: {
			// 	type: String,
			// 	default: ''
			// },
			broadcast_tit: {
				type: String,
				default: '今日热点'
			},
			direction: {
				type: String,
				default: 'left'
			},
			view_height: {
				type: Number,
				default: 200
			},
			touchEvent: {
				type: true,
				default: false
			}
		},
		comments: {
		},
		created() {
			if (this.broadcastType == "text") {
				if (this.direction == "right" || this.direction == "left") {
					this.initial_let_rig();
				} else {
					let arr = [...this.broadcastData];
					arr = arr.concat(arr);
					this.broadcastDataTopBtmDataes = arr;
				}
			}
		},
		mounted() {
			let that = this;
			if (this.direction == "top" || this.direction == "bottom") {
				const query2 = uni.createSelectorQuery().in(this)
				query2.select('.broadcastDataTopBtmDatacss_' + this.direction).boundingClientRect(rect => {
					that.broadcastTopBtmHeight = rect.height;
					
					if(that.view_height-rect.height>10){
						that.qualifications=false;
						that.animation_paused = true;
					}
					// console.log(rect.height, 'that.surplusHeight')
				}).exec()
			}
			if (this.direction == "left" || this.direction == "right") {
				
				if (this.broadcastType == 'mould') {
					const query = uni.createSelectorQuery().in(this)
					query.select('.bml01').boundingClientRect(rect => {
						that.scrlloWidth = rect.width;
						// console.log(rect.width, 'that.surplusHeight')
					}).exec()
				}
				// if(this.broadcastType=='text'){有偏差
				// 	let assist = { ...this.broadcastStyle};
				// 	const querys = uni.createSelectorQuery().in(this)
				// 	querys.select('.boradcast_text_left_rig').boundingClientRect(rect => {
				// 		assist.width_mal = rect.width;
				// 			assist.time = rect.width/assist.speed;
				// 			that.broadcastStylees=assist;
				// 	}).exec()
				// }
			}
		},
		methods: {
			touchendFn(e) {
				if (this.touchEvent&&this.qualifications) {
					this.animation_paused = false;
				}
			},
			touchstartFn(e) {
				if (this.touchEvent&&this.qualifications) {
					this.animation_paused = true;
				}
			},
			initial_let_rig() {
				let broadcastData = [...this.broadcastData];
				console.log(this.direction, 'this.direction')
				if (this.direction == "right") {
					broadcastData.reverse();
				}
				let ititdata = broadcastData.map((item) => {
						return {
							"text": item,
							"starspos": uni.getSystemInfoSync().windowWidth - 103
						}
					}),
					assist = { ...this.broadcastStyle
					},
					this_width = 0,
					spacing = 0,
					speed = (assist.speed * assist.font_size); //m每秒行走的距离
				for (let i in ititdata) {
					this_width += ititdata[i].text.length * assist.font_size;
					if (i != ititdata.length - 1) {
						spacing += ititdata[i].starspos
					}
				}
				let total_length = (this_width / 2) + spacing; //总长
				assist.time = total_length / speed; /**滚动时间*/
				assist.width_mal = total_length;
				this.broadcastDataes = ititdata;
				this.broadcastStylees = assist;
			},
		}
	}
</script>

<style>
	
	.marquee_box {
		width: 100%;
		overflow: hidden;
		position: inherit;
		height: auto;
	}
	.mould {
		width: 750rpx;
		overflow: hidden;
		position: inherit;
	}
	.dsf {
		display: flex;
		justify-content: space-between;
	}
	.broadIcon {
		/* width: 60rpx; */
		padding: 0 22rpx;
		height: 60rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		left: 0;
		top: 0;
		color: #fff;
		z-index: 999;
		line-height: 60rpx;
	}
	.marquee_container {
		position: relative;
		width: 100%;
		height: 60rpx;
	}
	.marquee_text_left {
		display: flex;
		white-space: nowrap;
		animation-name: around_left;
		animation-duration: var(--speed--);
		animation-iteration-count: infinite;
		animation-timing-function: linear;
		line-height: 60rpx;
		position: absolute;
	}
	@keyframes around_left {
		from {
			left: 100%;
		}
		to {
			left: var(--marqueeWidth--);
		}
	}
	.marquee_text_right {
		display: flex;
		white-space: nowrap;
		animation-name: around_right;
		animation-duration: var(--speed--);
		animation-iteration-count: infinite;
		animation-timing-function: linear;
		line-height: 60rpx;
		position: absolute;
	}
	@keyframes around_right {
		from {
			right: 100%;
		}
		to {
			right: var(--marqueeWidth--);
		}
	}
	.marquee_tit {
		height: 60rpx;
		line-height: 60rpx;
	}
	.broadcastTopBtm {
		padding: 12rx;
		overflow: hidden;
		position: relative;
	}
	.broadcastDataTopBtmDatacss_top {
		width: 100%;
		animation-name: around_top;
		animation-duration: var(--scrollSpeed--);
		animation-iteration-count: infinite;
		animation-timing-function: linear;
		position: absolute;
		left: 0;
	}
	@keyframes around_top {
		from {
			top: 0%;
		}
		to {
			top: var(--scrollHeight--);
		}
	}
	.broadcastDataTopBtmDatacss_bottom {
		width: 100%;
		animation-name: around_Bottom;
		animation-duration: var(--scrollSpeed--);
		animation-iteration-count: infinite;
		animation-timing-function: linear;
		position: absolute;
		left: 0;
	}
	@keyframes around_Bottom {
		from {
			bottom: 0%;
		}
		to {
			bottom: var(--scrollHeight--);
		}
	}
	.broadcastDataTopBtmDatacss_let {
		width: 100%;
		animation-name: around_let;
		animation-duration: var(--scrollSpeed2--);
		animation-iteration-count: infinite;
		animation-timing-function: linear;
		position: absolute;
		left: 0;
		top: 0;
	}
	.animation_pausedcss {
		animation-play-state: paused;
	}
	@keyframes around_let {
		from {
			left: 0%;
		}
		to {
			left: var(--scrollWidth--);
		}
	}
	.bdbd_item {
		padding: 0 12rpx;
		line-height: 60rpx;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	@font-face {
		font-family: "iconfont";
		src: url('//at.alicdn.com/t/font_1686137_9el2ov99ag.eot?t=1589197045289');
		/* IE9 */
		src: url('//at.alicdn.com/t/font_1686137_9el2ov99ag.eot?t=1589197045289#iefix') format('embedded-opentype'),
			/* IE6-IE8 */
			url('data:application/x-font-woff2;charset=utf-8;base64,d09GMgABAAAAAANIAAsAAAAAByAAAAL7AAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHEIGVgCCcAqCJIIbATYCJAMICwYABCAFhG0HNxtIBhHVmxnIfiTYMS7bb8koPCedCeJJj2/e7iZfYqkjkZzk9lw0oCspm5uha1SEYUrOg//lO9IN4UL4FQBGHE6b4vPv8pzmojHnedQFGG8NaG9cCySQAgnMG8ZuIniA2yGAP8kUIg0ateiALwbQTwAZMXRwX3wLLgzGifoi2A1TDbIKC1+1WF0CVvLvy0fKxRcUlgYkthnUcAB13sA47XgOPSgBwXIGwJkFGigEDEiHRlMrtAwVovGPQoZ0OFa2wJs6nkf5h/3jgYDYgMxAABJ1/jMJsPBRMxUARq/vB7iTzgYmPjQ5vv+S6E4b/P2XXBBxLwYELI3pvDFm/XqMs27QknXgrK82yepoddiXOXTJhi3NTe/e7XznTqcNG7ZujLoN2Drexulwy99hXacl0UZCzNW77p1WfV52MOpyx+O3nVuFt1qs+xziN+DL8Q0BU2N17J84FTs1YMOXE/2dXf8DTwV62/5bne0/dmdranzv25uGonfvtepore/p0pXugqw9N2SpuKsuTSuYO7vtjzV1oka1azMqsm50/SYtYxvHNug6blqbRuGpQccDpjYpGzCwauyCmnETJpadjJjwO6teaETz1uHNQjPqfmhPHQC8q/q2Lvj3vyF17cvZvUOqvvkGGAAePO36jTu8q4ZhFvC9Q7PAnxYMzDOxCKjUI/MNs6JfzoECneta8QC2cJ5MVUe9VkzwJerlLD4kZdD4kkk01EJYBFAJG19q4U8BjWcHEME4NGL8gHzmaxBCOQlFMBehCeUy0VAfwyKaz7AJFQ1/OknEkgFki5dUfUYt6MD8IFnaPdKKEtk3DFfVnBdx2gv5TF4Yu6EcXXFHXmLE+Q6TiAfPtMGi7Ya1EhxMGa10UeSY+943XamztDWK+oxa0IH5QbK0e1pcVOXzNwxX1dyRM+J8IZ+pcRi7YQBxle+Dch5ln/MdJhEPnmmDRTNghSGCo7lTRitdnBA45t4o5IfKu+2V298tAfwBGiWwuSZo8KX3YOgfkRnbpgEA') format('woff2'),
			url('//at.alicdn.com/t/font_1686137_9el2ov99ag.woff?t=1589197045289') format('woff'),
			url('//at.alicdn.com/t/font_1686137_9el2ov99ag.ttf?t=1589197045289') format('truetype'),
			/* chrome, firefox, opera, Safari, Android, iOS 4.2+ */
			url('//at.alicdn.com/t/font_1686137_9el2ov99ag.svg?t=1589197045289#iconfont') format('svg');
		/* iOS 4.1- */
	}
	.iconfont {
		font-family: "iconfont" !important;
		font-size: 16px;
		font-style: normal;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}
	.icon-guangbozheng-:before {
		content: "\e640";
	}
</style>

