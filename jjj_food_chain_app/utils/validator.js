import utils from '@/common/utils.js';
import config from '@/env/config.js';

function validator(app) {
	/**
	 * 获取应用ID
	 */
	app.config.globalProperties.getAppId = function() {
		return uni.getStorageSync('app_id') || config.app_id || 10001
	};
	app.config.globalProperties.compareVersion = function(v1, v2) {
		v1 = v1.split('.')
		v2 = v2.split('.')
		const len = Math.max(v1.length, v2.length)

		while (v1.length < len) {
			v1.push('0')
		}
		while (v2.length < len) {
			v2.push('0')
		}

		for (let i = 0; i < len; i++) {
			const num1 = parseInt(v1[i])
			const num2 = parseInt(v2[i])

			if (num1 > num2) {
				return 1
			} else if (num1 < num2) {
				return -1
			}
		}

		return 0
	};
	/**
	 * 获取访客
	 */
	app.config.globalProperties.getVisitcode = function() {
		let visitcode = uni.getStorageSync('visitcode');
		if (!visitcode) {
			visitcode = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
				var r = Math.random() * 16 | 0,
					v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			});
			visitcode = visitcode.replace(/-/g, "");
			uni.setStorageSync('visitcode', visitcode);
		}

		return visitcode;
	};
	/**
	 * 订阅通知,目前仅小程序
	 */
	app.config.globalProperties.subMessage = function(temlIds, callback) {
		let self = this;
		// #ifdef  MP-WEIXIN
		//小程序订阅消息
		const version = wx.getSystemInfoSync().SDKVersion;
		if (temlIds && temlIds.length != 0 && self.compareVersion(version, '2.8.2') >= 0) {
			uni.hideLoading();
			wx.requestSubscribeMessage({
				tmplIds: temlIds,
				success(res) {},
				fail(res) {},
				complete(res) {
					callback();
				},
			});
		} else {
			callback();
		}
		// #endif
		// #ifndef MP-WEIXIN
		callback();
		// #endif
	};
	/**
	 * 显示失败提示框
	 */
	app.config.globalProperties.showError = function(msg, callback) {
		uni.showModal({
			title: '友情提示',
			content: msg,
			showCancel: false,
			success: function(res) {
				callback && callback();
			}
		});
	};
	/**
	 * 显示成功提示框
	 */
	app.config.globalProperties.showSuccess = function(msg, callback) {
		uni.showModal({
			title: '友情提示',
			content: msg,
			showCancel: false,
			success: function(res) {
				callback && callback();
			}
		});
	};
	/**
	 * 生成转发的url参数
	 */
	app.config.globalProperties.getShareUrlParams = function(params) {
		let self = this;
		return utils.urlEncode(Object.assign({
			referee_id: self.getUserId(),
			app_id: self.getAppId()
		}, params));
	};
	/**
	 * 当前用户id
	 */
	app.config.globalProperties.getUserId = function() {
		return uni.getStorageSync('user_id');
	};
	/**
	 * 是否是ios
	 */
	app.config.globalProperties.ios = function() {
		const u = navigator.userAgent.toLowerCase();
		if (u.indexOf("like mac os x") < 0 || u.match(/MicroMessenger/i) != 'micromessenger') {
			return false;
		}
		return true;
	};
	/**
	 * 获取当前平台
	 */
	app.config.globalProperties.isWeixin = function() {
		var ua = navigator.userAgent.toLowerCase();
		if (ua.match(/MicroMessenger/i) == "micromessenger") {
			return true;
		} else {
			return false;
		}
	};
	app.config.globalProperties.getPlatform = function(params) {
		let platform = 'wx';
		// #ifdef  APP-PLUS
		if (uni.getSystemInfoSync().platform == 'android') {
			platform = 'android';
		} else {
			platform = 'ios';
		}
		// #endif
		// #ifdef  H5
		if (this.isWeixin()) {
			platform = 'mp';
		} else {
			platform = 'h5';
		}
		// #endif
		return platform;
	};
	app.config.globalProperties.getNavHeight = function() {
		return this.topBarHeight() == 0 ? '' : 'height:' + this.topBarHeight() + 'px;';
	};
	app.config.globalProperties.topBarTop = function() {
		// #ifdef MP-WEIXIN
		return uni.getMenuButtonBoundingClientRect().top;
		// #endif
		// #ifndef MP-WEIXIN
		const SystemInfo = uni.getSystemInfoSync();
		return SystemInfo.statusBarHeight;
		// #endif
	};
	app.config.globalProperties.topBarHeight = function() {
		// #ifdef MP-WEIXIN
		return uni.getMenuButtonBoundingClientRect().height;
		// #endif
		// #ifndef MP-WEIXIN
		return 0
		// #endif
	};
	/* 小数点截取 */
	app.config.globalProperties.subPrice = function(str, val) {
		let strs = String(str);
		if (val == 1) {
			return strs.substring(0, strs.indexOf("."));
		} else if (val == 2) {
			let indof = strs.indexOf(".");
			return strs.slice(indof + 1, indof + 3);
		}
	}
	/*转两位数*/
	app.config.globalProperties.convertTwo = function(n) {
		let str = '';
		if (n < 10) {
			str = '0' + n;
		} else {
			str = n;
		}
		return str;
	}
	app.config.globalProperties.yulan = function(e, i) {
		let image_path_arr = [];
		if (!Array.isArray(e)) {
			image_path_arr = [e];
		} else {
			if (e[0].file_path) {
				e.forEach((item, index) => {
					image_path_arr.push(item.file_path)
				})
			} else {
				image_path_arr = e
			}
		}
		let picnum = i * 1;
		uni.previewImage({
			urls: image_path_arr,
			current: picnum,
		});
	}
	/* 截取小数点前后 */
	app.config.globalProperties.subpoint = function(str, val) {
		let strs = String(str);
		if (val == 1) {
			return strs.substring(0, strs.indexOf("."));
		} else if (val == 2) {
			let indof = strs.indexOf(".");
			return strs.slice(indof + 1, indof + 3);
		}
	}

}

export default validator