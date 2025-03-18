<template>
	<Popup :show="isTimer" :width="width" :height='height' :padding="0" @hidePopup="hidePopupFunc" type='bottom'>
		<view class="d-b-c time_picker">
			<view class="" style="width: 40%;">
				<scroll-view style="height: 600rpx;" scroll-y="true">
					<template v-for="(item,index) in hours" :key='index'>
						<view v-if="myhours <= item.end"
							:class="pickhours&&item.start!=pickhours.start?'hours':'hours-active'" @click="pickH(item)">
							{{item.start+':00~'+item.end+':00'}}
						</view>
					</template>

				</scroll-view>
			</view>
			<view class="flex-1">
				<scroll-view style="height: 600rpx;" scroll-y="true">
					<template v-for="(item,index) in minute" :key='index'>
						<view
							v-if="pickhours&&myhours>=pickhours.start&&myminute<=item&&myhours!=inittime(pickhours.end)"
							@click="picktime(pickhours.start+':'+item)">
							{{pickhours.start+":"+item}}
						</view>
					</template>
					<template v-for="(item,index) in minute" :key='index'>
						<view v-if="pickhours&&myhours<=pickhours.start&&myhours!=inittime(pickhours.end)"
							@click="picktime(pickhours.start+':'+item)">
							{{pickhours.start+":"+item}}
						</view>
					</template>
					<template v-for="(item,index) in minute" :key='index'>
						<view v-if="pickhours&&myhours!=inittime(pickhours.end)"
							@click="picktime(inittime(pickhours.end) +':'+item)">
							{{inittime(pickhours.end) +":"+item}}
						</view>
					</template>
					<template v-for="(item,index) in minute" :key='index'>
						<view v-if="pickhours&&myminute<=item&&myhours==inittime(pickhours.end)"
							@click="picktime(inittime(pickhours.end) +':'+item)">
							{{inittime(pickhours.end)+":"+item}}
						</view>
					</template>

				</scroll-view>
			</view>
		</view>

	</Popup>
</template>

<script>
	import Popup from '@/components/uni-popup.vue';
	export default {
		components: {
			Popup
		},
		data() {
			return {
				/*宽度*/
				width: 750,
				height: 600,
				/*数据对象*/
				dataModel: {},
				minute: [
					'10', '25', '40', '55',
				],
				hours: [{
						start: '00',
						end: "02"
					},
					{
						start: '02',
						end: "04"
					},
					{
						start: '04',
						end: "06"
					},
					{
						start: '06',
						end: "08"
					},
					{
						start: '08',
						end: "10"
					},
					{
						start: '10',
						end: "12"
					},
					{
						start: '12',
						end: "14"
					},
					{
						start: '14',
						end: "16"
					},
					{
						start: '16',
						end: "18"
					},
					{
						start: '18',
						end: "20"
					},
					{
						start: '20',
						end: "22"
					},
					{
						start: '22',
						end: "24"
					},
				],
				myhours: '',
				myminute: '',
				pickhours: {
					start: '',
					end: ""
				},
				mealtime: ''
			}
		},
		props: ['isTimer'],
		onShow() {

		},
		watch: {
			'isTimer': function(n, o) {
				if (n != o) {
					this.getData()
				}
			}
		},
		methods: {
			/*获取数据*/
			getData() {
				let self = this;
				let myDate = new Date();
				self.myhours = myDate.getHours(); //获取当前小时数(0-23)
				self.myminute = myDate.getMinutes(); //获取当前分钟数(0-59)
				self.pickH(self.hours[self.myhours])
				self.$nextTick(function() {
					self.hours.forEach((item, index) => {
						if (item.start <= self.myhours && self.myhours < item.end) {
							self.pickH(item)
						}
					})
				})
			},
			pickH(n) {
				this.pickhours = n;
			},
			/*关闭弹窗*/
			hidePopupFunc(e) {
				this.$emit('close', '');
			},
			picktime(n) {
				this.mealtime = n;
				this.$emit('close', this.mealtime);

			},
			inittime(n) {
				let time = n
				time = n * 1 - 1
				if (n <= 10) {
					return '0' + time
				} else {
					return time
				}
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
					mask: true,
					duration: 2000
				});
			}

		}
	}
</script>

<style scoped lang="scss">
	.hours-active {
		background: #FFFFFF;
		color: #000000;
	}

	.hours {
		background-color: #f4f4f4;
		color: #666666;
	}

	.mpservice-wrap .mp-image {
		width: 560rpx;
		margin-top: 40rpx;
	}

	.mpservice-wrap .mp-image image {
		width: 100%;
	}

	scroll-view ::-webkit-scrollbar {
		width: 0;
		height: 0;
		background-color: transparent;
	}

	.time_picker {
		position: fixed;
		bottom: 0;
		width: 100%;
		background: #ffffff;
	}
</style>