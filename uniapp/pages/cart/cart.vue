<template>
	<view class="card" v-if="!loadding">
		<!--产品列表-->
		<template v-if="tableData.length > 0">
			<view class="address-bar d-e-c">
				<view class="f30" @click="isEdit = !isEdit">
					<button v-if="isEdit">完成</button>
					<button v-else>编辑</button>
				</view>
			</view>

			<view class="section">
				<view class="item" v-for="(item, index) in tableData" :key="index">
					<label class="d-c-c" @click="checkItem(item, index)">
						<checkbox value="cb" class="checkbox" :checked="item.checked" />
					</label>
					<image :src="item.product_image" class="cover" mode="aspectFit"></image>
					<view class="info">
						<view class="title">{{ item.product_name }}</view>
						<view class="describe">{{ item.selling_point }}</view>
						<view class="level-box count_choose">
							<view class="price">
								¥
								<text class="num">{{ item.product_price }}</text>
							</view>
							<view class="num-wrap">
								<view class="icon-box minus d-c-c" @click="reduceFunc(item)">
									<span class="icon iconfont icon-jian" :class="item.total_num<=1?'gray':'gray3'"></span>
								</view>
								<view class="text-wrap">
									<input type="number" :maxlength="item.product_sku.stock_num" v-model="item.total_num" />
								</view>
								<view class="icon-box plus d-c-c" @click="addFunc(item)">
									<span class="icon iconfont icon-jia" :class="item.total_num>=item.product_sku.stock_num?'gray':'gray3'"></span>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</template>
		<template v-else>
			<view class="none-data-box">
				<image src="/static/none.png" mode="widthFix"></image>
				<text>购物车空空如也</text>
				<button type="default" class="btn-red mt30 ww100" @click="gotoShop">去购物</button>
			</view>
		</template>

		<!--底部按钮-->
		<view class="bottom-btns f28" v-if="tableData.length > 0">
			<label class="d-c-c mr20" @click="onCheckedAll()">
				<checkbox class="checkbox" :checked="checkedAll" value="cb" />
				全选
			</label>
			<view class="d-e-c" v-if="!isEdit">
				<view class="total d-s-c flex-1 mr20">
					<text>合计：</text>
					<view class="price f22">
						¥
						<text class="num f40">{{ totalPrice }}</text>
					</view>
				</view>
				<button type="primary" class="buy-btn" @click="Submit()">去结算</button>
			</view>
			<view class="" v-else><button type="primary" @click="onDelete()" class="delete-btn mr20">删除</button></view>
		</view>

	</view>
</template>

<script>
	
	export default {
		components: {
		},
		data() {
			return {
				/*是否加载完成*/
                loadding: true,
                isEdit: false,
				/*购物车商品*/
                tableData: [],
                arrIds: [],
                // 是否全选
                checkedAll: false,
                totalPrice: 0
            };
		},
		onLoad() {},
		onShow() {
			/*获取产品详情*/
			this.getData();
		},
		methods: {

			/*获取数据*/
            getData() {
                uni.showLoading({
                    title: '加载中',
					mask:true
                });
                let self = this;
                self._get('order.cart/lists', {}, function(res) {
                    uni.hideLoading();
                    self.tableData = res.data;
                    self.loadding = false;
                    self._initGoodsChecked();
                });
            },
            /**
             * 初始化商品选中状态
             */
            _initGoodsChecked() {
                let self = this;
                let checkedData = self.getCheckedData();
                // 将商品设置选中
                self.tableData.forEach(item => {
                    item.checked = self.inArray(`${item.product_id}_${item.product_sku_id}`, checkedData);
                });
                self.isEdit = false;
                self.checkedAll = checkedData.length == self.tableData.length;
                // 更新购物车已选商品总价格
                self.updateTotalPrice();
            },
            /**
             * 获取选中的商品
             */
            getCheckedData() {
                let checkedData = uni.getStorageSync('checkedData');
                return checkedData ? checkedData : [];
            },
			/*单选*/
            checkItem(e, index) {
                e.checked = !e.checked;
                this.$set(this.tableData, index, e);

                // 更新选中记录
                this.onUpdateChecked();
                // 更新购物车已选商品总价格
                this.updateTotalPrice();
                // 更新全选状态
                this.checkedAll = this.getCheckedData().length == this.tableData.length;
            },
            /**
             * 更新商品选中记录
             */
            onUpdateChecked() {
                let self = this,
                    checkedData = [];
                self.tableData.forEach(item => {
                    if (item.checked == true) {
                        checkedData.push(`${item.product_id}_${item.product_sku_id}`);
                    }
                });
                uni.setStorageSync('checkedData', checkedData);
            },
			/*全选*/
            onCheckedAll() {
                let self = this;
                self.checkedAll = !self.checkedAll;
                self.tableData.forEach(item => {
                    item.checked = self.checkedAll;
                });
                self.updateTotalPrice();
                // 更新选中记录
                self.onUpdateChecked();
            },
            updateTotalPrice: function() {
                let total_price = 0;
                let items = this.tableData;
                for (let i = 0; i < items.length; i++) {
                    if (items[i]['checked'] == true) {
                        total_price += items[i]['total_num'] * items[i]['product_price'];
                    }
                }
				/*保留2位小数*/
                this.totalPrice = total_price.toFixed(2);
            },
			/*去结算*/
            Submit() {
                let arrIds = [];
                this.tableData.forEach(item => {
                    if (item.checked == true) {
                        arrIds.push(`${item.product_id}_${item.product_sku_id}`);
                    }
                });
                if (arrIds.length == 0) {
                    uni.showToast({
                        title: '请选择商品',
                        icon: 'none'
                    });
                    return false;
                }
                uni.navigateTo({
                    url: '/pages/order/confirm-order/confirm-order?order_type=cart&cart_ids=' + arrIds
                });
            },
			/*添加*/
            addFunc(item) {
                let self = this;
                let product_id = item.product_id;
                let product_sku_id = item.product_sku_id;
                uni.showLoading({
                    title: '加载中',
					mask:true
                });
                self._post(
                    'order.cart/add', {
                        product_id: product_id,
                        product_sku_id: product_sku_id,
                        total_num: 1
                    },
                    function(res) {
                        self.loadding = false;
                        self.getData();
                    },
                    function() {
                        self.loadding = false;
                    }
                );
            },
			/*减少*/
            reduceFunc(item) {
                let self = this;
                let product_id = item.product_id;
                let product_sku_id = item.product_sku_id;
                if (item.total_num <= 1) {
                    return;
                }
                uni.showLoading({
                    title: '加载中',
					mask:true
                });
                self._post(
                    'order.cart/sub', {
                        product_id: product_id,
                        product_sku_id: product_sku_id
                    },
                    function(res) {
                        self.loadding = false;
                        self.getData();
                    },
                    function() {
                        self.loadding = false;
                    }
                );
            },
			/*删除购物车*/
            onDelete() {
                let self = this;
                let cartIds = self.getCheckedIds();
                if (!cartIds.length) {
                    self.showError('您还没有选择商品');
                    return false;
                }
                uni.showModal({
                    title: "提示",
                    content: "您确定要移除选择的商品吗?",
                    success(e) {
                        e.confirm && self._post(
                            'order.cart/delete', {
                                product_sku_id: cartIds
                            },
                            function(res) {
                                // 删除选中的商品
                                self.onDeleteEvent(cartIds);
                                self.getData();
                            }
                        );
                    }
                });
            },
            /**
             * 获取已选中的商品
             */
            getCheckedIds() {
                let self = this;
                let arrIds = [];
                self.tableData.forEach(item => {
                    if (item.checked === true) {
                        arrIds.push(`${item.product_id}_${item.product_sku_id}`);
                    }
                });
                return arrIds;
            },

            /**
             * 商品删除事件
             */
            onDeleteEvent(cartIds) {
                let self = this;
                cartIds.forEach((cartIndex) => {
                    self.tableData.forEach((item, productIndex) => {
                        if (cartIndex == `${item.product_id}_${item.product_sku_id}`) {
                            self.tableData.splice(productIndex, 1);
                        }
                    });
                });
                // 更新选中记录
                self.onUpdateChecked();
                return true;
            },
            /**
             * 是否在数组内
             */
            inArray(search, array) {
                for (var i in array) {
                    if (array[i] == search) {
                        return true;
                    }
                }
                return false;
            },
			
			/*去购物*/
			gotoShop(){
				uni.switchTab({
				    url: '/pages/index/index'
				});
			}
		}
	};
</script>

<style lang="scss">
	.card {
		padding-bottom: 100rpx;
	}

	.card .checkbox {
		transform: scale(0.7)
	}

	.address-bar {
		padding: 20rpx;
	}
	
	.address-bar button{ border:1rpx solid $dominant-color; background: #FFFFFF; color:$dominant-color;}

	.section {
		background: #FFFFFF;
	}

	.section .item {
		padding: 20rpx;
		display: flex;
		border-bottom: 1px solid #EEEEEE;
	}

	.section .cover {
		width: 200rpx;
		height: 200rpx;
	}

	.section .info {
		flex: 1;
		padding-left: 20rpx;
		box-sizing: border-box;
		overflow: hidden;
	}
	.section .title{font-size: 34rpx;}
	.section .title,
	.vender .list .describe {
		width: 100%;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.section .describe {
		margin-top: 20rpx;
		font-size: 24rpx;
		color: #999999;
	}

	.section .price {
		color: $dominant-color;
		font-size: 24rpx;
	}

	.section .price .num {
		padding: 0 4rpx;
		font-size: 40rpx;
	}

	.section .level-box {
		margin-top: 20rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.section .level-box .key {
		font-size: 24rpx;
		color: #999999;
	}

	.section .level-box .num-wrap {
		display: flex;
		justify-content: flex-end;
		align-items: center;
	}

	.section .level-box .icon-box {
		width: 60rpx;
		height: 60rpx;
		border: 1px solid #DDDDDD;
		background: #f7f7f7;
	}

	.section .level-box .icon-box .gray {
		color: #CCCCCC;
	}

	.section .level-box .icon-box .gray3 {
		color: #333333;
	}

	.section .level-box .text-wrap {
		margin: 0 4rpx;
		height: 60rpx;
		border: 1px solid #DDDDDD;
		background: #f7f7f7;
	}

	.section .level-box .text-wrap input {
		padding: 0 10rpx;
		height: 60rpx;
		line-height: 60rpx;
		width: 80rpx;
		font-size: 24rpx;
		text-align: center;
	}

	.bottom-btns {
		position: fixed;
		padding: 0 0 0 20rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: 90rpx;
		right: 0;
		bottom: calc(var(--window-bottom);
		left: 0;
		box-shadow: 0 -2rpx 8rpx rgba(0, 0, 0, .1);
		background: #FFFFFF;
		z-index: 99;
	}

	.bottom-btns .delete-btn {
		margin: 0;
		padding: 0 30rpx;
		height: 70rpx;
		line-height: 70rpx;
		border-radius: 35rpx;
		background: $dominant-color;
		font-size: 30rpx;
	}

	.bottom-btns .buy-btn {
		margin: 0;
		padding: 0 60rpx;
		height: 90rpx;
		line-height: 90rpx;
		border-radius: 0;
		background: $dominant-color;
		font-size: 30rpx;
	}

	.bottom-btns .price {
		color: $dominant-color;
	}
</style>
