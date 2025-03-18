function requestFun(app) {
	/**
	 * @description get请求
	 * @param
	 * @returns
	 */
	app.config.globalProperties._get = function(path, data, success, fail, complete) {
		console.log(this.websiteUrl, "llll")
		data = data || {};
		data.token = uni.getStorageSync('token') || '';
		data.app_id = this.getAppId();
		uni.request({
			url: this.websiteUrl + '/index.php/api/' + path,
			data: data,
			dataType: 'json',
			method: 'GET',
			header: {
				'appId': this.getAppId()
			},
			success: (res) => {
				if (res.statusCode !== 200 || typeof res.data !== 'object') {
					return false;
				}
				if (res.data.code === -2) {
					this.showError(res.data.msg, function() {
						uni.removeStorageSync('token');
						// this.gotoPage('/pages/index/index', 'reLaunch');
					})
				} else if (res.data.code === -1) {
					// 登录态失效, 重新登录
					console.log('登录态失效, 重新登录');
					this.doLogin();
				} else if (res.data.code === 0) {
					this.showError(res.data.msg, function() {
						fail && fail(res);
					});
					return false;
				} else if (res.data.code === 10) {
					/* 店铺不存在 */
					this.showError(res.data.msg, function() {
						uni.removeStorageSync('selectedId');
						uni.reLaunch({
							url: '/pages/index/index'
						})
					});
					return false;
				} else {
					success && success(res.data);
				}
			},
			fail: (res) => {
				fail && fail(res);
			},
			complete: (res) => {
				uni.hideLoading();
				complete && complete(res);
			},
		});
	};
	/**
	 * @description post请求
	 * @param
	 * @returns
	 */
	app.config.globalProperties._post = function(path, data, success, fail, complete) {
		data = data || {};
		data.token = uni.getStorageSync('token') || '';
		data.app_id = this.getAppId();
		uni.request({
			url: this.websiteUrl + '/index.php/api/' + path,
			data: data,
			dataType: 'json',
			method: 'POST',
			header: {
				'content-type': 'application/x-www-form-urlencoded',
				'appId': this.getAppId()
			},
			success: (res) => {
				if (res.statusCode !== 200 || typeof res.data !== 'object') {
					return false;
				}
				if (res.data.code === -1) {
					// 登录态失效, 重新登录
					console.log('登录态失效, 重新登录');
					this.doLogin();
				} else if (res.data.code === 0) {
					this.showError(res.data.msg, function() {
						fail && fail(res);
					});
					return false;
				} else {
					success && success(res.data);
				}
			},
			fail: (res) => {
				fail && fail(res);
			},
			complete: (res) => {
				uni.hideLoading();
				complete && complete(res);
			},
		});
	};
	/**
	 * @description 登录失效 重新登录
	 * @param
	 * @returns
	 */
	app.config.globalProperties.doLogin = function() {
		let pages = getCurrentPages();
		if (pages.length) {
			let currentPage = pages[pages.length - 1];
			if ("pages/login/login" != currentPage.route &&
				"pages/login/weblogin" != currentPage.route &&
				"pages/login/openlogin" != currentPage.route) {
				uni.setStorageSync("currentPage", currentPage.route);
				uni.setStorageSync("currentPageOptions", currentPage.options);
			}
		}
		console.log('app_ID=' + this.getAppId())
		//公众号
		// #ifdef  H5
		this.gotoPage("/pages/login/weblogin");
		// #endif
		// #ifdef APP-PLUS
		this.gotoPage("/pages/login/openlogin", 'redirect');
		return;
		// #endif
		// 非公众号,跳转授权页面
		// #ifndef  H5
		this.gotoPage("/pages/login/login");
		// #endif
	};
}

export default requestFun