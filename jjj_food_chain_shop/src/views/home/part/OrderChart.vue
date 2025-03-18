<template>
	<div class="mt10">
		<div class="Echarts">
			<div id="UserChart"></div>
		</div>
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
						text: '',
						left: 'center'
					},
					tooltip: {
						trigger: 'item'
					},
					legend: {
						orient: 'vertical',
						left: 'right'
					},
					series: [{
						name: '',
						 labelLine: {
						            show: false,
						          },
						 label: {
						        show: false,
						      },
						type: 'pie',
						radius: '50%',
						data: [],
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}]
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
			/*创建图表对象*/
			myEcharts() {
				// 基于准备好的dom，初始化echarts实例
				myChart = echarts.init(document.getElementById('UserChart'));
				/*获取列表*/
				// this.getData();
			},

			/*格式数据*/
			createOption() {
				if (!this.loading) {
					let names = [];
					let xAxis = this.dataList.days;
					let series1 = [];
					this.dataList.forEach(item => {
						series1.push(item);
					});
					this.option.series[0].data = series1;
					myChart.setOption(this.option);
					myChart.resize();
				}
			},
		}
	};
</script>

<style lang="scss" scoped>
	.Echarts {
		box-sizing: border-box;
	}

	.Echarts>div {
		width: 100%;
		height: 320px;
		box-sizing: border-box;
	}
</style>