<script>
	//import Utils from '@/common/iconfont.js';
	export default {
		onLaunch: function(e) {
			console.log('App Launch');
			//#ifdef MP-WEIXIN	
			//检查更新
			this.updateManager();
			//#endif
			//应用启动参数
			this.onStartupScene(e.query);
		},
		onShow: function() {
			//console.log('App Show')
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
				let scene = this.getSceneData(query);
				// 记录推荐人id
				let refereeId = query.referee_id ? query.referee_id : scene.uid;
				if (refereeId > 0) {
					if (!uni.getStorageSync('referee_id')) {
						uni.setStorageSync('referee_id', refereeId);
					}
				}
			},
			/**
			 * 获取场景值(scene)
			 */
			getSceneData(query) {
				return query.scene ? this.scene_decode(query.scene) : {};
			},
			/**
			 * scene解码
			 */
			scene_decode(e) {
				if (e === undefined)
					return {};
				let scene = decodeURIComponent(e),
					params = scene.split(','),
					data = {};
				for (let i in params) {
					var val = params[i].split(':');
					val.length > 0 && val[0] && (data[val[0]] = val[1] || null)
				}
				return data;
			},
		}
	}
</script>

<style>
	/*每个页面公共css */
	/* @import './common/uni.css'; */
	/* @import './common/iconfont.css'; */
	@import './common/style.css';
</style>
