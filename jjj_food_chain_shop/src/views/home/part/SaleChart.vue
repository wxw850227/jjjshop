<template>
	<div class="Echarts">
		<div id="SaleChart"></div>
	</div>
</template>

<script>
	import {
		formatDate
	} from '@/utils/DateTime.js';
	import * as echarts from 'echarts';
	let myChart;
	export default {
		data() {
			let endDate = new Date();
			let startDate = new Date();
			startDate.setTime(startDate.getTime() - 3600 * 1000 * 24 * 7);
			return {
				/*是否正在加载*/
				loading: true,
				/*类别*/
				activeName: 'order',
				/*时间快捷选项*/
				pickerOptions: {
					shortcuts: []
				},
				datePicker: [formatDate(startDate, 'yyyy-MM-dd'), formatDate(endDate, 'yyyy-MM-dd')],
				/*数据对象*/
				dataList: null,
				/*交易统计图表对象*/
				// myChart: null,
				/*图表数据*/
				option: {
					title: {
						//text: 'ECharts 入门示例'
					},
					grid: {
						top: '10%',
						left: '0',
						right: '0',
						bottom: '8%',
						containLabel: true
					},
					color: ['#6797ef'],
					tooltip: {
						trigger: 'axis',
						backgroundColor: 'rgba(1, 13, 19, 0.5)',
						borderWidth: 1,
						axisPointer: {
							type: 'shadow'
						},
						formatter: function(params) {
							var str = '';
							if (params.length > 0) {
								str = params[0].name + '<br/>';
							}
							for (var i = 0; i < params.length; i++) {
								if (params[i].seriesName !== '') {
									str += params[i].seriesName + ':' + params[i].value + '<br/>';
								}
							}
							return str;
						},
						textStyle: {
							color: 'rgba(212, 232, 254, 1)'
						},
						extraCssText: 'z-index:2'
					},
					legend: {
						right: 20,
						top: 30,
						icon: 'circle',
						itemWidth: 15,
						itemHeight: 10,
						itemGap: 15,
						borderRadius: 4,
						textStyle: {
							color: '#000',
							fontFamily: 'Alibaba PuHuiTi',
							fontSize: 12,
							fontWeight: 400
						}
					},
					xAxis: {
						type: 'category',
						data: [],
						axisLine: {
							show: false
						},
						axisTick: {
							show: false
						},
						axisLabel: {
							show: true,
							textStyle: {
								color: '#393939' //X轴文字颜色
							}
						}
					},
					yAxis: [{
						type: 'value',
						axisLine: {
							show: false,
							lineStyle: {
								color: '#eeeeee'
							}
						},
						axisTick: {
							show: false
						},
						axisLabel: {
							color: '#393939',
							fontSize: 12
						},
						splitLine: {
							show: true,
							lineStyle: {
								color: '#e7e9ef',
								type: 'dashed'
							}
						}
					}],
				}
			};
		},
		props: ['listData'],
		created() {

		},
		mounted() {
			this.myEcharts();
		},
		methods: {
			changeData(dataList) {
				let self = this;
				// self.loading = true;
				self.dataList = dataList;
				console.log(self.dataList)
				self.loading = false;
				self.createOption();
			},
			/*切换*/
			handleClick(e) {
				this.getData();
			},

			/*选择时间*/
			changeDate() {
				this.getData();
			},

			/*创建图表对象*/
			myEcharts() {
				// 基于准备好的dom，初始化echarts实例
				myChart = echarts.init(document.getElementById('SaleChart'));
				/*获取列表*/
				// this.getData();
			},

			/*格式数据*/
			createOption() {
				if (!this.loading) {
					let names = [];
					let xAxis = this.dataList.days;
					let series1 = [];
					this.dataList.data.forEach(item => {
						series1.push(item.total_money);
					});
					names = ['销售金额'];
					// 指定图表的配置项和数据
					this.option.xAxis = {
						type: 'category',
						boundaryGap: false,
						data: xAxis
					};
					
					this.option.color = ['#6797ef'];
					this.option.series = [{
						name: names[0],
						type: 'line',
						symbol: 'circle', //标记的图形为实心圆
						symbolSize: 0, //标记的大小
						data: series1,
						lineStyle: {
							width: 2,
							color: '#6797ef'
						}
					}];

					myChart.setOption(this.option);
					myChart.resize();
					
				}
			},

			/*获取列表*/
			getData() {
				let self = this;
				// self.loading = true;
				self.dataList = self.$props.listData;
				self.loading = false;
				self.createOption();
			}
		}
	};
</script>

<style lang="scss" scoped>
	.Echarts {
		box-sizing: border-box;
	}

	.Echarts > div {
		width: 100%;
		height: 330px;
		box-sizing: border-box;
	}
</style>