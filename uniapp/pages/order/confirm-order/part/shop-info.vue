<template>
	<div>
		<!--地址-->
		<template v-if="!extract_shop.shop_id">
			<view class="add-address d-s-c" @click="addAddress()">
				<view class="icon-box mr10">
					<span class="icon iconfont icon-dizhi1"></span>
				</view>
				<text>请选择自提点</text>
			</view>
		</template>
		<template v-else>
			<view class="address-defalut-wrap">
				<view class="info d-s-s">
					<text class="state">当前自提点</text>
					<text class="province-c-a flex-1">{{extract_shop.region.province}} {{extract_shop.region.city}}
						{{extract_shop.region.region}} </text>
				</view>
				<view class="address">
					<text class="fb gray3">{{extract_shop.address}}</text>
					<view class="icon-box" @click="addAddress()">
						<span class="icon iconfont icon-jiantou"></span>
					</view>
				</view>
				<view class="user">
					<text class="name">{{extract_shop.shop_name}}</text>
					<text class="tel">{{extract_shop.phone}}</text>
				</view>
			</view>
		</template>
		<view class="addree-bottom">
			<view class="stripe" v-for="(item,index) in 10" :key="index">
				<text :class="index%2==0?'red':'blue'"></text>
				<text class="white"></text>
			</view>
		</view>

		<!--自取人的信息-->
		<view class="buy-checkout">
			<view class="item">
				<text class="key">联系人：</text>
				<input type="text" v-model="linkman"  class="flex-1" placeholder-style="color:#cccccc;font-size:24rpx;" placeholder="请输入联系人"
				 value="" />
			</view>
			<view class="item">
				<text class="key">联系电话：</text>
				<input type="text" v-model="phone" class="flex-1" placeholder-style="color:#cccccc;font-size:24rpx;" placeholder="请输入联系电话"
				 value="" />
			</view>
		</view>

	</div>
</template>

<script>
	export default {
		data() {
			return {
				linkman:'',
				phone:''
			}
		},
		props: ['extract_shop'],
		onLoad() {},
		mounted() {

		},
		methods: {
			/*添加地址*/
			addAddress() {
				let shop_id = -1;
				if(this.extract_shop.shop_id){
					shop_id = this.extract_shop.shop_id;
				}
				uni.navigateTo({
					url: '/pages/shop/address/address?shop_id='+shop_id,
				});
			},
			
			
		}
	}
</script>

<style>
</style>
