<template>
    <view class="my-wallet p30 bg-white tc" v-if="!loadding">
        <view class="d-c-c p-30-0">
            <span class="icon iconfont icon-qianbao" style="font-size: 200rpx; color: #d2b975;"></span>
        </view>
        <view class="">
            <text class="f24">￥</text>
            <text class="f40 fb">{{user_info.balance}}</text>
        </view>
        <view class="pt10 f24 gray9">
            账户余额（元）
        </view>
      <!--  <view class="mt30" style="margin-top: 200rpx;">
            <button type="primary" class="btn-red" @click="gotoPage('/pages/user/my-wallet/recharge/recharge')">充值
            </button>
        </view>-->
        <view class="p-30-0 d-a-c f28" style="margin-top: 40rpx;">
            <!--<text @click="gotoPage('/pages/user/my-wallet/record/record')">充值记录</text>-->
            <text @click="gotoPage('/pages/user/my-wallet/bill/bill')">账单详情</text>
        </view>
    </view>
</template>

<script>
    export default {
        data() {
            return {
				/*是否加载完成*/
				loadding:true,
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
                user_info: {},
            }
        },
        mounted(){
            /*获取数据*/
            this.getData();
        },
        methods: {
            /*获取数据*/
            getData(){
                let _this = this;
                _this._get('user.wallet/index', {}, function (data)
                {
                    _this.user_info = data.data.userInfo;
					_this.loadding=false;
					uni.hideLoading();

                });
            },
            /*跳转页面*/
            gotoPage(path) {
                uni.navigateTo({
                    url: path
                });
            },
        }
    }
</script>

<style>

</style>
