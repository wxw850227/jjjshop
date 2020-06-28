<template>
    <view>
        <view class="top-box">
            <!-- 搜索框 -->
            <view class="index-search-box">
                <view class="index-search t-c" @click="gotoSearch">
                    <span class="icon iconfont icon-sousuo"></span>
                    <text class="ml10">{{search != '' ? search : '搜索产品'}}</text>
                </view>
            </view>
        </view>
        <view class="prodcut-list-wrap">
            <scroll-view scroll-y="true" class="scroll-Y" :style="'height:' + scrollviewHigh + 'px;'"
                         lower-threshold="50"
                         @scrolltolower="scrolltolowerFunc">
                <view :class="topRefresh?'top-refresh open':'top-refresh'">
                    <view class="circle" v-for="(circle,n) in 3" :key="n"></view>
                </view>
                <view class="list">
                    <view class="item" v-for="(item, index) in listData" :key="index"
                          @click="gotoList(item.product_id)">
                        <view class="product-cover">
                            <image :src="item.product_img" mode="aspectFit"></image>
                        </view>
                        <view class="product-info">
                            <view class="product-title">
                                {{item.product_name}}
                            </view>
                            <view class="already-sale">
                                剩余{{item.seckill_stock}}件
                            </view>
                            <view class="price">
                                所需积分：
                                <text class="num">{{item.point_num}}</text>
                            </view>
                        </view>
                    </view>
                </view>
                <!-- 没有记录 -->
                <view class="d-c-c p30" v-if="listData.length==0 && !loading">
                    <text class="iconfont icon-wushuju"></text>
                    <text class="cont">亲，暂无相关记录哦</text>
                </view>
                <uni-load-more v-else :loadingType="loadingType"></uni-load-more>
            </scroll-view>
        </view>
    </view>
</template>

<script>
    import uniLoadMore from "@/components/uni-load-more.vue";
    export default {
        components: {
            uniLoadMore
        },
        data() {
            return {
                /*手机高度*/
                phoneHeight: 0,
                /*可滚动视图区域高度*/
                scrollviewHigh: 0,
                /*顶部刷新*/
                topRefresh: false,
                /*底部加载*/
                loading: true,
                /*没有更多*/
                no_more: false,
                /*类别选中*/
                type_active: 0,
                /*价格选中*/
                price_top: false,
                /*商品列表*/
                listData: [],
                /*当前页面*/
                page: 1,
                list_rows: 10,
                last_page: 0,

            };
        },
        computed: {

            /*加载中状态*/
            loadingType(){
                if (this.loading) {
                    return 1;
                } else {
                    if (this.listData.length != 0 && this.no_more) {
                        return 2;
                    } else {
                        return 0;
                    }
                }
            }
        },
        onLoad(e) {

        },
        mounted() {
            this.init();
            /*获取产品列表*/
            this.getData();
        },
        onPullDownRefresh() {
            /*下拉到顶，页面值还原初始化*/
            this.restoreData();
            this.getData();
        },
        methods: {
            /*初始化*/
            init() {
                let _this = this;
                uni.getSystemInfo({
                    success(res) {
                        _this.phoneHeight = res.windowHeight;
                        // 计算组件的高度
                        let view = uni.createSelectorQuery().select('.top-box');
                        view.boundingClientRect(data =>
                        {
                            let h = _this.phoneHeight - data.height;
                            _this.scrollviewHigh = h;
                        }).exec();
                    }
                });
            },
            /*还原初始化*/
            restoreData() {
                this.listData = [];
            },

            /*获取数据*/
            getData() {
                let self = this;
                let page = self.page;
                let list_rows = self.list_rows;
                self.loading = true;
                self._get('points.exchange/index', {
                    page: page || 1,
                    list_rows: list_rows,
                }, function (res)
                {
                    self.loading = false;
                    self.listData = self.listData.concat(res.data.list.data);
                    self.last_page = res.data.list.last_page;
                    if (res.data.list.last_page <= 1) {
                        self.no_more = true;
                    }
                });
            },

            /*跳转产品列表*/
            gotoList(e) {
                uni.navigateTo({
                    url: '/pages/product/detail/detail?product_id=' + e + '&from_type=20'
                });
            },

            /*跳转搜索页面*/
            gotoSearch() {
                uni.navigateTo({
                    url: '/pages/product/search/search'
                });
            },
            /*可滚动视图区域到底触发*/
            scrolltolowerFunc() {
                let self = this;
                self.bottomRefresh = true;
                self.page++;
                self.loading = true;
                if (self.page > self.last_page) {
                    self.loading = false;
                    self.no_more = true;
                    return;
                }
                self.getData();
            },
            /**
             * 设置分享内容
             */
            onShareAppMessage() {
                // 构建分享参数
                return {
                    title: "全部分类",
                    path: "/pages/product/category?" + this.getShareUrlParams()
                };
            },
        }
    };
</script>

<style lang="scss">
    .inner-tab {
        height: 80rpx;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-bottom: 1px solid #dddddd;
        background: #ffffff;
    }

    .inner-tab .item {
        flex: 1;
        font-size: 30rpx;
    }

    .inner-tab .item.active,
    .inner-tab .item .arrow.active .iconfont {
        color: $dominant-color;
    }

    .inner-tab .item .box {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
    }

    .inner-tab .item .arrows {
        margin-left: 10rpx;
        line-height: 0;
    }

    .inner-tab .item .iconfont {
        line-height: 24rpx;
        font-size: 24rpx;
    }

    .inner-tab .item .arrow,
    .inner-tab .item .svg-icon {
        width: 20rpx;
        height: 20rpx;
    }

    .prodcut-list-wrap .list {
        background: #FFFFFF;
    }

    .prodcut-list-wrap .list .item {
        padding: 20rpx;
        display: flex;
        border-bottom: 1px solid #dddddd;
    }

    .prodcut-list-wrap .product-cover,
    .prodcut-list-wrap .product-cover image {
        width: 220rpx;
        height: 220rpx;
    }

    .prodcut-list-wrap .product-info {
        flex: 1;
        margin-left: 20rpx;
    }

    .prodcut-list-wrap .product-title {
        display: -webkit-box;
        overflow: hidden;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 28rpx;
    }

    .prodcut-list-wrap .already-sale {
        padding: 4rpx 0;
        color: #999;
        font-size: 24rpx;
    }

    .prodcut-list-wrap .price {
        color: $dominant-color;
        font-size: 24rpx;
    }

    .prodcut-list-wrap .price .num {
        padding: 0 4rpx;
        font-size: 40rpx;
    }
</style>
