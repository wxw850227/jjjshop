<template>
	<view class="diy-page">
		<diy :diyItems="items"></diy>
	</view>
</template>

<script>
	import diy from '@/components/diy/diy.vue';
	export default {
		components: {
			diy
		},
		data() {
			return {
				/*页面ID*/
				page_id:null,
				/*diy类别*/
				items:{},
				/*页面信息*/
				page_info:{}
			}
		},
		onLoad(e) {
			this.page_id=e.page_id;
			this.getData();
		},
		methods: {
			/*获取数据*/
			getData(page_id) {
				let self = this;
				self._get('index/index', {
					page_id: self.page_id
				}, function(res) {
					self.page_info = res.data.page;
					self.items = res.data.items;
					self.setPage(self.page_info);
					
				});
			},
			
			/*设置页面*/
			setPage(param){
				uni.setNavigationBarTitle({
				    title: param.params.name
				});
				
				/* uni.setNavigationBarColor({
					frontColor:param.style.titleTextColor,
					backgroundColor:param.style.titleBackgroundColor
				}); */
			}
		},
		
	}
</script>

<style>

</style>
