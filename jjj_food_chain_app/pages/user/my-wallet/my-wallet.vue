<template>
	<view class="index">
		<view class="top_bg" :style="'height:'+(368+topBarHeight()*2+topBarTop()*2)+'rpx;'">
			<!-- #ifdef MP-WEIXIN || APP-PLUS -->
			<view class="ww100" :style="'height:'+topBarTop()+'px;'"></view>
			<view class="tc  head_top" :style="topBarHeight() == 0 ? '': 'height:'+topBarHeight()+'px;'">
				<view class="reg180" @click="goback"><text class="icon iconfont icon-jiantou" style="font-size: 48rpx;"></text></view>
				<view class="fb">我的钱包</view>
			</view>
			<!-- #endif -->
			<view class="card-top">
				<view class="f60 white"><text class="f36">￥</text>{{balance}}</view>
				<view class="f26 white">账户余额(元)</view>
			</view>
		</view>
		<view class="wallet-content">
			<view class="index-body">
				<view class="body-head">
					<view class="f30">钱包明细</view>
					<view class="f26 gray9" @click="gotoList('all')">更多明细 <text class="icon iconfont icon-jiantou" style="color: #999999;font-size: 22rpx;"></text></view>
				</view>
				<view class="body-item" v-for="(item,index) in dataList" :key="index">
					<view class="body-item-top">
						<view class="body-item-top-left f32 ">{{ item.scene.text }}</view>
						<view class="body-item-top-right f36" v-if="item.money > 0">+{{ item.money }}</view>
						<view class="body-item-top-right f36" v-else>{{ item.money }}</view>
					</view>
					<view class="body-item-bottom">
						<view class="body-item-bottom-left font-24 gray9">{{ item.create_time }}</view>
					</view>
				</view>
			</view>
		</view>
		
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				dataList: [],
				balance: '',
				balance_open: 0,
				cash_open:0
			}
		},
		onShow() {
			/*获取数据*/
			this.getData();
		},
		methods: {
			/*获取数据*/
			getData() {
				let self = this;
				self.loading = true;
				self._get(
					'balance.log/index', {},
					function(res) {
						self.loading = false;
						self.dataList = res.data.list;
						self.balance = res.data.balance;
						self.balance_open = res.data.balance_open;
						self.cash_open = res.data.cash_open;
					}
				);
			},
			gotoList(type) {
				this.gotoPage('/pages/user/my-wallet/my-balance?type=' + type);
			},
			goback() {
				uni.navigateBack();
			},
			gotoPay() {
				this.gotoPage('/pages/order/recharge');
			},
		}
	}
</script>

<style>
	page {
		background: #f2f2f2;
	}

	.font-color-ccc {
		color: #CCCCCC;
	}

	.icon-jiantou::before {
		font-size: 24rpx;
	}
	
	.font-24 {
		font-size: 24rpx;
	}

	.font-28 {
		font-size: 28rpx;
	}

	.font-32 {
		font-size: 32rpx;
	}

	.font-72 {
		font-size: 72rpx;
	}

	.width-150 {
		width: 150rpx;
		text-align: center;
	}

	.index {
		width: 750rpx;
	}
	.wallet-content{
		margin-top: -93rpx;
	}
	.index-head {
		width: 710rpx;
		margin: 0 auto;
		height: 160rpx;
		background: #FFFFFF;
		border-radius: 12rpx;
		margin-bottom: 50rpx;
	}

	.bg-image {
		width: 660rpx;
		height: 340rpx;
		background-image: url('../../../static/card.png');
		background-size: 100% 100%;
		margin: 0 auto;
		margin-top: 50rpx;
		display: flex;
		flex-direction: column;
	}

	.card-top {
		width: 750rpx;
		height: 290rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}

	.card-bottom {
		/* width: 660rpx; */
		height: 160rpx;
		display: flex;
		justify-content: space-around;
		align-items: center;
	}

	.index-body {
		width: 710rpx;
		/* background-color: rgba(0, 0, 0, 0.1); */
		background-color: white;
		margin: 0 auto;
		padding: 30rpx;
		box-sizing: border-box;
		border-radius: 12rpx;
		padding-top: 0;
	}

	.body-head {
		height: 80rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-bottom: 1rpx #f2f2f2 solid;
	}

	.body-item {
		display: flex;
		flex-direction: column;
		justify-content: center;
		height: 126rpx;
		border-bottom: 1rpx #f2f2f2 solid;
	}

	.body-item-top {
		display: flex;
		justify-content: space-between;
		align-items: center;
		color: #333333;
		margin-bottom: 10rpx;
	}

	.body-item-bottom {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.reg180 {
		padding-right: 20rpx;
		text-align: right;
		transform: rotateY(180deg);
		position: absolute;
		bottom: 0;
	}
	.reg180 .icon-jiantou::before {
		font-size: 32rpx;
	}
	.reg180 .icon-jiantou {
		color: #FFFFFF;
		font-size: 32rpx;
	}

	.head_top {
		position: relative;
		height: 30px;
		line-height: 30px;
		color: #FFFFFF;
		font-size: 32rpx;
	}

	.bg_topimg {
		position: absolute;
		top: 0;
		width: 100%;
		height: 400rpx;
		z-index: -1;
	}

	.top_bg {
		width: 750rpx;
		height: 368rpx;
		background: linear-gradient(180deg, #FF774D 0%, #FF422E 100%);
	}

	.wallet_img {
		width: 48rpx;
		height: 48rpx;
		margin: 0 auto;
		margin-bottom: 14rpx;
	}

	.index-head .card-bottom .pr .icon-jiantou {
		position: absolute;
		right: 95rpx;
		color: #999999;
		font-size: 26rpx;
		top: 24rpx;
	}

	.none_line {
		width: 1rpx;
		height: 80rpx;
		background-color: #D9D9D9;
	}

	.body-item-top-right {
		margin-bottom: -30rpx;
	}
</style>
