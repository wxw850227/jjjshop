<template>
	<view class="container" :data-theme="theme()" :class="theme() || ''">
		<view class="main">
			<view class="nav">
				<view class="header">
					<view v-if="orderType == 'takein'" class="left">
						<view class="store-name">
							<text class="fb">{{ supplier.name }}</text>
						</view>
						<view class="gray9 f24"><text
								class="icon iconfont icon-diliweizhi"></text>距离您{{ supplier.distance }}</view>
					</view>
					<view v-if="orderType == 'takeout'" class="left">
						<view class="store-name">
							<text class="fb">{{ supplier.name }}</text>
						</view>
						<view class="d-s-c f24" style="margin-right: 90rpx;" v-if="address_detail">
							<view class="text-ellipsis gray9 d-s-c">
								<text class="icon iconfont icon-dizhi"></text>
								配送地址：{{address_detail || '请选择收货地址'}}
							</view>
							<view class="theme-notice f-s-0"
								@click="gotoPage('/pages/user/address/storeaddress?shop_supplier_id=' + shop_supplier_id)">
								更换地址
							</view>
						</view>
						<view class="d-s-c f24" style="margin-right: 90rpx;" v-else
							@click="gotoPage('/pages/user/address/storeaddress?shop_supplier_id='+shop_supplier_id)">
							<view class="text-ellipsis gray9 d-s-c"><text class="icon iconfont icon-dizhi"></text>
								请选择收货地址
							</view>
						</view>
					</view>
					<view class="dinner-right">
						<template v-for="(item, index) in delivery_set" :key="'a-'+index">
							<view class="dinner_type" v-if="item == '10'" :class="{active:orderType == 'takeout'}"
								@tap="takout">
								<text>配送</text>
							</view>
						</template>
						<template v-for="(item, index) in delivery_set" :key="'b-'+index">
							<view class="dinner_type" v-if="item == '20'" :class="{active:orderType == 'takein'}"
								@tap="takein">
								<text>自取</text>
							</view>
						</template>
					</view>
				</view>
			</view>
			<view class="content">
				<scroll-view class="menus" :style="'height:' + scrollviewHigh + 'px;'"
					:scroll-into-view="menuScrollIntoView" scroll-y>
					<view class="category-wrapper">
						<template v-for="(item, index) in productList" :key="index">
							<view v-if="item.products.length != 0" class="menu d-s-c pr"
								:id="`menu-${item.category_id}`"
								:class="{ current: item.category_id === currentCateId }"
								@tap="handleMenuTap(item.category_id)">
								<image v-if="item.images" class="f-s-0 menu-imgs" :src="item.images.file_path"
									mode="aspectFill"></image>
								<view class="text-ellipsis">{{ item.name }}</view>
								<view class="menu-cartNum" v-if="item.product_num">{{item.product_num}}</view>
							</view>
						</template>
						<view class="menu-bottom"></view>
					</view>
				</scroll-view>
				<!-- goods list begin -->
				<scroll-view class="goods pr" :style="'height:' + scrollviewHigh + 'px;'" scroll-y
					:scroll-top="cateScrollTop" @scroll="handleGoodsScroll">
					<view class="goods-wrapper">
						<view class="list" :style="'padding-bottom:'+(scrollviewHigh*2 - 238)+'rpx;'">
							<!-- category begin -->
							<template v-for="(item, index) in productList" :key="index">
								<view class="category" v-if="item.products.length != 0"
									:id="`cate-${item.category_id}`">
									<view class="title">
										<text>{{ item.name }}</text>
									</view>
									<view class="goods-items">
										<!-- 商品 begin -->
										<view class="good" @click="gotoDetail(good)"
											v-for="(good, key) in item.products" :key="key">
											<view class="image-boxs">
												<view class="sallsell-out" v-if="good.product_stock<=0">
													<view class="sallsell-out-btn">当前售罄</view>
												</view>
												<image :src="good.product_image" class="image"></image>
											</view>
											<view class="product-info">
												<view class="ww100">
													<view class="name text-ellipsis">{{ good.product_name }}</view>
													<view class="tips text-ellipsis">{{ good.selling_point }}</view>
												</view>
												<view class="price_and_action">
													<view>
														<text class="f24 theme-price">￥</text>
														<text class="f34 theme-price">{{ good.product_price }}</text>
														<text class="linprice"
															v-if="good.product_price * 1 != good.line_price * 1">￥{{ good.line_price * 1 }}</text>
													</view>
													<view class="btn-group">
														<image @tap.stop="reduceFunc(good)" v-if="good.cart_num != 0"
															class="add-image"
															:src="'/static/icon/cart/reduce-'+theme()+'.png'" mode="">
														</image>
														<view class="number" v-if="good.cart_num != 0">
															{{ good.cart_num }}
														</view>
														<image @tap.stop="addCart(good)" v-if="good.product_stock > 0"
															class="add-image"
															:src="'/static/icon/cart/add-'+theme()+'.png'" mode="">
														</image>
														<image v-if="good.product_stock <= 0" class="add-image"
															src="/static/icon/cart/add-null.png" mode="">
														</image>
													</view>
												</view>
											</view>
										</view>
										<!-- 商品 end -->
									</view>
								</view>
							</template>
							<!-- category end -->
						</view>
					</view>
				</scroll-view>
				<!-- goods list end -->
			</view>
			<!-- 购物车栏 begin -->
			<view class="cart-box" @click="openCartPopup(0)">
				<view class="mark">
					<view class="icon iconfont icon-gouwudai cart-view"></view>
					<view class="tag" v-if="cart_total_num">{{ cart_total_num }}</view>
				</view>
				<view class="price" v-if="cart_total_num">
					<view>
						<text class="f22 ">￥</text>
						<text class="f36">{{ total_price }}</text>
						<text class="gray9 f22 text-d-line fn ml10">￥{{ line_price }}</text>
					</view>
					<view class="gray9 f22" v-if="total_bag_price != 0">
						<text class="">包含包装费￥</text>
						{{ total_bag_price }}
					</view>
				</view>
				<view v-else class="flex-1 f32 white">未选购商品</view>
				<button class="pay-btn" :disabled="!cart_total_num" @click.stop="toPay"
					v-if="min_money_diff <= 0 || orderType != 'takeout'">去结算</button>
				<button class="btn-gray" disabled
					v-if="min_money_diff > 0 && total_price == 0 && orderType == 'takeout'">{{ '￥' + min_money + '起送' }}</button>
				<button class=" btn-gray" disabled
					v-if="min_money_diff > 0 && total_price != 0 && orderType == 'takeout'">{{ '还差￥' + min_money_diff + '起送' }}</button>
			</view>
			<!-- 购物车栏 end -->
		</view>
		<!-- 购物车详情popup -->
		<popup-layer direction="top" :show-pop="cartPopupVisible" class="cart-popup" v-if="cart_total_num > 0"
			@closeCallBack="closeCallBack">
			<template #content>
				<view class="cart-popup pr">
					<view class="top d-b-c">
						<view class="f30 gray3 d-s-c">
							购物车
							<view class="f22 gray3" v-if="total_bag_price">（打包费 <text class="theme-price">￥{{total_bag_price}}</text>）</view>
						</view>
						<view @tap="handleCartClear" class="d-c-c"><text class="icon iconfont icon-shanchu1"></text>清空购物车</view>
					</view>
					<scroll-view class="cart-list" scroll-y>
						<view class="wrapper">
							<template v-for="(item, index) in cart_list" :key="index">
								<view class="item" v-if="item.product_num > 0">
									<view class="d-s-c ww100">
										<view class="cart-image">
											<image style="" :src="item.image.file_path" mode="aspectFill"></image>
										</view>
										<view class="left">
											<view>
												<view class="name text-ellipsis">{{ item.product.product_name }}</view>
												<view class="gray9">{{ item.describe }}</view>
											</view>
											<view class="center">
												<text>￥</text>
												<text class="f34">{{ item.price }}</text>
												<text class="f24 gray9 ml10" v-if="bag_type != 1">包装费￥{{ item.bag_price }}</text>
											</view>
										</view>
									</view>
									<view class="right">
										<image @tap="cartReduce(item)" class="btn-image" :src="'/static/icon/cart/reduce-'+theme()+'.png'" mode=""></image>
										<view class="number">{{ item.product_num }}</view>
										<image @tap="cartAdd(item)" class="btn-image" :src="'/static/icon/cart/add-'+theme()+'.png'" mode=""></image>
									</view>
								</view>
							</template>
						</view>
					</scroll-view>
				</view>
			</template>
		</popup-layer>
		<!-- 购物车详情popup -->
		<!-- 商品规格详情 -->
		<prospec v-if="isDetail" :productModel="productModel" @close="closeGoodDetailModal"></prospec>
		<view class="loading" v-if="loading">
			<image src="/static/images/loading.gif"></image>
		</view>
	</view>
</template>

<script>
	import modal from '@/components/modal/modal';
	import popupLayer from '@/components/popup-layer/popup-layer';
	import prospec from './popup/spec.vue';
	//#ifdef H5
	import jweixin from 'weixin-js-sdk';
	//#endif
	export default {
		components: {
			modal,
			popupLayer,
			prospec,
		},
		data() {
			return {
				loadingClock: false,
				isDetail: false,
				isLoading: true,
				supplier: {
					name: '',
					business_status: 0
				},
				shop_supplier_id: 0,
				loading: true,
				currentCateId: 0, //默认分类
				cateScrollTop: 0,
				menuScrollIntoView: '',
				/* 分类定位初始化 */
				sizeCalcState: false,
				productList: [],
				/* 商品详情数据 */
				productModel: {},
				// 
				clock: false,
				total_price: 0,
				/* 购物车弹窗 */
				cart_total_num: 0,
				cart_product_id: 0,
				cartPopupVisible: false,
				/* 弹窗购物车列表 */
				cart_list: [],
				cart_type: 0,
				phoneHeight: 0,
				/*可滚动视图区域高度*/
				scrollviewHigh: 0,
				/* 添加商品锁 */
				addclock: false,
				/* 收货地址 */
				address_detail: '',
				longitude: 0,
				latitude: 0,
				address_id: 0,
				/* ---- */
				/* 是否收取打包费 */
				bag_type: 1,
				total_bag_price: 0,
				// orderType 与 dinner_type 重复待删除
				orderType: '',
				/* 10配送20自提30店内40外卖 */
				dinner_type: 20,
				// 配送方式
				delivery_set: [],
				isFirst: true,
				/* 起送 */
				min_money: 0,
				min_money_diff: 0,
				line_price: 0,
				scrollLast: 0,
			};
		},
		onLoad(e) {
			let self = this;
			self.orderType = e.orderType;
			if (self.orderType == 'takeout') {
				self.dinner_type = 10;
			} else {
				self.dinner_type = 20;
			}
			self.shop_supplier_id = uni.getStorageSync('selectedId') ? uni.getStorageSync('selectedId') : 0;
		},
		onShow() {
			let self = this;
			self.isDetail = false;
			self.init();
		},
		methods: {
			stopClick() {
				return false;
			},
			/* 获取用户登录状态 */
			getUserInfo() {
				let self = this;
				self.loading = true;
				self.isLogin = false;
				self._get(
					'index/userInfo', {},
					function(res) {
						/* 已登录 */
						if (res.data && res.data.user) {
							self.isLogin = true;
						}
						if (self.dinner_type == 10 && res.data && res.data.user) {
							if (res.data.user.address && res.data.user.address_id) {
								self.latitude = res.data.user.address.latitude;
								self.longitude = res.data.user.address.longitude;
								self.address_detail = res.data.user.address.detail;
								self.getCategory(true);
							} else {
								self.getcityData();
							}
						} else {
							/* 未登录去获取当前定位 */
							self.getcityData();
						}
					}
				);
			},
			init() {
				//页面初始化
				this.addclock = false;
				this.clock = false;
				this.loadingClock = false;
				this.loading = true;
				this.isLoading = true;
				this.productList = [];
				this.sizeCalcState = false;
				this.getUserInfo();
			},
			scrollInit() {
				let self = this;
				if (self.scrollviewHigh) {
					return
				}
				uni.getSystemInfo({
					success(res) {
						self.phoneHeight = res.windowHeight;
						// 计算组件的高度
						let view = uni.createSelectorQuery().select('.nav');
						view.boundingClientRect(data => {
							let h = self.phoneHeight - data.height;
							self.scrollviewHigh = h;
						}).exec();
					}
				});
			},
			/* 获取商品类型 */
			getCategory(flag) {
				let self = this;
				this.sizeCalcState = false;
				let delivery = self.orderType == 'takeout' ? 10 : 20;
				self._get(
					'product.category/index', {
						type: 0,
						/* 0外卖，1店内 */
						shop_supplier_id: self.shop_supplier_id || 0,
						longitude: self.longitude,
						latitude: self.latitude,
						delivery: delivery,
						order_type: 0,
						table_id: 0,
						cart_type: self.cart_type || 0,
					},
					function(res) {
						self.supplier = res.data.supplier;
						self.min_money = (res.data.supplier.min_money * 1).toFixed(2);
						self.productList = res.data.list;
						if (!self.currentCateId) {
							self.currentCateId = self.productList[0].category_id;
						}
						self.delivery_set = res.data.supplier.delivery_set;
						if (self.isFirst) {
							if (self.orderType) {
								let orderType = self.orderType == 'takeout' ? '10' : '20';
								if (self.delivery_set.indexOf(orderType) == -1) {
									let content = ''
									if (orderType == '20') {
										content = '当前门店不支持自取，已为您切换为配送';
										self.orderType = 'takeout';
									} else {
										content = '当前门店不支持配送，已为您切换为自取';
										self.orderType = 'takein';
									}
									uni.showModal({
										title: '提示',
										content,
										showCancel: false
									})

								}
							}
							self.isFirst = false;
						}
						self.address_id = res.data.address_id;
						self.bag_type = res.data.supplier.bag_type;
						self.loading = false;
						self.isLoading = false;
						self.$nextTick(function() {
							self.scrollInit();
						});
						if (self.isLogin && flag) {
							self.getCart();
						}
					},
					function(err) {
						self.showError(err.msg, () => {
							self.gotoPage('/pages/index/index');
						});
					}
				);
			},
			reCart(res) {
				let self = this;
				self.loadingClock = false;
				self.cart_total_num = res.data.cartInfo.cart_total_num;
				self.line_price = res.data.cartInfo.total_line_money;
				self.total_price = res.data.cartInfo.total_pay_price;
				self.total_bag_price = res.data.cartInfo.total_bag_price;
				self.min_money_diff = res.data.cartInfo.min_money_diff;
				if (!self.cart_list || self.cart_list == '') {
					self.cartPopupVisible = false;
				}
			},
			addCart(goods) {
				let self = this;
				if (goods.spec_types == 20) {
					self.gotoDetail(goods)
					return
				}
				if (self.addclock) {
					return;
				}
				if (goods.limit_num != 0 && goods.limit_num <= goods.cart_num) {
					uni.showToast({
						icon: 'none',
						title: '超过限购数量'
					});
					return;
				}
				if (goods.product_stock <= 0 || goods.product_stock <= goods.cart_num) {
					uni.showToast({
						icon: 'none',
						title: '没有更多库存了'
					});
					return;
				}

				let params = {
					product_id: goods.product_id,
					product_num: 1,
					product_sku_id: goods.sku[0].product_sku_id,
					attr: '',
					feed: '',
					describe: '',
					price: goods.sku[0].product_price,
					bag_price: goods.sku[0].bag_price,
					shop_supplier_id: goods.supplier.shop_supplier_id,
					cart_type: 0,
					dinner_type: self.dinner_type,
					product_price: goods.sku[0].line_price,
					delivery: self.orderType == 'takeout' ? 10 : 20
				};
				self.addclock = true;
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					'order.cart/add',
					params,
					function(res) {
						uni.hideLoading();
						self.reCart(res);
						self.addclock = false;
						self.getCategory(false);

					},
					function(err) {
						uni.hideLoading()
						self.addclock = false;
					}
				);
			},
			reduceFunc(goods) {
				let self = this;
				if (self.addclock || goods.cart_num <= 0) {
					return;
				}
				if (goods.spec_types == 20) {
					self.openCartPopup(goods.product_id);
					return
				}
				let product_id = goods.product_id;
				self.addclock = true;
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					'order.cart/productSub', {
						product_id: product_id,
						type: 'down',
						cart_type: 0,
						dinner_type: self.dinner_type,
						shop_supplier_id: self.shop_supplier_id,
						delivery: self.orderType == 'takeout' ? 10 : 20
					},
					function(res) {
						uni.hideLoading();
						self.reCart(res);
						self.addclock = false;
						self.getCategory(false);
					},
					function() {
						uni.hideLoading()
						self.addclock = false;
					}
				);
			},
			getCartLoading() {
				console.log('getCartLoading')
				let self = this;
				self._get(
					'order.cart/lists', {
						shop_supplier_id: self.shop_supplier_id,
						cart_type: 0,
						delivery: self.orderType == 'takeout' ? 10 : 20,
						product_id: self.cart_product_id
					},
					function(res) {
						self.cart_list = res.data.productList;
						self.isLoading = true;
						self.reCart(res);
						uni.hideLoading()
					},
					err => {
						uni.hideLoading()
					}
				);
			},
			getCart() {
				let self = this;
				if (!self.isLogin) {
					return;
				}
				return new Promise((resolve, reject) => {
					uni.showLoading({
						title: '',
						mask: true
					})
					self._get(
						'order.cart/lists', {
							shop_supplier_id: self.shop_supplier_id,
							cart_type: 0,
							delivery: self.orderType == 'takeout' ? 10 : 20,
							product_id: self.cart_product_id
						},
						function(res) {
							uni.hideLoading()
							self.isLoading = true;
							self.reCart(res);
							self.cart_list = res.data.productList;
							resolve(true)
						},
						err => {
							uni.hideLoading()
						}
					);
				})
			},
			/* 购物车商品添加 */
			cartAdd(goods) {
				let self = this;
				if (self.addclock) {
					return;
				}
				self.addclock = true;
				let product_id = goods.product_id;
				let total_num = 1;
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					'order.cart/sub', {
						product_id: product_id,
						total_num: total_num,
						cart_id: goods.cart_id,
						type: 'up',
						cart_type: 0,
						dinner_type: self.dinner_type,
						shop_supplier_id: self.shop_supplier_id,
						delivery: self.orderType == 'takeout' ? 10 : 20
					},
					function(res) {
						// self.reCart(res);
						self.addclock = false;
						self.getCategory(false);
						self.getCartLoading();
					},
					function() {
						uni.hideLoading()
						self.addclock = false;
					}
				);
			},
			/* 购物车商品减少 */
			cartReduce(goods) {
				let self = this;
				if (self.addclock) {
					return;
				}
				self.addclock = true;
				let product_id = goods.product_id;
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					'order.cart/sub', {
						product_id: product_id,
						total_num: 1,
						cart_id: goods.cart_id,
						type: 'down',
						cart_type: 0,
						dinner_type: self.dinner_type,
						shop_supplier_id: self.shop_supplier_id,
						delivery: self.orderType == 'takeout' ? 10 : 20
					},
					function(res) {
						// self.reCart(res);
						self.addclock = false;
						self.getCategory(false);
						self.getCartLoading();
					},
					function() {
						uni.hideLoading()
						self.addclock = false;
					}
				);
			},
			takout() {
				if (this.orderType == 'takeout') return;
				this.orderType = 'takeout';
				this.dinner_type = 10;
				this.init();
			},
			takein() {
				if (this.orderType == 'takein') return;
				this.orderType = 'takein';
				this.dinner_type = 20;
				this.init();
			},
			handleMenuTap(id) {
				let self = this;
				//点击菜单项事件
				if (!self.sizeCalcState) {
					self.calcSize();
				}
				self.currentCateId = id;
				self.$nextTick(() => {
					self.cateScrollTop = self.productList.find(item => item.category_id == id).top;
					if (!self.cateScrollTop && self.cateScrollTop != 0) {
						setTimeout(function() {
							self.handleMenuTap(id)
						}, 300);
					}
				});
			},
			handleGoodsScroll({
				detail
			}) {
				let self = this;
				//商品列表滚动事件
				if (!this.sizeCalcState) {
					this.calcSize();
				}
				this.scrollLast = detail.scrollTop;
				// console.log(this.scrollLast)
				const {
					scrollTop
				} = detail;
				let tabs = this.productList.filter(item => (item.top - 5) <= scrollTop).reverse();
				if (tabs.length > 0) {
					this.currentCateId = tabs[0].category_id;
				}

			},
			calcSize() {
				let h = 0;
				this.productList.forEach(item => {
					let view = uni.createSelectorQuery().select(`#cate-${item.category_id}`);
					view.fields({
							size: true
						},
						data => {
							item.top = h;
							if (data != null) {
								h += data.height;
							}

							item.bottom = h;
						}
					).exec();
				});
				this.sizeCalcState = true;
			},
			closeGoodDetailModal(num, res) {
				//关闭饮品详情模态框
				this.isDetail = false;
				this.clock = false;
				if (num) {
					this.reCart(res);
					this.getCategory(false);
				}
			},
			async openCartPopup(n) {
				if (!this.cart_total_num) {
					return
				}
				if (!this.cartPopupVisible) {
					this.cart_list = [];
					this.cart_product_id = n;
					//打开/关闭购物车列表popup
					await this.getCart();
					this.cartPopupVisible = !this.cartPopupVisible;
				} else {
					this.cartPopupVisible = !this.cartPopupVisible;
				}
			},
			closeCallBack() {
				this.cart_product_id = 0;
				this.cart_list = [];
				this.cartPopupVisible = false;
			},
			handleCartClear() {
				//清空购物车
				let self = this;
				uni.showModal({
					title: '提示',
					content: '确定清空购物车么',
					success(res) {
						if (res.confirm) {
							self.clearCart();
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				});
			},
			clearCart() {
				let self = this;
				self._post(
					'order.cart/delete', {
						shop_supplier_id: self.shop_supplier_id,
						cart_type: 0
					},
					function(res) {
						self.cartPopupVisible = false;
						self.cart_list = [];
						self.init();
					}
				);
			},
			toPay() {
				let self = this;
				if (self.address_id == 0 && self.orderType == 'takeout') {
					uni.showModal({
						title: '提示',
						content: '您还没选择收货地址,请先选择收货地址',
						success() {
							self.gotoPage('/pages/user/address/storeaddress?shop_supplier_id=' + self
								.shop_supplier_id);
						}
					});
					return;
				}
				if (self.loadingClock) {
					return
				}
				self.loadingClock = true;
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				self._get(
					'order.cart/lists', {
						shop_supplier_id: self.shop_supplier_id,
						cart_type: 0,
						delivery: self.orderType == 'takeout' ? 10 : 20
					},
					function(res) {
						uni.hideLoading();
						self.reCart(res);
						self.cart_list = res.data.productList;
						let arrIds = [];

						self.cart_list.forEach(item => {
							arrIds.push(item.cart_id);
						});
						if (arrIds.length == 0) {
							uni.showToast({
								title: '请选择商品',
								icon: 'none'
							});
							return false;
						}
						let delivery = self.orderType == 'takeout' ? 10 : 20;
						uni.navigateTo({
							url: '/pages/order/confirm-order?order_type=cart&cart_ids=' +
								arrIds.join(',') +
								'&delivery=' +
								delivery +
								'&shop_supplier_id=' +
								self.shop_supplier_id +
								'&cart_type=0' +
								'&dinner_type=' +
								delivery
						});
						self.loadingClock = false;
					},
					() => {
						uni.hideLoading()
						self.loadingClock = false;
					}
				);
			},
			/* 新 打开商品详情弹窗 */
			gotoDetail(e) {
				let self = this;
				let delivery = this.orderType == 'takeout' ? 10 : 20;
				self.productModel = {
					product_id: e.product_id || 0,
					delivery: delivery,
					bag_type: self.bag_type || 0,
					dinner_type: self.dinner_type || 0,
					cart_type: self.cart_type || 0,
					table_id: 0,
					shop_supplier_id: self.shop_supplier_id || 0

				}
				self.isDetail = true;
			},
			/*  打开商品详情页面 暂留*/
			// gotoDetailPage() {
			// 	let self = this;
			// 	let delivery = this.orderType == 'takeout' ? 10 : 20;
			// 	let url = '/pages/product/detail/detail?product_id=' +
			// 		e.product_id +
			// 		'&delivery=' +
			// 		delivery +
			// 		'&bag_type=' +
			// 		this.bag_type +
			// 		'&dinner_type=' +
			// 		this.dinner_type +
			// 		'&cart_type=' +
			// 		this.cart_type
			// 	/* 登录后才能进入详情页 */
			// 	self._get('user.index/setting', {}, res => {
			// 		self.gotoPage(url);
			// 	})
			// },
			/*获取定位方式*/
			getcityData() {
				let self = this;
				// 第一次，如果是公众号，则初始化微信sdk配置
				// #ifdef H5
				if (self.isWeixin()) {
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					self._post(
						'index/index', {
							url: window.location.href
						},
						function(res) {
							let sign = res.data.signPackage;
							uni.hideLoading();
							self.getWxLocation(sign);
						}
					);
				} else {
					self.getLocation();
				}
				// #endif
				// #ifndef H5
				self.getLocation();
				// #endif
			},
			/*获取用户坐标*/
			getLocation(callback) {
				let self = this;
				uni.getLocation({
					type: 'wgs84',
					success(res) {
						console.log('getLocation')
						console.log(res)
						self.longitude = res.longitude;
						self.latitude = res.latitude;
						self.getCategory(true);
					},
					fail(err) {
						self.longitude = 0;
						self.latitude = 0;
						uni.showToast({
							title: '获取定位失败，请点击右下角按钮打开定位权限',
							duration: 2000,
							icon: 'none'
						});
						self.getCategory(true);
					}
				});
			},
			/* 公众号获取坐标 */
			getWxLocation(signPackage, callback) {
				// #ifdef H5
				let self = this;
				jweixin.config(JSON.parse(signPackage));
				jweixin.ready(function(res) {
					jweixin.getLocation({
						type: 'wgs84',
						success: function(res) {
							self.longitude = res.longitude;
							self.latitude = res.latitude;
							self.getCategory(true);
						},
						fail(err) {
							self.longitude = 0;
							self.latitude = 0;
							self.getCategory(true);
						}
					});
				});
				jweixin.error(function(res) {
					console.log(res);
				});
				// #endif
			},
		}
	};
</script>

<style lang="scss">
	@import './menu.scss';
</style>