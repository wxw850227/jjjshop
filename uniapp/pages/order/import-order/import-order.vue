<template>
	
	<view class="import-order">
		
		<view class="uni-product-list">
			<view class="uni-product" v-for="(product,index) in productList" :key="index" @click="goDetailPage(product.product_id)">
				<view class="image-view">
					<image class="uni-product-image" :src="product.image[0].file_path"></image>
					<view class="number" v-if="product.sku[0].stock_num < 100000">库存：{{product.sku[0].stock_num}}</view>
				</view>
				<view class="uni-product-title">
					{{product.product_name}}
				</view>
			</view>
		</view>
		
	</view>
	
</template>

<script>
	
	export default {
		data() {
			return {
				/*列表数据*/
				productList: []
			}
		},
		onLoad() {
			document.title = '订单导入'; 
		},
		onShow:function(){
			this.getData();
		},
		methods: {
			getData() {
				let _this = this;
				_this._get('product/lists', {}, function(res) {
					_this.productList = res.data.list;
				});
			},
			/*跳转页面*/
			goDetailPage(product_id) {
				uni.navigateTo({
					url: '/pages/order/order-detail/order-detail?product_id='+product_id 
				});
			}
		}
	}
	
</script>

<style>
	
	.import-order{ padding:20upx 20upx 80upx;}
	.import-order .uni-product{ padding:0 0 20upx; margin: 10upx; background: #FFFFFF;}
	.import-order .image-view{ position: relative; margin-top: 0; margin-bottom: 0; border-bottom: 2upx solid #CCCCCC;}
	.import-order .image-view .number{ position: absolute; top: 0; right: 0; padding: 0 10upx; font-size: 24upx; background:rgb(27, 216, 13); color: #FFFFFF;}
	.import-order .uni-product-title{ margin-left: 20upx; margin-right: 10upx; padding-top: 20upx; }
	

</style>
