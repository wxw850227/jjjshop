<template>
	<view :class="Visible?'product-popup open':'product-popup close'" @touchmove.stop.prevent="" @click="closePopup">
		<view class="popup-bg"></view>
		<view class="main" v-on:click.stop>
			<view class="header">
				<image :src="form.detail.show_sku.sku_image" mode="aspectFit" class="avt"></image>
				<view class="price">
					¥
					<text class="num">{{form.detail.show_sku.product_price}}</text>
				</view>
				<view class="stock">
					库存：{{form.detail.show_sku.stock_num}}
				</view>
				<view class="close-btn" @click="closePopup">
					<text class="icon iconfont icon-guanbi"></text>
				</view>
			</view>

			<view class="body">
				<!--选择数量-->
				<view class="level-box count_choose">
					<text class="key">数量</text>
					<view :class="form.detail.show_sku.stock_num>0?'num-wrap':'num-wrap no-stock'">
						<view class="icon-box minus d-c-c" @click="sub()">
							<text class="icon iconfont icon-jian"></text>
						</view>
						<view class="text-wrap">
							<input type="text" v-model="sum" value="" />
						</view>
						<view class="icon-box plus d-c-c" @click="add()">
							<text class="icon iconfont icon-jia"></text>
						</view>
					</view>
				</view>

				<!--规格-->
				<view v-if="form.specData !=null">
					<view class="specs mt20" v-for="(item_attr,attr_index) in form.specData.spec_attr" :key="attr_index">
						<view class="specs-hd p-20-0">
							<text class="f24 gray9">{{item_attr.group_name}}</text>
						</view>
						<view class="specs-list">
							<button type="primary" :class="item.checked ? 'btn-red' : 'btn-gray-border'" v-for="(item,item_index) in item_attr.spec_items" :key="item_index"
							 @click="selectAttr(attr_index, item_index)">{{item.spec_value}}
							</button>
						</view>
					</view>
				</view>
			</view>
			<view class="btns">
				<button type="primary" class="confirm-btn" @click="confirmFunc(form)">确认</button>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				/*是否可见*/
				Visible: false,
				sum: 1,
				form:{
					detail:{
						product_sku:{},
						show_sku:{}
					}
				}
			}

		},
		props: ['isPopup', 'productModel'],
		onLoad() {

		},
		mounted() {

		},
		watch: {
			'isPopup': function(n, o) {
				if (n != o) {
					this.Visible = n;
					this.form = this.productModel;
				}
			}
		},
		methods: {
			/*关闭弹窗*/
			closePopup() {
				this.$emit('close', {})
			},

			/*确认提交*/
			confirmFunc() {
				if (this.form.type == 'card') {
					this.addCart();
				} else {
					this.createdOrder();
				}
			},

			/*加入购物车*/
			addCart() {
				let self = this;
				let product_id = self.form.detail.product_id;
				let total_num = self.sum;
				let product_sku_id = self.form.detail.show_sku.product_sku_id;
				if (self.form.detail.spec_type == 20 && product_sku_id == 0) {
					uni.showToast({
						title: '请选择属性',
						icon: 'none',
						duration: 2000
					});
					return false;
				}

				self._post('order.cart/add', {
					product_id: product_id,
					total_num: total_num,
					product_sku_id: product_sku_id,
				}, function(res) {
					uni.showToast({
						title: res.msg,
						duration: 2000
					});
					self.closePopup();
				});
			},
			/*选择属性*/
			selectAttr(attr_index, item_index) {
				let self = this;
				let specData = self.form.specData;
				for (let i in specData.spec_attr) {
					for (let j in specData.spec_attr[i].spec_items) {
						if (attr_index == i) {
							specData.spec_attr[i].spec_items[j].checked = false;
							if (item_index == j) {
								specData.spec_attr[i].spec_items[item_index].checked = true;
								self.form.productSpecArr[i] = specData.spec_attr[i].spec_items[item_index].item_id;
							}
						}
					}
				}
				self.specData = specData;
				// 更新商品规格信息
				self.updateSpecProduct();
			},

			/**
			 * 更新商品规格信息
			 */
			updateSpecProduct() {
				let self = this;
				let specSkuId = self.form.productSpecArr.join('_');
				// 查找skuItem
				let spec_list = self.form.specData.spec_list,
					skuItem = spec_list.find((val) => {
						return val.spec_sku_id == specSkuId;
					});
				// 记录product_sku_id
				// 更新商品价格、划线价、库存
				if (typeof skuItem === 'object') {
					self.form.detail.show_sku.product_sku_id = skuItem.spec_sku_id;
					self.form.detail.show_sku.product_price = skuItem.spec_form.product_price;
					self.form.detail.show_sku.line_price = skuItem.spec_form.line_price;
					self.form.detail.show_sku.stock_num = skuItem.spec_form.stock_num;
					self.form.detail.show_sku.sku_image = skuItem.spec_form.image_id > 0 ? skuItem.spec_form.image_path : self.form.detail.product_image;
				}
			},


			/*创建订单*/
			createdOrder() {
				let self = this;
				let product_id = self.form.detail.product_id;
				let product_num = self.sum;
				let product_sku_id = self.form.detail.show_sku.product_sku_id;
				uni.navigateTo({
					url: '/pages/order/confirm-order/confirm-order?product_id=' + product_id + '&product_num=' + product_num +
						'&product_sku_id=' + product_sku_id + '&order_type=buy'
				});

			},
			/*商品增加*/
			add() {
				if (this.form.detail.product_sku.stock_num <= 0) {
					return;
				}
				let a = this.sum;
				if (a >= this.form.detail.product_sku.stock_num) {
					uni.showToast({
						title: '数量超过了库存',
						icon: 'none',
						duration: 2000
					});
					return false;
				}
				a++;
				this.sum = a;
			},
			/*商品减少*/
			sub() {
				if (this.form.detail.product_sku.stock_num <= 0) {
					return;
				}
				let a = this.sum;
				if (a < 2) {
					uni.showToast({
						title: '商品数量至少为1',
						icon: 'none',
						duration: 2000
					});
					return false;
				}
				a--;
				this.sum = a;
			}

		}
	}
</script>

<style lang="scss">
	.product-popup {}

	.product-popup .popup-bg {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0, 0, 0, .6);
		z-index: 99;
	}

	.product-popup .main {
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

	.product-popup.open .main {
		transform: translate3d(0, 0, 0);
	}

	.product-popup.close .popup-bg {
		display: none;
	}

	.product-popup .header {
		height: 120rpx;
		padding: 10rpx 0 10rpx 250rpx;
		position: relative;
		border-bottom: 1px solid #EEEEEE;
	}

	.product-popup .header .avt {
		position: absolute;
		top: -80rpx;
		left: 30rpx;
		width: 200rpx;
		height: 200rpx;
		border: 2px solid #FFFFFF;
		background: #FFFFFF;
	}

	.product-popup .header .stock {
		font-size: 24rpx;
		color: #999999;
	}

	.product-popup .close-btn {
		position: absolute;
		width: 40rpx;
		height: 40rpx;
		top: 10rpx;
		right: 10rpx;
	}

	.product-popup .price {
		color: $dominant-color;
		font-size: 30rpx;
	}

	.product-popup .price .num {
		padding: 0 4rpx;
		font-size: 50rpx;
	}

	.product-popup .old-price {
		margin-left: 10rpx;
		font-size: 30rpx;
		color: #999999;
		text-decoration: line-through;
	}

	.product-popup .body {
		padding: 20rpx 30rpx;
		max-height: 600rpx;
		overflow-y: auto;
	}

	.product-popup .level-box {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.product-popup .level-box .key {
		font-size: 24rpx;
		color: #999999;
	}

	.product-popup .level-box .num-wrap {
		display: flex;
		justify-content: flex-end;
		align-items: center;
	}

	.product-popup .level-box .icon-box {
		width: 60rpx;
		height: 60rpx;
		border: 1px solid #DDDDDD;
		background: #f7f7f7;
	}

	.product-popup .num-wrap .iconfont {
		color: #666;
	}

	.product-popup .num-wrap.no-stock .iconfont {
		color: #CCCCCC;
	}

	.product-popup .level-box .text-wrap {
		margin: 0 4rpx;
		height: 60rpx;
		border: 1px solid #DDDDDD;
		background: #f7f7f7;
	}

	.product-popup .level-box .text-wrap input {
		padding: 0 10rpx;
		height: 60rpx;
		line-height: 60rpx;
		width: 80rpx;
		text-align: center;
	}

	.specs .specs-list {
		display: flex;
		justify-content: flex-start;
		align-items: center;
		flex-wrap: wrap;
	}

	.specs .specs-list button {
		margin-right: 10rpx;
		margin-bottom: 10rpx;
		font-size: 24rpx;
	}
	
	.specs .specs-list button:after,
	.product-popup .btns button,
	.product-popup .btns button:after {
		border: 0;
		border-radius: 0;
	}

	.product-popup .btns .confirm-btn {
		height: 90rpx;
		line-height: 90rpx;
		background: $dominant-color;
	}
</style>
