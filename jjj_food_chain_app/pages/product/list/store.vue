<template>
	<view class="container" :data-theme="theme()" :class="theme() || ''">
		<view class="main">
			<view class="nav">
				<view class="header" :class="{tableHead:table_id != 0 && table_detail}">
					<view class="left">
						<view class="store-name">
							<text class="fb">{{ supplier.name }}</text>
						</view>
					</view>
					<view class="dinner-right" v-if="table_id == 0">
						<template v-for="(item, index) in store_set" :key="item">
							<view class="dinner_type" v-if="item == '30'" :class="{active:orderType == 'takeout'}"
								@tap="takout">
								<text>打包</text>
							</view>
						</template>
						<template v-for="(item, index) in store_set" :key="item">
							<view class="dinner_type" v-if="item == '40'" :class="{active:orderType == 'takein'}"
								@tap="takein">
								<text>店内</text>
							</view>
						</template>
					</view>
				</view>
			</view>
			<view class="content">
				<scroll-view class="menus" :style="'height:' + scrollviewHigh + 'px;'"
					:scroll-into-view="menuScrollIntoView" :scroll-with-animation="true" :scroll-animation-duration="1"
					scroll-y>
					<view class="category-wrapper">
						<template v-for="(item, index) in productList" :key="index">
							<view class="menu d-s-c" :id="`menu-${item.category_id}`"
								:class="{ current: item.category_id === currentCateId }"
								v-if="item.products.length != 0" @tap="handleMenuTap(item.category_id)">
								<image v-if="item.images" :src="item.images.file_path" mode="aspectFill"
									class="f-s-0 menu-imgs"></image>
								<text>{{ item.name }}</text>
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
													<view class="name text-ellipsis">
														{{ good.product_name }}
													</view>
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
			<!-- content end -->
			<!-- 购物车栏 begin -->
			<view class="cart-box" @click.stop="openCartPopup(0)">
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
				</view>
				<view v-else class="flex-1 f32 white">未选购商品</view>
				<template v-if="table_id != 0">
					<button class="pay-btn" @click.stop="toPay"><text>下单</text></button>
				</template>
				<template v-else>
					<button v-if="addorder_id == 0" class="pay-btn" @click.stop="toPay"><text>去结算</text></button>
				</template>
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
							<view class="f22 gray3" v-if="total_bag_price">（打包费 <text
									class="theme-price">￥{{total_bag_price}}</text>）</view>
						</view>
						<view @tap="handleCartClear" class="d-c-c"><text
								class="icon iconfont icon-shanchu1"></text>清空购物车</view>
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
												<text class="f24 gray9 ml10"
													v-if="bag_type != 1">包装费￥{{ item.bag_price }}</text>
											</view>
										</view>
									</view>
									<view class="right">
										<image @tap="cartReduce(item)" class="btn-image"
											:src="'/static/icon/cart/reduce-'+theme()+'.png'" mode=""></image>
										<view class="number">{{ item.product_num }}</view>
										<image @tap="cartAdd(item)" class="btn-image"
											:src="'/static/icon/cart/add-'+theme()+'.png'" mode=""></image>
									</view>
								</view>
							</template>
						</view>
					</scroll-view>
				</view>
			</template>
		</popup-layer>
		<!-- 购物车详情popup -->
		<prospec v-if="isDetail" :productModel="productModel" @close="closeGoodDetailModal"></prospec>
		<view class="loading" v-if="loading">
			<image src="/static/images/loading.gif"></image>
		</view>
	</view>
</template>

<script>
	import utils from '@/common/utils.js';
	import prospec from './popup/spec.vue';
	import popupLayer from '@/components/popup-layer/popup-layer';
	export default {
		components: {
			popupLayer,
			prospec,
		},
		data() {
			return {
				loadingClock: false,
				isSearch: false,
				isLogin: false,
				isDetail: false,
				isLoading: true,
				goods: [], //所有商品
				supplier: {
					name: '',
					business_status: 0
				},
				loading: true,
				currentCateId: 0, //默认分类
				cateScrollTop: 0,
				menuScrollIntoView: '',
				cart: [], //购物车
				// good: {}, //当前饮品
				category: {}, //当前饮品所在分类
				cartPopupVisible: false,
				sizeCalcState: false,
				listData: [],
				productList: [],
				productModel: {},
				clock: false,
				cart_total_num: 0,
				cart_product_id: 0,
				total_price: 0,
				total_bag_price: 0,
				cart_list: [],
				orderType: 'takein',
				takeout_address: {},
				phoneHeight: 0,
				/*可滚动视图区域高度*/
				scrollviewHigh: 0,
				delivery_time: ['00:00', '00:00'],
				store_time: ['00:00', '00:00'],
				officeTime: {
					now: 0,
					delivery_start: 0,
					delivery_end: 0,
					store_start: 0,
					store_end: 0
				},
				addclock: false,
				longitude: 0,
				latitude: 0,
				bag_type: 1,
				shop_supplier_id: 0,
				/* 10配送20自提30店内 */
				dinner_type: 30,
				cart_type: 1,
				table_id: 0,
				order_id: 0,
				addorder_id: 0,
				discountInfo: [],
				reduce: {},
				reduce_diff_value: 0,
				line_price: 0,
				isFirst: true,
				store_set: [],
				num: 1,
				table_detail: null,
				options: {},
				settle_type: 0,
				status: '',
				meal_num: '',
				isShopDetail: false,
				supplierDetail: null,
			};
		},
		onLoad(e) {
			let self = this;
			let scene = utils.getSceneData(e);
			self.options = e;
			self.shop_supplier_id = e.shop_supplier_id ? e.shop_supplier_id : scene.sid;
			self.table_id = e.table_id ? e.table_id : scene.tid;
			if (!self.table_id) {
				self.table_id = 0;
			}
			if (self.table_id == 0) {
				self.shop_supplier_id = uni.getStorageSync('selectedId') ? uni.getStorageSync('selectedId') : 0;
			}
			self.num = e.num || 0;
			self.meal_num = e.meal_num || 0;
			self.status = e.status;
			self.addorder_id = e.order_id || 0;
			uni.setNavigationBarTitle({
				title: self.table_id == 0 ? '快餐模式' : '堂食点餐'
			});
		},
		onShow() {
			let self = this;
			self.isDetail = false;
			self.getUserInfo();
		},
		computed: {
			menuCartNum() {
				return id =>
					this.cart.reduce((acc, cur) => {
						if (cur.cate_id === id) {
							return (acc += cur.number);
						}
						return acc;
					}, 0);
			}
		},
		methods: {
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
						self.init();
					}
				);
			},
			init() {
				//页面初始化
				this.addclock = false;
				this.category = {};
				this.clock = false;
				this.loading = true;
				this.isLoading = true;
				this.productList = [];
				this.sizeCalcState = false;
				this.getCategory(true);
			},
			goBack() {
				if (this.table_id > 0 && this.status == 1) {
					return
				}
				// #ifndef H5
				uni.navigateBack({
					delta: 1
				});
				// #endif
				// #ifdef H5
				history.go(-1);
				// #endif
			},
			scrollInit() {
				let self = this;
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
			/* 获取商品类型 */
			getCategory(flag) {
				let self = this;
				this.sizeCalcState = false;
				self._get(
					'product.category/index', {
						/* 0外卖分类，1堂食分类 */
						type: 1,
						shop_supplier_id: self.shop_supplier_id,
						longitude: 0,
						latitude: 0,
						table_id: self.table_id,
						/* 30 打包 40店内 */
						delivery: self.orderType == 'takeout' ? 30 : 40,
						order_type: self.table_id == 0 ? 1 : 2,
						cart_type: self.cart_type || 1,
					},
					function(res) {
						self.supplier = res.data.supplier;
						self.discountInfo = res.data.discountInfo;
						self.productList = res.data.list;
						if (!self.currentCateId) {
							self.currentCateId = self.productList[0].category_id;
						}
						self.store_set = res.data.supplier.store_set;
						if (self.table_id != 0) {
							self.orderType = 'takein';
						}
						self.shop_supplier_id = res.data.supplier.shop_supplier_id;
						self.bag_type = res.data.supplier.storebag_type;
						if (self.orderType == 'takein') {
							self.bag_type = 1;
						}
						self.loading = false;
						self.isLoading = false;
						self.$nextTick(function() {
							self.scrollInit();
						});
						if (self.isLogin && flag) {
							self.getCart();
						}
					},
					function() {
						self.gotoPage('/pages/index/index');
					}
				);
			},
			reCart(res) {
				let self = this;
				self.loadingClock = false;
				self.cart_total_num = res.data.cartInfo.cart_total_num;
				self.total_price = res.data.cartInfo.total_pay_price;
				self.line_price = res.data.cartInfo.total_line_money;
				self.total_bag_price = res.data.cartInfo.total_bag_price;
				self.reduce = res.data.cartInfo.reduce;
				self.reduce_diff_value = res.data.cartInfo.reduce_diff_value;
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
					shop_supplier_id: self.shop_supplier_id,
					table_id: self.table_id,
					cart_type: 1,
					dinner_type: self.dinner_type,
					product_price: goods.sku[0].line_price,
					delivery: self.orderType == 'takeout' ? 30 : 40
				};
				self.addclock = true;
				let url = 'order.cart/add';
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					url,
					params,
					function(res) {
						self.reCart(res);
						uni.hideLoading()
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
				let url = 'order.cart/productSub';
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					url, {
						product_id: product_id,
						type: 'down',
						cart_type: 1,
						shop_supplier_id: self.shop_supplier_id,
						table_id: self.table_id,
						dinner_type: self.dinner_type,
						delivery: self.orderType == 'takeout' ? 30 : 40
					},
					function(res) {
						uni.hideLoading()
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
				let self = this;
				let url = 'order.cart/lists';
				let params = {
					shop_supplier_id: self.shop_supplier_id,
					cart_type: 1,
					delivery: self.orderType == 'takeout' ? 30 : 40,
					product_id: self.cart_product_id,
					table_id: self.table_id || 0
				}
				let callBack = function(res) {
					uni.hideLoading();
					self.isLoading = true;
					self.reCart(res);
					self.cart_list = res.data.productList;
				}
				self._get(
					url, params,
					function(res) {
						callBack(res)
					},
					err => {
						uni.hideLoading();
					}
				);
			},
			getCart() {
				let self = this;
				if (!self.isLogin) {
					return;
				}
				return new Promise((resolve, reject) => {
					let url = 'order.cart/lists';
					let params = {
						shop_supplier_id: self.shop_supplier_id,
						cart_type: 1,
						delivery: self.orderType == 'takeout' ? 30 : 40,
						product_id: self.cart_product_id,
						table_id: self.table_id || 0
					}
					let callBack = function(res) {
						uni.hideLoading();
						self.isLoading = true;
						self.reCart(res);
						self.cart_list = res.data.productList;
					}
					uni.showLoading({
						title: '',
						mask: true
					})
					self._get(
						url, params,
						function(res) {
							callBack(res)
							resolve(true)
						},
						err => {
							uni.hideLoading();
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
				let url = 'order.cart/sub';
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					url, {
						product_id: product_id,
						total_num: total_num,
						cart_id: goods.cart_id,
						type: 'up',
						cart_type: 1,
						delivery: self.orderType == 'takeout' ? 30 : 40,
						dinner_type: self.dinner_type,
						shop_supplier_id: self.shop_supplier_id,
						table_id: self.table_id,
					},
					function(res) {
						// uni.hideLoading()
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
				let url = 'order.cart/sub';
				uni.showLoading({
					title: '',
					mask: true
				})
				self._post(
					url, {
						product_id: product_id,
						total_num: 1,
						cart_id: goods.cart_id,
						type: 'down',
						cart_type: 1,
						dinner_type: self.dinner_type,
						shop_supplier_id: self.shop_supplier_id,
						table_id: self.table_id,
						delivery: self.orderType == 'takeout' ? 30 : 40
					},
					function(res) {
						// self.reCart(res);
						// uni.hideLoading()
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
			//清空购物车
			handleCartClear() {
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
			/*  清空 */
			clearCart() {
				let self = this;
				let url = 'order.cart/delete';
				let params = {
					shop_supplier_id: self.shop_supplier_id,
					cart_type: 1,
					table_id: self.table_id || 0
				};
				self._post(url, params, function(res) {
					self.cartPopupVisible = false;
					self.cart_list = [];
					self.init();
				});
			},
			/* 提交 */
			toPay() {
				let self = this;
				self.fastToPay();
			},
			/* 快餐结算 */
			fastToPay() {
				let self = this;
				self.loadingClock = true;
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				let delivery = self.orderType == 'takeout' ? 30 : 40;
				self._get(
					'order.cart/lists', {
						shop_supplier_id: self.shop_supplier_id,
						cart_type: 1,
						delivery: delivery,
						table_id: self.table_id || 0
					},
					function(res) {
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
						uni.hideLoading();
						uni.navigateTo({
							url: '/pages/order/confirm-order?order_type=cart&cart_ids=' +
								arrIds.join(',') +
								'&delivery=' +
								delivery +
								'&shop_supplier_id=' +
								self.shop_supplier_id +
								'&cart_type=1' +
								'&dinner_type=30&table_id=' +
								self.table_id
						});
					}, err => {
						self.loadingClock = false;
					}
				);
			},
			/* 加餐 */
			addpay() {
				let self = this;
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				self.addpayFunc();
			},
			/* 快餐模式加餐 */
			addpayFunc() {
				let self = this;
				self._get(
					'order.cart/lists', {
						shop_supplier_id: self.shop_supplier_id,
						cart_type: 1,
						delivery: self.orderType == 'takeout' ? 30 : 40,
						table_id: self.table_id || 0
					},
					function(res) {
						self.cart_total_num = res.data.cartInfo.cart_total_num;
						self.total_price = res.data.cartInfo.total_price;
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
						uni.hideLoading();
						let url =
							'/pages/order/addorder?order_type=cart&cart_ids=' +
							arrIds.join(',') +
							'&delivery=40&shop_supplier_id=' +
							self.shop_supplier_id +
							'&cart_type=1' +
							'&dinner_type=30&table_id=' +
							self.table_id +
							'&order_id=' +
							self.addorder_id;
						self.gotoPage(url);
					}
				);
			},
			/* 新 打开商品详情弹窗 */
			gotoDetail(e) {
				let self = this;
				let delivery = self.orderType == 'takeout' ? 30 : 40;
				self.productModel = {
					product_id: e.product_id || 0,
					delivery: delivery,
					bag_type: self.bag_type || 0,
					dinner_type: self.dinner_type || 0,
					cart_type: self.cart_type || 0,
					table_id: self.table_id || 0,
					shop_supplier_id: self.shop_supplier_id || 0

				}
				self.isDetail = true;
			},
			//点击菜单项事件
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
						}, 200);
					}
				});
			},
			//商品列表滚动事件
			handleGoodsScroll({
				detail
			}) {
				if (!this.sizeCalcState) {
					this.calcSize();
				}
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
				if (!this.cartPopupVisible) {
					this.cart_list = [];
					this.cart_product_id = n;
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
		}
	};
</script>

<style lang="scss">
	@import './menu.scss';

	.top-status {
		padding: 0 26rpx;
		height: 104rpx;
		line-height: 104rpx;
		font-size: 28rpx;
		border-bottom: 1px solid;
		@include border_color('border_color');
		@include background_color('opacify_background_0');
	}
</style>