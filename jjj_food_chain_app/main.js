import App from './App'

// #ifndef VUE3
import Vue from 'vue'
import './uni.promisify.adaptor'
Vue.config.productionTip = false
App.mpType = 'app'
const app = new Vue({
	...App
})
app.$mount()
// #endif
// #ifdef VUE3
import {
	createSSRApp
} from 'vue'
import onfire from '@/common/onfire.js'
// 公共组件
import headerBar from './components/header.vue'
/*底部数据*/
import tabBar from "@/components/tabbar/footTabbar.vue"
/* 配置 */
import config from "./env/config.js";
import {
	gotopage
} from '@/common/gotopage.js'
import requestFun from '@/utils/request.js';
import validator from '@/utils/validator.js';
import store from "./store/index.js"
//#ifdef H5
import jweixin from 'weixin-js-sdk';
//#endif
export function createApp() {
	const app = createSSRApp(App)

	app.component("header-bar", headerBar)
	app.component("tabBar", tabBar)

	app.use(store)
	app.config.globalProperties.$store = store;
	app.config.globalProperties.footTabberData = {
		active: 'home'
	}
	app.config.globalProperties.$fire = new onfire()
	app.config.globalProperties.config = config
	app.config.globalProperties.websiteUrl = config.app_url;
	app.config.globalProperties.app_id = config.app_id;
	//h5发布路径
	app.config.globalProperties.h5_addr = config.h5_addr;
	// #ifdef H5
	app.config.globalProperties.configWx = function(signPackage, shareParams, params) {
		if (signPackage == '') {
			return;
		}
		let self = this;

		jweixin.config(JSON.parse(signPackage));
		let url_params = self.getShareUrlParams(params);

		jweixin.ready(function(res) {
			jweixin.updateAppMessageShareData({
				// jweixin.onMenuShareAppMessage({
				title: shareParams.title,
				desc: shareParams.desc,
				link: self.websiteUrl + self.h5_addr + shareParams.link + '?' + url_params,
				imgUrl: shareParams.imgUrl,
				success: function() {

				}
			});
			jweixin.updateTimelineShareData({
				title: shareParams.title,
				desc: shareParams.desc,
				link: self.websiteUrl + self.h5_addr + shareParams.link + '?' + url_params,
				imgUrl: shareParams.imgUrl,
				success: function() {

				}
			});
		});
	};
	app.config.globalProperties.configWxScan = function(signPackage) {
		if (signPackage == '') {
			return;
		}
		jweixin.config(JSON.parse(signPackage));
		return jweixin;
	};
	// #endif
	/*页面跳转*/
	app.config.globalProperties.gotoPage = gotopage;
	app.config.globalProperties.font_url = config.font_url;
	//#ifdef H5
	// app.$router.afterEach((to, from) => {
	// 	const u = navigator.userAgent.toLowerCase()
	// 	if (u.indexOf("like mac os x") < 0 || u.match(/MicroMessenger/i) != 'micromessenger') return
	// 	if (to.path !== global.location.pathname) {
	// 		location.assign(config.h5_addr + to.fullPath);
	// 	}
	// })
	//#endif

	//请求
	requestFun(app);

	validator(app);

	app.config.globalProperties.theme = function() {
		return 'theme' + this.$store.state.theme || ''
	}
	app.config.globalProperties.callPhone = function(phone) {
		uni.makePhoneCall({
			phoneNumber: phone //仅为示例
		});
	}
	app.config.globalProperties.openmap = function(latitude, longitude) {
		uni.openLocation({
			longitude: Number(longitude),
			latitude: Number(latitude),
		})
	}
	app.config.globalProperties.footTab = function() {
		return this.$store.state.footTab || ''
	}

	app.config.globalProperties.getTabBarLinks = function() {
		let varsParams = uni.getStorageSync('TabBar');
		let theme = uni.getStorageSync('theme');
		if (varsParams != null && varsParams != '') {
			this.setTabBarLinks(varsParams, theme);
		} else {
			uni.request({
				url: this.config.app_url + '/index.php/api/index/nav',
				data: {
					app_id: this.config.app_id
				},
				success: (result) => {
					let varsParams = result.data.data.vars.data;
					let theme = result.data.data.theme.theme;
					this.$store.commit('changeTheme', theme);
					uni.setStorageSync('TabBar', varsParams);
					uni.setStorageSync('theme', theme);
					this.setTabBarLinks(varsParams, theme);
				}
			});
		}
	}
	app.config.globalProperties.getThemeColor = function() {
		let theme = this.$store.state.theme;
		let color = ['#FEE238', '#b91d32', '#09B4F1', '#00A348', '#FD8103', '#B99970', '#A4A4D6'];
		return color[theme]
	}
	app.config.globalProperties.getTimeData = function(timestamp) {
		if (typeof timestamp === 'string') {
			timestamp = Number(timestamp);
		}
		if (typeof timestamp !== 'number') {
			alert("输入参数无法识别为时间戳");
		}
		let date = new Date(timestamp);
		let Y = date.getFullYear() + '-';
		let M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
		let D = date.getDate() + ' ';
		let h = date.getHours() + ':';
		let m = date.getMinutes() + ':';
		let s = date.getSeconds();
		return Y + M + D + h + m + s;
	}


	return {
		app,
	}
}
// #endif