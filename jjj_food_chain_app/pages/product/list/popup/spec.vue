<template>
	<view class="pop-spec" v-if="!loading">
		<view class="pop-bg" @click.stop="closeFunc(false)"></view>
		<view class="good-detail-modal" :data-theme="theme()" :class="theme() || ''">
			<view class="icon iconfont icon-guanbi1" @click.stop="closeFunc(false)"></view>
			<!-- 商品详情模态框 begin -->
			<view class="product-images">
				<image v-if="detail.product_image" :src="detail.product_image" class="product-image" mode="aspectFill">
				</image>
			</view>
			<view class="product-name f-s-0">
				<view class="text-ellipsis">{{ detail.product_name || '' }}</view>
			</view>
			<view class="good_basic">
				<view class="f22 gray9">已售{{ detail.product_sales || '' }}</view>
				<view class="gray6 sell-point" style="line-height: 1.5;" v-if="detail.selling_point ">
					{{ detail.selling_point || '' }}
				</view>
			</view>
			<view class="product-specs">
				<view class="property" v-if="detail.spec_type == 20">
					<view class="title"><text class="name">规格</text></view>
					<view class="values">
						<view class="value" @click.stop="selecedtSpec(value, key)" v-for="(value, key) in detail.sku"
							:key="key" :class="{ default: value.checked }">
							{{ value.spec_name }}
						</view>
					</view>
				</view>
				<view class="property" v-if="detail.product_attr.length > 0"
					v-for="(item, index) in detail.product_attr" :key="index">
					<view class="title">
						<text class="name">{{ item.attribute_name || '' }}</text>
					</view>
					<view class="values">
						<template v-for="(value, key) in item.attribute_value">
							<view class="value" @click.stop="selectAttr(value, key, index)" v-if="value != ''"
								:key="key" :class="{ default: form.show_sku.attr[index] == key }">
								{{ value }}
							</view>
						</template>

					</view>
				</view>
				<view class="property" v-if="detail.product_feed.length > 0">
					<view class="title"><text class="name">加料</text></view>
					<view class="values">
						<view class="value" @click.stop="selectFeed(item, index)"
							v-for="(item, index) in detail.product_feed" :key="index"
							:class="{ default: form.show_sku.feed[index] != null }">
							+{{ item.feed_name }}
						</view>
					</view>
				</view>
			</view>
			<!--详情内容-->
			<view class="product-content">
				<view class="border-b-e">
					<view class="group-hd d-s-c"><text class="min-name pr f30 fb">详情</text></view>
				</view>
				<view class="content-box">
					<view class="" v-html="detail.content"></view>
				</view>
			</view>
			<view class="spec-bottom">
				<view class="action">
					<view class="d-c d-a-s">
						<view class="d-s-c">
							<text class="f42 theme-price">￥</text>
							<text class="f48 mr16 theme-price">{{ price || 0 }}</text>
							<text class="f22 gray9 fb text-l-t mr8">￥{{ lineprice || 0}}</text>
							<text class="f22 gray9" v-if="bag_type != 1">打包费￥{{ bag_price()|| 0 }}</text>
						</view>
						<view class="f22 gray3">{{ describe() || ''}}</view>
					</view>
					<view class="btn-group">
						<image @click.stop="sub()" class="add-image" :src="'/static/icon/cart/reduce-'+theme()+'.png'">
						</image>
						<view class="number">{{ form.show_sku.sum }}</view>
						<image v-if="stock > 0" @click.stop="add()" class="add-image"
							:src="'/static/icon/cart/add-'+theme()+'.png'" mode=""></image>
						<image v-else @click.stop="add()" class="add-image" src="/static/icon/cart/add-null.png"
							mode="">
						</image>
					</view>
				</view>
				<view>
					<view class="addCart-btn" @tap="confirmFunc">
						<view>加入购物车</view>
					</view>
				</view>
			</view>
			<!-- 商品详情模态框 end -->
		</view>
	</view>

</template>

<script>
	import utils from '@/common/utils.js';
	export default {
		components: {},
		data() {
			return {
				detail: {
					product_attr: [],
					product_feed: [],
					sku: []
				},
				/*是否可见*/
				Visible: false,
				/*表单对象*/
				form: {
					attr: [],
					product_sku_id: [],
					feed: [],
					detail: {

					},
					show_sku: {
						sku_image: '',
						bag_price: ''
					},
					shop_supplier_id: 0
				},
				/*当前商品总库存*/
				stock: 0,
				/*选择提示*/
				selectSpec: '',
				/*是否打开过多规格选择框*/
				isOpenSpec: false,
				type: '',
				product_price: '',
				feed_price: 0,
				space_name: '',
				attr_name: [],
				feed_name: [],
				product_lineprice: '',
				delivery: '',
				bag_type: 1,
				dinner_type: 20,
				cart_type: 0,
				table_id: 0,
				clock: false,
				discount_price: 0,
				product_id: '',
				isContentVideoPlay: false,
				isMPH5: false,
				loading: true
			}
		},
		computed: {
			price: function() {
				if (this.discount_price) {
					return ((this.discount_price * 1 + this.product_price * 1) * this.form.show_sku.sum).toFixed(2);
				} else {
					return ((this.feed_price * 1 + this.product_price * 1) * this.form.show_sku.sum).toFixed(2);
				}
			},
			lineprice: function() {
				return ((this.feed_price * 1 + this.product_lineprice * 1) * this.form.show_sku.sum).toFixed(2);
			},
			/*判断增加数量*/
			isadd: function() {
				return this.form.show_sku.sum >= this.stock || this.form.show_sku.sum >= this.form.detail.limit_num;
			},

			/*判断减少数量*/
			issub: function() {
				return this.form.show_sku.sum <= 1;
			}
		},
		props: ['productModel'],
		created(e) {
			let form = this.productModel;
			this.product_id = form.product_id;
			this.delivery = form.delivery;
			this.bag_type = form.bag_type;
			this.dinner_type = form.dinner_type;
			this.cart_type = form.cart_type;
			this.table_id = form.table_id;
			this.shop_supplier_id = form.shop_supplier_id;
			this.clock = false;
			this.getData();
			//#ifdef H5
			if (this.isWeixin()) {
				this.isMPH5 = true;
			}
			//#endif
		},
		methods: {
			closeFunc(e) {
				this.$emit('close')
			},
			openVideo(name) {
				if (name == 'video') {
					this.isVideoPlay = true;
					this.isContentVideoPlay = false;
				} else {
					this.isVideoPlay = false;
					this.isContentVideoPlay = true;
				}
			},
			bag_price: function() {
				let price = this.form.show_sku.bag_price * this.form.show_sku.sum
				return price.toFixed(2) || 0;
			},
			goback() {
				uni.navigateBack({
					success: () => {
						delta: 1 //返回的页面数，如果 delta 大于现有页面数，则返回到首页。默认为1
					},

					//失败回调直接返回首页
					fail: () => {
						uni.switchTab({
							url: '/pages/index/index' //路径为测试数据，填写小程序真实路径就行
						})
					}
				})
			},
			getData() {
				let self = this;
				uni.showLoading({
					title: '加载中...',
					mask: true
				})
				self._get('product.product/detail', {
					product_id: self.product_id,
					table_id: self.table_id || 0,
					shop_supplier_id: self.shop_supplier_id || 0
				}, (res) => {
					uni.hideLoading()
					if (!res.data.detail) {
						self.showError('商品已下架', () => {
							self.closeFunc(false)
						})
						return
					}
					/*详情内容格式化*/
					res.data.detail.content = utils.format_content(res.data.detail.content);
					self.detail = res.data.detail;
					self.loading = false;
					this.showGoodDetailModal();
				})
			},
			showGoodDetailModal() {
				let self = this;
				this.detail.sku.forEach((item, index) => {
					item.checked = false;
				})
				let obj = {
					specData: this.detail.sku,
					detail: this.detail,
					shop_supplier_id: this.detail.shop_supplier_id,
					productSpecArr: this.specData != null ? new Array(this.specData.spec_attr.length) : [],
					show_sku: {
						sku_image: '',
						seckill_price: 0,
						attr: [],
						product_sku_id: [],
						feed: [],
						line_price: 0,
						seckill_stock: 0,
						seckill_product_sku_id: 0,
						sum: 1
					}
				};
				self.form = obj;
				self.space_name = '';
				self.attr_name = [];
				self.feed_name = [];
				self.isOpenSpec = true;
				self.initShowSku();
				if (self.form.detail.sku[0].checked == false) {
					self.selecedtSpec(self.form.detail.sku[0], 0)
				}
				if (self.form.detail.product_attr == '') {
					return
				}
				self.form.detail.product_attr.forEach((item, index) => {
					if (!self.form.show_sku.attr[index]) {
						self.selectAttr(item.attribute_value[0], 0, index)
					}
				})
			},
			describe: function() {
				let space_name = this.space_name;
				if (space_name != '') {
					space_name += ';'
				}
				let attr_name = this.attr_name.join(';');
				if (attr_name != '') {
					attr_name += ';'
				}
				let feed_name = this.feed_name.join(',');
				if (feed_name != '') {
					feed_name += ';'
				}

				return (space_name + attr_name + feed_name) || ''
			},
			/*初始化*/
			initShowSku() {
				this.form.show_sku.sku_image = this.form.detail.product_image;
				this.form.show_sku.product_price = this.form.detail.product_price;
				this.form.show_sku.bag_price = this.form.detail.bag_price;
				this.form.show_sku.product_sku_id = [];
				this.form.show_sku.attr = [];
				this.form.show_sku.feed = [];
				this.form.show_sku.feed.length = this.form.detail.product_feed.length;
				this.form.show_sku.line_price = this.form.detail.line_price;
				this.form.show_sku.stock_num = this.form.detail.product_stock;
				this.form.show_sku.sum = 1;
				this.stock = this.form.detail.product_stock;
			},
			/*选择属性*/
			selecedtSpec(item, index) {
				let self = this;
				if (item.checked) {
					item.checked = false;
					self.form.show_sku.product_sku_id[0] = null;
				} else {
					self.form.detail.sku.forEach((sitem, sindex) => {
						sitem.checked = false;
					})
					item.checked = true;
					self.form.show_sku.product_sku_id[0] = item.product_sku_id;
					self.space_name = item.spec_name;
					self.$set(self.form.show_sku, 'product_price', item.product_price)
					self.$set(self.form.show_sku, 'bag_price', item.bag_price);
					self.$set(self.form.show_sku, 'line_price', item.line_price)
					self.$set(self.form.show_sku, 'stock_num', item.stock_num)
				}
				if (self.form.show_sku.product_sku_id[0] == null) {
					self.initShowSku();
					return;
				}
				// 更新商品规格信息
				self.updateSpecProduct();
			},
			/*选择属性*/
			selectAttr(item, index, aindex) {
				let self = this;
				self.$set(self.form.show_sku.attr, aindex, index);
				self.attr_name[aindex] = item;
				// 更新商品规格信息
				self.updateSpecProduct();
			},
			/*选择加料*/
			selectFeed(item, index) {
				let self = this;
				if (self.form.show_sku.feed[index] || self.form.show_sku.feed[index] === 0) {
					self.$set(self.form.show_sku.feed, index, null)
					self.feed_price -= item.price * 1;
					if (item.discount_price) {
						self.discount_price -= item.discount_price
					} else {
						self.discount_price -= 0
					}
					let n = self.feed_name.indexOf(item.feed_name);
					if (n > -1) {
						self.feed_name.splice(n, 1)
					}
				} else {
					self.$set(self.form.show_sku.feed, index, index)
					self.feed_price += item.price * 1;
					if (item.discount_price) {
						self.discount_price += item.discount_price
					} else {
						self.discount_price += 0
					}
					self.feed_name.push(item.feed_name)

				}
				// 更新商品规格信息
				self.updateSpecProduct();
			},
			updateSpecProduct() {
				this.product_price = this.form.show_sku.product_price;
				this.product_lineprice = this.form.show_sku.line_price;
			},
			/*商品增加*/
			add() {
				if (this.stock <= 0) {
					uni.showToast({
						title: '商品库存不足',
						icon: 'none',
						duration: 2000
					});
					return;
				}
				this.form.show_sku.sum++;
			},
			/*商品减少*/
			sub() {
				if (this.stock <= 0) {
					return;
				}
				if (this.form.show_sku.sum < 2) {
					uni.showToast({
						title: '商品数量至少为1',
						icon: 'none',
						duration: 2000
					});
					return false;
				}
				this.form.show_sku.sum--;
			},
			/*确认提交*/
			confirmFunc() {
				console.log(this.clock)
				if (this.clock) {
					return
				}
				if (this.form.show_sku.product_sku_id[0] == null && this.form.detail.spec_type == 20) {
					uni.showToast({
						title: '请选择规格',
						icon: 'none',
						duration: 2000
					});
					return;
				}
				if (this.form.detail.product_attr != null) {
					for (let i = 0; i < this.form.detail.product_attr.length; i++) {
						if (this.form.show_sku.attr[i] == null) {
							uni.showToast({
								title: '请选择属性',
								icon: 'none',
								duration: 2000
							});
							return;
						}
					}
				}
				if (this.form.show_sku.sum > this.form.show_sku.stock_num) {
					uni.showToast({
						title: '商品库存不足',
						icon: 'none',
						duration: 2000
					});
					return;
				}
				this.addCart();
			},
			/*加入购物车*/
			addCart() {
				let self = this;
				let feed = [];
				self.form.show_sku.feed.forEach((item, index) => {
					if (item != null) {
						feed.push(item)
					}
				})
				if (feed.length <= 0) {
					feed = ''
				} else {
					feed = feed.join(',')
				}
				let price = '';
				if (self.discount_price) {
					price = self.discount_price * 1 + self.product_price * 1;
				} else {
					price = self.feed_price * 1 + self.product_price * 1;
				}
				let params = {
					product_id: self.form.detail.product_id,
					product_num: self.form.show_sku.sum,
					product_sku_id: self.form.show_sku.product_sku_id[0],
					attr: self.form.show_sku.attr.join(','),
					feed: feed,
					describe: self.describe(),
					price: price,
					product_price: self.feed_price * 1 + self.form.show_sku.line_price * 1,
					bag_price: self.form.show_sku.bag_price,
					shop_supplier_id: self.form.shop_supplier_id,
					cart_type: self.cart_type,
					dinner_type: self.dinner_type,
					delivery: self.delivery,
					table_id: self.table_id || 0
				}
				let url = 'order.cart/add';
				self.clock = true;
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(url, params, function(res) {
					self.clock = false;
					uni.hideLoading()
					self.$emit('close', self.form.show_sku.sum, res)
				}, (err) => {
					self.clock = false;
				});
			},
		}
	}
</script>

<style lang="scss">
	@import './spec.scss';
</style>