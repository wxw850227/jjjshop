<template>
	<view class="apply-withdraw">
		<view class="banner d-c-c d-c">
			<view class="fb white">
				<text class="f22">￥</text>
				<text class="f50">{{tableData.balance}}</text>
			</view>
			<view class="f28 white">
				账号余额
			</view>
		</view>
		<!--立即充值-->
		<view class="form-wrap p30 f30" style="margin-top: -60rpx;">

			<view class="form-item"  v-for="(item,index) in planList" :key="index">
				<view class="recharge-choice-item">
					<text>1000</text>
					<text>送200</text>
				</view>
			</view>

			<view class="form-item">
				<view class="field-name">充值金额</view>
				<input class="flex-1 text-price" name="name" type="number" value="" placeholder-class="grary" placeholder="请输入要充值的金额" />
			</view>

			<view class="d-c-c mt30">
				<button type="primary" class="btn-red" @click="applyWithdraw">立即充值</button>
			</view>

			<view class="f24">
				<view class="p-20-0 gray3">
					充值说明
				</view>
				<view class="gray9">
					1. 账户充值仅限微信在线方式支付，充值金额实时到账；<br/>2. 账户充值套餐赠送的金额即时到账；<br/>3. 账户余额有效期：自充值日起至用完即止；<br/>4. 若有其它疑问，可拨打客服电话400-000-1234
				</view>
			</view>
		</view>

	</view>
</template>

<script>
	export default {
		components:{},
		data() {
			return {
                /*数据列表*/
                tableData: {},
				/*支付类别*/
				withdraw_type:0,
				/*套餐*/
                planList:{},

			}
		},
        mounted(){
            /*获取数据*/
            this.getData();
        },
		methods: {
            /*获取数据*/
            getData(){
                let self = this;
                self._get('recharge.recharge/index', {}, function (data)
                {

                 self.tableData=data.data.userInfo;
                 self.planList=data.data.planList;
                });
            },
			/*切换提现方式*/
			TabType(e){
				this.withdraw_type=e;
			},



		}
	}
</script>

<style>
.apply-withdraw .banner{ height: 200rpx; padding-bottom: 60rpx; background: #f44f47; }
.form-wrap{ margin:30rpx; border-radius: 16rpx; background: #FFFFFF; box-shadow: 0 0 8rpx 0 rgba(0,0,0,.2);}
.form-item{ padding: 20rpx 0; margin-bottom: 20rpx; display: flex; justify-content: flex-start; align-items: center; font-size: 28rpx;}
.form-item .field-name{ width: 140rpx;}
.form-item input{ font-size: 28rpx;}
.form-item .text-price{ padding: 0 20rpx; height: 80rpx; line-height: 80rpx; border-radius: 40rpx; border: 1px solid #CCCCCC;}
.agreement-content{ max-height:60vh; overflow-y: auto;}

.recharge-choice-item{ width: 160rpx; padding: 30rpx; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; border: 1px solid #E2231A; border-radius: 16rpx; color: #E2231A; flex-direction: column;}
</style>
