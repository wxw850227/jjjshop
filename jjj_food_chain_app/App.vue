<script>
	import utils from './common/utils.js'
	import config from "./env/config.js";
	export default {
		onLaunch: function(e) {
			console.log('App Launch');
			//#ifdef MP-WEIXIN	
			//检查更新
			this.updateManager();
			wx.login(); //重新登录,防止解密失败
			//#endif
			// #ifdef APP-PLUS
			plus.runtime.getProperty(plus.runtime.appid, function(widgetInfo) {
				console.log('------------------------------');
				uni.request({
					url: config.app_url + '/index.php/api/index/update',
					data: {
						version: widgetInfo.version,
						name: widgetInfo.name,
						app_id: config.app_id,
						platform: uni.getSystemInfoSync().platform
					},
					success: data => {
						if (data.update && data.wgtUrl) {
							uni.downloadFile({
								url: data.wgtUrl,
								success: (downloadResult) => {
									if (downloadResult.statusCode === 200) {
										plus.runtime.install(downloadResult
											.tempFilePath, {
												force: true
											},
											function() {
												console.log('install success...');
												plus.nativeUI.alert(
													"已更新至最新版本，确定后将重启应用",
													function() {
														plus.runtime.restart();
													}, "更新提示", "确定");
											},
											function(e) {
												console.error('install fail...');
											});
									}
								}
							});
						}
						if (data.update && data.pkgUrl) {
							plus.nativeUI.confirm("有新版本更新，请点击确认更新到最新版本，以免影响使用", function(e) {
								if (e.index == 0) {
									plus.runtime.openURL(data.pkgUrl);
								}
							}, {
								"title": "更新提示",
								"buttons": ["确定", "取消"],
								"verticalAlign": "center"
							});
						}
					},
					error: (error) => {
						console.log('----------------error');
						console.log(error);
					}
				});
			});
			// #endif
			//应用启动参数
			this.onStartupScene(e.query);
			this.getTabBarLinks();
		},
		onShow: function() {
			//console.log('App Show')
			//#ifdef APP-PLUS
			getApp().globalData.vueObj = this;
			//#endif
		},
		onHide: function() {
			//console.log('App Hide')
		},
		methods: {
			updateManager: function() {
				const updateManager = uni.getUpdateManager();
				updateManager.onCheckForUpdate(function(res) {
					// 请求完新版本信息的回调
					if (res.hasUpdate) {
						updateManager.onUpdateReady(function(res2) {
							uni.showModal({
								title: '更新提示',
								content: '新版本已经准备好，即将重启应用',
								showCancel: false,
								success(res2) {
									if (res2.confirm) {
										// 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
										updateManager.applyUpdate();
									}
								}
							});
						});
					}
				});

				updateManager.onUpdateFailed(function(res) {
					// 新的版本下载失败
					uni.showModal({
						title: '更新提示',
						content: '检查到有新版本，但下载失败，请检查网络设置',
						showCancel: false
					});
				});
			},
			/**
			 * 小程序启动场景
			 */
			onStartupScene(query) {
				// 获取场景值
				let scene = utils.getSceneData(query);
				// 记录推荐人id
				let refereeId = query && query.referee_id;
				if (refereeId > 0) {
					if (!uni.getStorageSync('referee_id')) {
						uni.setStorageSync('referee_id', refereeId);
						console.log('refereeId=' + refereeId)
					}
				}
				// 记录分销人id
				let uid = scene.uid;
				if (uid > 0) {
					if (!uni.getStorageSync('referee_id')) {
						uni.setStorageSync('referee_id', uid);
					}
				}
				// 如果是h5，设置app_id
				// #ifdef  H5
				let appId = query.app_id;
				if (appId > 0) {
					uni.setStorageSync('app_id', appId);
				}
				if (uni.getStorageSync('app_id')) {
					this.config.app_id = uni.getStorageSync('app_id');
				}
				// #endif
			},
			getTabBarLinks() {
				uni.request({
					url: this.config.app_url + '/index.php/api/index/nav',
					data: {
						app_id: this.config.app_id
					},
					success: (result) => {
						let theme = 2;
						/* 获取主题名 */
						this.$store.commit('changeTheme', theme);
						this.$store.commit('changeMapkey', result.data.data.key);
						let color = ['#FEE238', '#b91d32', '#09B4F1', '#00A348', '#FD8103', '#B99970', '#A4A4D6']
						uni.setTabBarStyle({
							color: '#333333',
							selectedColor: color[theme],
						})
						uni.setTabBarItem({
							index: 0,
							text: '首页',
							iconPath: '/static/tab/home.png',
							selectedIconPath: '/static/tab/home_' + theme + '.png'
						})
						uni.setTabBarItem({
							index: 1,
							text: '订单',
							iconPath: '/static/tab/order.png',
							selectedIconPath: '/static/tab/order_' + theme + '.png'
						})
						uni.setTabBarItem({
							index: 2,
							text: '我的',
							iconPath: '/static/tab/user.png',
							selectedIconPath: '/static/tab/user_' + theme + '.png'
						})
						uni.setStorageSync('theme', theme);
					}
				});
			}
		}
	}
</script>

<style lang="scss">
	@import './common/myIcon.css';
	@import './common/iconfont.scss';
	/*每个页面公共css */
	@import './common/style.scss';

	page {
		background: #f6f6f6;
		font-size: 0.75rem;
		min-height: 100vh;
	}
</style>