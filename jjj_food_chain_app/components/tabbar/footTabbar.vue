<template>
	<view class="" v-if="detail.is_auto!=0 && hasTab" @click.stop>
		<view class="foot-bottom"></view>
		<view class="foot-tavbar-container d-a-c" :style="'background:'+detail.backgroundColor || '' + ';' "
			v-if="detail.list!=''">
			<view class="item d-c-c" :class="{'active':item.link_url == getRoute()}" v-for="(item, index) in detail.list"
				:key="index" @click="tabBarFunc(item)">
				<view style="height: 0;width: 0;opacity: 0;">{{getRoute()}}</view>
				<view class="inner d-c-c d-c">
					<image class="images" v-if="detail.type!=2" :src="item.link_url == getRoute()?item.selectedIconPath:item.iconPath"
						mode="aspectFill">
					</image>
					<text v-if="detail.type!=1"
						:style="item.link_url == getRoute()?'color:'+detail.textHoverColor+';':'color:'+detail.textColor+';'"
						class="text-name">{{ item.text}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				/*当前选中*/
				activeTabber: '首页',
				/*打开直播菜单*/
				open_liveMenu: false,
				/*底部菜单*/
				detail: {},
				/*是否微信小程序*/
				platform: 'wx',
				hasTab: false
			};
		},
		watch: {
		},
		created() {
			let pages = getCurrentPages();
			if (pages.length) {
				let currentPage = pages[pages.length - 1];
				if (currentPage.route == 'pages/index/index') {
					this.$store.commit('changefootTab', '首页');
				}
			}
			this.platform = this.getPlatform();
			this.detail = uni.getStorageSync('TabBar');
			this.getData();
		},
		methods: {
			hasmenu() {
				let self = this;
				let curRoute = self.getRoute();
				let res = self.detail.list.some(item => {
					if (item.link_url == curRoute) {
						return true
					}
				})
				self.hasTab = res;
				if (uni.getStorageSync('TabBar').is_auto && uni.getStorageSync('TabBar').is_auto != 0) {
					uni.hideTabBar()
				}
			},
			getRoute() {
				let self = this;
				let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
				let curRoute = '/' + routes[routes.length - 1].route //获取当前页面路由
				if (curRoute == '/pages/diy-page/diy-page' || curRoute == '/pages/article/detail/detail') {
					if (routes[routes.length - 1]['$page']) {
						curRoute = routes[routes.length - 1]['$page'].fullPath
					}
				}
				return curRoute
			},
			/*点击菜单*/
			tabBarFunc(e) {
				if (this.footTabberData.active == e.text) {
					return
				}
				this.$store.commit('changefootTab', e.text);
				this.gotoPage(e.link_url);
			},
			/*获取首页分类*/
			getData() {
				let self = this;
				self._get('index/nav', {}, function(res) {
					self.detail = res.data.vars.data;

					uni.setStorageSync('TabBar', res.data.vars.data);
					self.hasmenu();
				});
			},

		}
	};
</script>

<style lang="scss">
	.foot-bottom {
		width: 100%;
		height: 98rpx;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.foot-tavbar-container {
		box-shadow: 0 0 6rpx 0 rgba(0, 0, 0, 0.3);
		position: fixed;
		right: 0;
		bottom: 0;
		left: 0;
		height: 98rpx;
		background: #FFFFFF;
		z-index: 90;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.foot-tavbar-container .item {
		flex: 1;
		height: 98rpx;
	}

	.foot-tavbar-container .item.add-btn .inner {
		margin-bottom: 70rpx;
		width: 100rpx;
		height: 100rpx;
		border-radius: 50%;
		background: $dominant-color;
		box-shadow: 0 0 10rpx 0 rgba(232, 38, 100, .6);
	}

	.foot-tavbar-container .images {
		width: 50rpx;
		height: 50rpx;
	}

	.foot-tavbar-container .text-name {
		font-size: 24rpx;
		color: #666666;
	}

	.foot-tavbar-container .item.active .text-name {
		color: #f8c341;
	}
</style>
