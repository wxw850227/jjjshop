<template>
	<view class="product-detail" v-if="!loadding">
		<!--图片-->
		<view class="product-pic">
			<swiper class="swiper" :indicator-dots="indicatorDots" :autoplay="autoplay" :interval="interval" :duration="duration">
				<swiper-item v-for="(item, index) in imagList" :key="index"><image :src="item.file_path" mode="aspectFit"></image></swiper-item>
			</swiper>
		</view>
		<!--基本信息-->
		<view class="bg-white">
			<view class="price-wrap">
				<view class="left">
					<template>
						<view class="new-price">
							¥
							<text class="num">{{ detail.product_sku.product_price }}</text>
						</view>
						<text class="old-price">¥{{ detail.product_sku.line_price }}</text>
					</template>
				</view>
				<template>
					<text class="already-sale">已出售{{ detail.product_sales }}件</text>
				</template>
			</view>
			<view class="product-name">{{ detail.product_name }}</view>
			<view class="product-describe">{{ detail.selling_point }}</view>
		</view>

		<!--评价-->
		<view class="product-comment">
			<view class="group-hd">
				<view class="left">
					<text class="min-name">评价</text>
					<text class="num">({{ detail.comment_data_count }}条)</text>
				</view>
				<view class="right" @click="lookEvaluate(detail.product_id)">
					<text class="more">查看更多</text>
					<span class="icon iconfont icon-jiantou"></span>
				</view>
			</view>
			<view class="comment-list" v-if="detail.comment_data_count > 0">
				<view class="item" v-for="(item, index) in detail.commentData" :key="index">
					<view class="cmt-user">
						<view class="left">
							<image class="photo" :src="item.user.avatarUrl" mode="aspectFill"></image>
							<text class="name">{{ item.user.nickName }}</text>
						</view>
						<text class="datetime">{{ item.create_time }}</text>
					</view>
					<view class="content">{{ item.content }}</view>
				</view>
			</view>
		</view>

		<!--详情内容-->
		<view class="product-content">
			<view class="group-hd border-b-e">
				<view class="left"><text class="min-name">商品介绍</text></view>
			</view>
			<view class="content-box" v-html="detail.content"></view>
		</view>

		<!--分享-->
		<view class="share-box">
			<button type="primary" @click="showShare"><text class="icon iconfont icon-share"></text></button>
		</view>

		<!--底部按钮-->
		<view class="btns-wrap">
			<view class="icon-box d-c-c"><button class="icon iconfont icon-kefu2" open-type="contact" session-from="wxapp"></button></view>
			<view class="icon-box d-c-c" @click="gotocart">
				<button class="pr icon iconfont icon-gouwuche">
					<text v-if="cart_total_num > 0" class="num">{{ cart_total_num }}</text>
				</button>
			</view>
			<template>
				<button type="primary" class="add-cart" @click="openPopup('card')">加入购物车</button>
				<button type="primary" class="buy" @click="openPopup('order')">立即购买</button>
			</template>
		</view>

		<!--购物确定-->
		<spec :isPopup="isPopup" :productModel="productModel" @close="closePopup"></spec>

		<!--底部弹窗-->
		<share :isbottmpanel="isbottmpanel" :product_id="product_id" @close="closeBottmpanel"></share>

		<!--生成图片-->
		<uniPopup :show="isCreatedImg" type="middle" @hidePopup="hidePopupFunc">
			<view class="d-c-c d-c create-img">
				<image :src="poster_img" mode="widthFix"></image>
				<!-- #ifdef MP -->
				<button class="btn-red mt20" type="default" @click="savePosterImg">保存图片</button>
				<!-- #endif  -->
				<!-- #ifdef H5 -->
				<button class="btn-red mt20" type="default">长按保存图片</button>
				<!-- #endif -->
			</view>
		</uniPopup>
	</view>
</template>

<script>
import share from './popup/share.vue';
import spec from './popup/spec.vue';
import uniPopup from '@/components/uni-popup.vue';
import utils from '@/common/utils.js';
export default {
	components: {
		spec,
		share,
		uniPopup
	},
	data() {
		return {
			/*是否加载完成*/
			loadding: true,
			indicatorDots: true,
			autoplay: true,
			interval: 2000,
			duration: 500,
			/*是否确定购买弹窗*/
			isPopup: false,
			buyNow: false,
			/*商品id*/
			product_id: '',
			url: '',
			/*商品详情*/
			detail: {
				product_sku: {},
				show_sku: {
					product_price: '',
					product_sku_id: 0,
					line_price: '',
					stock_num: 0,
					sku_image: ''
				}
			},
			/*商品属性*/
			specData: {},
			/*产品图*/
			imagList: [],
			/*子级页面传参*/
			productModel: {},
			/*规格数组*/
			productSpecArr: [],
			/*购物车商品数量*/
			cart_total_num: 0,
			/*分享*/
			isbottmpanel: false,
			/*是否生成图片*/
			isCreatedImg: false,
			poster_img: ''
		};
	},
	onLoad(e) {
		uni.showLoading({
			title: '加载中',
			mask: true
		});
		/*商品id*/
		let scene = utils.getSceneData(e);
		this.product_id = e.product_id ? e.product_id : scene.gid;
		//#ifdef H5
		this.url = window.location.href;
		if (this.ios()) {
			this.url = uni.getStorageSync('iosFirstUrl');
		}
		//#endif
	},
	mounted() {
		/*获取产品详情*/
		this.getData();
	},
	methods: {
		/*获取数据*/
		getData() {
			let self = this;
			let product_id = self.product_id;
			self._get(
				'product.product/detail',
				{
					product_id: product_id,
					url: self.url
				},
				function(res) {
					self.cart_total_num = res.data.cart_total_num;
					/*详情内容格式化*/
					res.data.detail.content = utils.format_content(res.data.detail.content);
					self.detail = res.data.detail;
					self.imagList = res.data.detail.image;
					self.detail.show_sku = res.data.detail.product_sku;
					self.detail.show_sku.product_sku_id = res.data.detail.product_sku.spec_sku_id;
					// 商品封面图(确认弹窗)
					self.detail.show_sku.sku_image = res.data.detail.product_image;
					// 多规格商品封面图(确认弹窗)
					if (self.detail.spec_type == 20 && self.detail.product_sku['image']) {
						self.detail.show_sku.sku_image = res.data.detail.product_sku['image']['file_path'];
					}
					// 初始化商品多规格
					if (self.detail.spec_type == 20) {
						self.initSpecData(res.data.specData);
					}
					// 配置微信分享参数
					//#ifdef H5
					if (self.url != '') {
						let params = {
							product_id: self.product_id
						};
						self.configWx(res.data.share.signPackage, res.data.share.shareParams, params);
					}
					//#endif
					self.loadding = false;
					uni.hideLoading();
				}
			);
		},

		/**
		 * 多规格商品
		 */
		initSpecData(data) {
			this.productSpecArr = [];
			for (let i in data.spec_attr) {
				for (let j in data.spec_attr[i].spec_items) {
					if (j < 1) {
						data.spec_attr[i].spec_items[0].checked = true;
						this.productSpecArr[i] = data.spec_attr[i].spec_items[0].item_id;
					}
				}
			}
			this.specData = data;
		},

		/*选规格*/
		openPopup(e) {
			let self = this;
			self.isPopup = true;
			self.productModel.specData = self.specData;
			self.productModel.detail = self.detail;
			self.productModel.productSpecArr = self.productSpecArr;
			self.productModel.type = e;
		},

		/*关闭弹窗*/
		closePopup() {
			let self = this;
			self.isPopup = false;
			self.getData();
		},

		/*查看更多评价*/
		lookEvaluate(product_id) {
			uni.navigateTo({
				url: '/pages/product/detail/look-evaluate/look-evaluate?product_id=' + product_id
			});
		},
		onShareAppMessage() {
			let self = this;
			// 构建页面参数
			let params = self.getShareUrlParams({
				product_id: self.product_id
			});
			return {
				title: self.detail.product_name,
				path: '/pages/product/detail/detail?' + params
			};
		},

		/*跳转购物车*/
		gotocart() {
			uni.switchTab({
				url: '/pages/cart/cart'
			});
		},
		//关闭分享
		closeBottmpanel(data) {
			this.isbottmpanel = false;
			if (data.type == 2) {
				this.poster_img = data.poster_img;
				this.isCreatedImg = true;
			}
		},
		//分享按钮
		showShare() {
			this.isbottmpanel = true;
		},

		//关闭生成图片
		hidePopupFunc() {
			this.isCreatedImg = false;
		},
		//保存海报图片
		savePosterImg() {
			let self = this;
			uni.showLoading({
				title: '加载中',
				mask: true
			});
			// 下载海报图片
			uni.downloadFile({
				url: self.poster_img,
				success(res) {
					uni.hideLoading();
					// 图片保存到本地
					uni.saveImageToPhotosAlbum({
						filePath: res.tempFilePath,
						success(data) {
							uni.showToast({
								title: '保存成功',
								icon: 'success',
								duration: 2000
							});
							// 关闭商品海报
							self.isCreatedImg = false;
						},
						fail(err) {
							console.log(err.errMsg);
							if (err.errMsg === 'saveImageToPhotosAlbum:fail auth deny') {
								uni.showToast({
									title: '请允许访问相册后重试',
									icon: 'none',
									duration: 1000
								});
								setTimeout(() => {
									uni.openSetting();
								}, 1000);
							}
						},
						complete(res) {
							console.log('complete');
						}
					});
				}
			});
		}
	}
};
</script>

<style lang="scss">
.product-detail {
	padding-bottom: 90rpx;
}

.product-detail .product-pic,
.product-detail .product-pic .swiper,
.product-detail .product-pic image {
	width: 750rpx;
	height: 750rpx;
}

.product-detail .price-wrap {
	padding: 20rpx 20rpx 0;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.product-detail .price-wrap .left {
	display: flex;
	justify-content: flex-start;
	align-items: flex-end;
}

.product-detail .price-wrap .new-price {
	color: $dominant-color;
	font-size: 30rpx;
	font-weight: bold;
}

.product-detail .price-wrap .new-price .num {
	padding: 0 4rpx;
	font-size: 50rpx;
}

.product-detail .price-wrap .old-price {
	margin-left: 10rpx;
	font-size: 30rpx;
	color: #999999;
	text-decoration: line-through;
}

.product-detail .already-sale {
	font-size: 24rpx;
	color: #999999;
}

.product-detail .product-name {
	padding: 4rpx 20rpx 0;
	font-size: 30rpx;
	font-weight: bold;
	color: #333333;
}

.product-detail .product-describe {
	padding: 20rpx;
	line-height: 40rpx;
	font-size: 24rpx;
	color: red;
}

.product-comment,
.product-content {
	margin-top: 20rpx;
	background: #ffffff;
}

.product-content .content-box p image {
	width: 100%;
}

.product-content .content-box {
	font-size: 36rpx;
}

.btns-wrap {
	position: fixed;
	height: 90rpx;
	right: 0;
	bottom: 0;
	left: 0;
	display: flex;
	background: #ffffff;
}

.btns-wrap .icon-box {
	width: 90rpx;
	height: 90rpx;
	border-right: 1px solid #dddddd;
}

.btns-wrap .icon-box .iconfont {
	font-size: 40rpx;
	color: #888888;
}

.btns-wrap .icon-box .iconfont .num {
	position: absolute;
	width: 30rpx;
	top: 10rpx;
	left: 50%;
	height: 30rpx;
	line-height: 30rpx;
	border-radius: 15rpx;
	font-size: 20rpx;
	color: #ffffff;
	background: red;
}

.btns-wrap button,
.btns-wrap button:after {
	height: 91rpx;
	line-height: 90rpx;
	margin: 0;
	padding: 0;
	flex: 1;
	border-radius: 0;
	border: 0;
}

.btns-wrap button.add-cart {
	background: $orange-color;
}

.btns-wrap button.buy {
	background: $dominant-color;
}

.share-box {
	position: fixed;
	padding-right: 10rpx;
	width: 80rpx;
	height: 80rpx;
	right: 0;
	bottom: 180rpx;
	display: flex;
	justify-content: center;
	align-items: center;
	border-radius: 16rpx 0 0 16rpx;
	background: rgba(0, 0, 0, 0.8);
}

.share-box button {
	padding: 0;
	background: 0;
	line-height: 60rpx;
}

.share-box .iconfont {
	margin-bottom: 10rpx;
	font-size: 50rpx;
	color: #ffffff;
}

.create-img {
	width: 100%;
	padding: 20rpx;
	box-sizing: border-box;
}

.create-img image {
	width: 100%;
}

.create-img button {
	width: 100%;
}
</style>
