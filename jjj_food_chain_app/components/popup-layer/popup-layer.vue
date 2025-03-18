<template>
	<view>
		<view v-if="ifshow" @tap="ableClose" @touchmove.stop.prevent class="popup-layer">
		</view>
		<view class="popup-content" @tap.stop="stopEvent" :style="_location">
			<slot name="content"></slot>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'popup-layer',
		model: {
			prop: "showPop",
			event: "change"
		},
		props: {
			showPop: {
				type: Boolean,
				default: false,
			},
			direction: {
				type: String,
				default: 'top', // 方向  top，bottom，left，right 
			},
			autoClose: {
				type: Boolean,
				default: true,
			}
		},
		data() {
			return {
				ifshow: false, // 是否展示,
				//#ifdef H5
				translateValue: -150, // 位移距离
				//#endif
				//#ifndef H5
				translateValue: -150, // 位移距离
				//#endif
				timer: null,
				iftoggle: false,

			};
		},
		computed: {
			_translate() {
				const transformObj = {
					'top': `transform:translateY(${-this.translateValue}%)`,
					'bottom': `transform:translateY(${this.translateValue}%)`,
					'left': `transform:translateX(${-this.translateValue}%)`,
					'right': `transform:translateX(${this.translateValue}%)`
				};
				return transformObj[this.direction]
			},
			_location() {
				const positionValue = {
					//#ifndef H5
					'top': 'bottom:0;width:100%;',
					//#endif
					//#ifdef H5
					'top': 'bottom:0;width:100%;',
					//#endif
					'bottom': 'top:0px;width:100%;',
					'left': 'right:0px;height:100%;',
					'right': 'left:0px;height:100%;',
				};
				return positionValue[this.direction] + this._translate;
			}
		},
		mounted() {
			if (this.showPop) {
				this.show();
			}
		},
		watch: {
			showPop(value) {
				if (value) {
					this.show();
				} else {
					this.close();
				}
			}
		},
		methods: {
			stopMove(event) {
				console.log(event);
				return;
			},
			show(events) {
				this.ifshow = true;
				let _open = setTimeout(() => {
					this.translateValue = 0;
					_open = null;
				}, 100)
				let _toggle = setTimeout(() => {
					this.iftoggle = true;
					_toggle = null;
				}, 300);
			},
			close() {
				if (this.timer !== null || !this.iftoggle) {
					return;
				}
				this.translateValue = -150;
				//#ifdef H5
				this.translateValue = -150
				//#endif
				this.timer = setTimeout(() => {
					this.ifshow = false;
					this.timer = null;
					this.iftoggle = false;
					this.$emit('closeCallBack', null);
					this.$emit('change', false)
				}, 300);
			},
			ableClose() {
				if (this.autoClose) {
					this.close();
				}
			},
			stopEvent(event) {},
			doSome() {

			}

		}
	}
</script>

<style lang="scss">
	.popup-layer {
		position: fixed;
		z-index: 988;
		background: rgba(0, 0, 0, .3);
		height: 100%;
		width: 100%;
		top: 0px;
		left: 0px;
		overflow: hidden;
	}

	.popup-content {
		position: fixed;
		z-index: 989;
		background: #FFFFFF;
		transition: all .3s ease;
		box-sizing: border-box;
		left: 0;
		bottom: -800rpx;
		padding-bottom: 124rpx;
		border-radius: 35rpx 35rpx 0rpx 0rpx;
	}
</style>