<template>
	<view class="refund-apply pb100" v-if="!loadding">
		<form @submit="formSubmit" @reset="formReset">
			<view class="one-product d-s-c p30 bg-white ">
				<view class="cover">
					<image :src="product.image.file_path" mode="aspectFit"></image>
				</view>
				<view class="flex-1">
					<view class="pro-info">{{product.product_name}}</view>
					<view class="pt10 p-0-30 f24 gray9">
						<text class="">
							单价：¥{{product.line_price}}
						</text>
						<text class="ml20">
							数量：{{product.total_num}}
						</text>
					</view>
				</view>
			</view>

			<!-- 服务类型 -->
			<view class="group bg-white">
				<view class="group-hd border-b-e">
					<view class="left">
						<text class="min-name">服务类型</text>
					</view>
				</view>
				<view class="d-s-c p-20-0">
					<button type="default" :class="type==10?'btn-red-border':''" @click="tabType(10)">退货/退款</button>
					<button type="default" :class="type==20?'ml20 btn-red-border':'ml20'" @click="tabType(20)">换货
					</button>
				</view>
			</view>

			<!--申请原因-->
			<view class="group bg-white">
				<view class="group-hd">
					<view class="left">
						<text class="min-name">申请原因</text>
					</view>
				</view>
				<view class="d-s-c">
					<textarea style="height:120rpx" class="p10 box-s-b border" value="" name="content" placeholder="请详细填写申请原因，注意保持商品的完好，建议您先与卖家沟通" />
					</view>
            </view>

            <!--退款金额-->
            <view class="group bg-white" v-if="type==10">
                <view class="group-hd">
                    <view class="left">
                        <text class="min-name">退款金额：</text>
                        <text class="red">¥{{product.total_price}}</text>
                    </view>
                </view>
            </view>

            <!--上传图片-->
            <view class="group bg-white">
                <view class="group-hd">
                    <view class="left">
                        <text class="min-name">上传凭证</text>
                        <text class="gray9">(最多6张)</text>
                    </view>
                </view>
                <view class="upload-list d-s-c">
                    <view class="item" v-for="(imgs,img_num) in images" :key="img_num">
                        <image :src="imgs.file_path" mode="aspectFit"></image>
                    </view>
                    <view class="item">
                        <view class="upload-btn d-c-c d-c" @click="openUpload()">
                            <text class="icon iconfont icon-xiangji"></text>
                            <text class="gray9">上传图片</text>
                        </view>
                    </view>
                </view>
            </view>

            <view class="foot-btns">
                <button form-type="submit" class="btn-red">确认提交</button>
                <!--<button type="primary" >确认提交</button>-->
            </view>
        </form>
		<!--上传图片-->
		<Upload v-if="isUpload" @getImgs="getImgsFunc"></Upload>
    </view>


</template>

<script>
	import Upload from '@/components/upload/upload.vue';
	import imageComponent from '@/components/imageComponent/imageComponent.vue';
    export default {
		components: {
			Upload,
			imageComponent
		},
        data() {
            return {
				/*是否加载完成*/
				loadding: true,
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
                type: 10,
				/*是否打开上传图片*/
				isUpload:false,
                /*订单商品id*/
                order_product_id: 0,
                /*订单商品*/
                product: {},
				images:[],
            }
        },
        onLoad(e){
            this.order_product_id = e.order_product_id;
        },
        mounted() {
			uni.showLoading({
				title: '加载中',
				mask:true
			});
            /*获取订单详情*/
            this.getData();
        },
        methods: {
            /*获取数据*/
            getData(){
                let self = this;
                let order_product_id = self.order_product_id;
                self._get( 'user.refund/apply',  {
                        order_product_id: order_product_id
                    },  function (res)  {
                        self.product = res.data.detail;
						self.loadding = false;
						uni.hideLoading();
                    }
                );
            },
            /*切换服务类型*/
            tabType(e){
                this.type = e;
            },
            /*提交表单*/
            formSubmit: function (e)
            {
                let self = this;
                var formdata = e.detail.value;
                formdata.type = self.type;
                formdata.order_product_id = self.order_product_id;
				formdata.images= JSON.stringify(self.images);
				console.log(formdata);
                self._post('user.refund/apply', formdata, function (res)
                {
                    uni.showToast({
                        title: res.msg,
                        duration: 3000,
                        complete:function(){
                            uni.navigateTo({
                                url: '/pages/order/refund/index/index',
                            });
                        }
                    });
                });
            },
			/*打开上传图片*/
			openUpload(){
				this.isUpload=true;
			},
			
			/*获取上传的图片*/
			getImgsFunc(e){
				let self=this;
				// self.images.push(e);
				console.log(self.images=self.images.concat(e));
				self.isUpload=false;
				 
			}
        }
    }
</script>

<style>

</style>
