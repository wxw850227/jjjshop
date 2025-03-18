<template>
	<picker-view class="p5" v-if="visible" :value="value" @change="bindChange">
		<picker-view-column>
			<view class="item" v-for="(item, index) in years" :key="index">{{ item }}年</view>
		</picker-view-column>
		<picker-view-column>
			<view class="item" v-for="(item, index) in months" :key="index">{{ item }}月</view>
		</picker-view-column>
		<picker-view-column>
			<view class="item" v-for="(item, index) in days" :key="index">{{ item }}日</view>
		</picker-view-column>
		<picker-view-column>
			<view class="item" v-for="(item, index) in hours" :key="index">{{ item }}时</view>
		</picker-view-column>
		<picker-view-column>
			<view class="item" v-for="(item, index) in minutes" :key="index">{{ item }}分</view>
		</picker-view-column>
	</picker-view>
</template>

<script>
export default {
	data() {
		const date = new Date();
		const years = [];
		const year = date.getFullYear();
		const months = [];
		const month = date.getMonth() + 1;
		const days = [];
		const day = date.getDate();
		const hours = [];
		const hour = date.getHours();
		const minutes = [];
		const minute = date.getMinutes();
		for (let i = date.getFullYear(); i <= 2030; i++) {
			years.push(i);
		}
		for (let i = 1; i <= 12; i++) {
			months.push(i);
		}
		for (let i = 1; i <= 31; i++) {
			days.push(i);
		}
		for (let i = 1; i <= 24; i++) {
			hours.push(i);
		}
		for (let i = 1; i <= 60; i++) {
			minutes.push(i);
		}
		return {
			title: 'picker-view',
			years,
			year,
			months,
			month,
			days,
			day,
			hours,
			hour,
			minutes,
			minute,
			value: [0, month-1, day-1, hour, minute],
			visible: true
		};
	},
	created() {
		this.emitFunc(this.value);
	},
	methods: {
		/*选择触发*/
		bindChange: function(e) {
			const val = e.detail.value;
			this.emitFunc(val);
		},
		
		emitFunc(val){
			this.year = this.years[val[0]];
			this.month = this.months[val[1]];
			this.day = this.days[val[2]];
			this.hour = this.hours[val[3]];
			this.minute = this.minutes[val[4]];
			this.$emit('get', this.year + '-' + this.month + '-' + this.day + ' ' + this.hour + ':' + this.minute);
		}
	}
};
</script>

<style scoped>
picker-view {
	width: 100%;
	height: 240rpx;
	text-align: center;
	line-height: 70rpx;
	border: 1rpx solid #cccccc;
	padding: 10rpx 2rpx;
}
</style>
