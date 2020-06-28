<template>
	<view :class="Visible?'usable-coupon open':'usable-coupon close'">
		<view class="popup-bg" @click="closePopup"></view>
		<view class="main" v-on:click.stop>
			<view class="p-0-30">
				
			<view @click="selectCoupon(item.user_coupon_id)" class="coupon-item mt30 coupon-item-red" v-for="(item,index) in datalist" :key="index">
			    <!--装饰用的小圆-->
			    <view class="circles">
			        <text v-for="(circle,num) in 6" :key="num"></text>
			    </view>
			    <view class="info">
			        <view class="d-c-c d-c">
			            <block v-if="item.coupon_type == 10">
			                <view class="price">
			                    <text>￥</text>
								<text class="f40">{{ item.reduce_price }}</text>
			                </view>
			            </block>
			            <block v-if="item.coupon_type == 20">
							<text class="f50">{{ item.discount }}折</text>
			            </block>
						<text class="f22">满{{ item.min_price }}元可用</text>
			        </view>
			    </view>
			    <view class="operation d-b-c w-b">
			        <view class="flex-1 o-h">
			            <view class="f34">
							{{item.name}}
			            </view>
			            <view class="gray9 f24">
							{{item.coupon_type.text}}
			            </view>
			
			            <block v-if="item.expire_type == 10 ">
			                <view class="mt30 f24 gray9">
								领取{{ item.expire_day }}天内有效
			                </view>
			            </block>
			            <block v-if="item.expire_type == 20 ">
			                <view class="mt30 f24 gray9">
								{{ item.start_time.text }}~{{ item.end_time.text }}
			                </view>
			            </block>
			        </view>
			        <!-- <view class="btns d-c-c">
			            <checkbox value="cb" color="#FFCC33" style="transform:scale(0.7)" />
			        </view> -->
			    </view>
			</view>
			<view class="mt30">
				<button type="default" @click="closePopup" class="btn-gray-border">不使用优惠券</button>
			</view>
			</view>
		</view>
	</view>
</template>

<script>
export default{
	data(){
		return {
			/*是否可见*/
			Visible: false,
			/*优惠券列表*/
			datalist:{}
		}
	},
	props: ['isCoupon','couponList'],

	onLoad() {
	
	},
	mounted() {
	
	},
	watch: {
		'isCoupon': function(n, o) {
			if (n != o) {
				this.Visible = n;
                this.datalist=this.couponList;
			}
		}
	},
	methods:{

	    /*选择优惠券*/
        selectCoupon(e){
            //this.$emit('close', e);
			this.closePopup(e);
		},
		/*关闭弹窗*/
		closePopup(e) {
			this.$emit('close', e);
		},
	}
}
</script>

<style>
	.usable-coupon .popup-bg{ position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0, 0, 0, .6);
		z-index: 99;}
.usable-coupon .main {
			position: fixed;
			width: 100%;
			bottom: 0;
			min-height: 200rpx;
			max-height: 900rpx;
			background-color: #fff;
			transform: translate3d(0, 980rpx, 0);
			transition: transform .2s cubic-bezier(0, 0, .25, 1);
			bottom: env(safe-area-inset-bottom);
			z-index: 99;
		}
		.usable-coupon .main {
			position: fixed;
			width: 100%;
			bottom: 0;
			min-height: 200rpx;
			max-height: 900rpx;
			background-color: #fff;
			transform: translate3d(0, 980rpx, 0);
			transition: transform .2s cubic-bezier(0, 0, .25, 1);
			bottom: env(safe-area-inset-bottom);
			z-index: 99;
		}
		
		.usable-coupon.open .main {
			transform: translate3d(0, 0, 0);
		}
		.usable-coupon.close .popup-bg {
			display: none;
		}
		.coupon-item-red .operation{ background: #fdf1df;}
</style>
