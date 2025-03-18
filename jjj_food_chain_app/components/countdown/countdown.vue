<template>
	<!--倒计时-->
	<view class="countdown">
		<template v-if="config.type == null">
			<text v-if="status == 0">{{title}}</text>
			<text v-if="status == 1">活动具体时间：</text>
			<text v-if="status == 2">活动结束时间：</text>
			<text class="box">{{ day }}</text>
			<text class="p-0-10">天</text>
			<text class="box">{{ hour }}</text>
			<text class="p-0-10">:</text>
			<text class="box">{{ minute }}</text>
			<text class="p-0-10">:</text>
			<text class="box">{{ second }}</text>
			<text class="p-0-10"></text>
		</template>
		<template v-if="config.type === 'text'">
			{{title}}{{ parseInt(day * 24) + parseInt(hour) }}:{{ minute }}:{{ second }}
		</template>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				/*状态*/
				status: 0,
				/*天*/
				day: '0',
				/*时*/
				hour: '0',
				/*分*/
				minute: '0',
				/*秒*/
				second: '0',
				/*定时器*/
				timer: null,
				/*剩余秒*/
				totalSeconds: 0,
				/*标题*/
				title: '活动剩余：'
			};
		},
		props: {
			config: {
				type: Object,
				default: function() {
					return {
						type: 'all',
					};
				}
			}
		},
		created() {},
		watch: {
			config: {
				deep: true,
				handler: function(n, o) {
					if (n != o && n.endstamp != 0) {
						if (n.title && typeof n.title != 'undefined') {
							this.title = n.title;
						}
						this.setTime();
					}
				},
				immediate: true
			}
		},
		methods: {
			/*轮循*/
			setTime() {
				let self = this;
				self.timer = setInterval(function() {
					self.init();
				}, 1000);
			},

			/*初始化*/
			init() {
				let nowseconds = Date.now() / 1000;
				if (nowseconds < this.config.startstamp) {
					//活动时间还没到
					this.status = 1;
				} else if (nowseconds > this.config.endstamp) {
					//活动已过期
					this.status = 2;
				} else {
					//活动进行中
					this.totalSeconds = parseInt(this.config.endstamp - nowseconds);
					this.status = 0;
					this.countDown();
				}
				this.$emit('returnVal', this.status);
			},

			/*计算时分秒*/
			countDown() {
				let totalSeconds = this.totalSeconds;
				//天数
				let day = Math.floor(totalSeconds / (60 * 60 * 24));
				//取模（余数）
				let modulo = totalSeconds % (60 * 60 * 24);
				//小时数
				let hour = Math.floor(modulo / (60 * 60));
				modulo = modulo % (60 * 60);
				//分钟
				let minute = Math.floor(modulo / 60);
				//秒
				let second = modulo % 60;
				this.day = this.convertTwo(day);
				this.hour = this.convertTwo(hour);
				this.minute = this.convertTwo(minute);
				this.second = this.convertTwo(second);
				this.totalSeconds--;
			},

			/*转两位数*/
			convertTwo(n) {
				let str = '';
				if (n < 10) {
					str = '0' + n;
				} else {
					str = n;
				}
				return str;
			},

			/*把时间戳转普通时间*/
			getLocalTime(nS) {
				return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/, ' ');
			},
			clear() {
				clearInterval(this.timer);
				this.timer = null;
			},
		},

		destroyed() {
			clearInterval(this.timer);
		}
	};
</script>

<style lang="scss">
	.countdown .box {
		display: inline-block;
		padding: 4rpx;
		/* width: 34rpx; */
		border-radius: 8rpx;
		background: #FFFFFF;
		text-align: center;
		color: #ffffff;
	}
</style>
