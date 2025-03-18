import {
	defineStore
} from 'pinia';
import {
	setStorage,
	getStorage
} from '@/utils/storageData';
import AuthApi from '@/api/auth.js';
import configObj from '@/config';
let {
	strongToken,
	renderMenu,
	menu
} = configObj;
import {
	handRouterTable,
	handMenuData
} from '@/utils/router';
export const useUserStore = defineStore('main', {
	state: () => {
		return {
			token: getStorage(strongToken),
			userInfo: getStorage('userInfo'),
			list: {},
			menus: getStorage(menu),
			renderMenus: getStorage(renderMenu),
		};
	},
	getters: {},
	actions: {
		bus_on(name, fn) {
			let self = this;
			self.list[name] = self.list[name] || [];
			self.list[name].push(fn);
		},
		// 发布
		bus_emit(name, data) {
			let self = this;
			if (self.list[name]) {
				self.list[name].forEach((fn) => {
					fn(data);
				});
			}
		},
		// 取消订阅
		bus_off(name) {
			let self = this;
			if (self.list[name]) {
				delete self.list[name];
			}
		},
		/**
		 * @description 登录
		 * @param {*} token
		 */
		async afterLogin(info) {
			this.userInfo = this.userInfo || {};
			const {
				data: {
					app_id,
					shop_name,
					shop_supplier_id,
					supplier_name,
					token,
					user_name,
					user_type,
					version,
					logoUrl
				}
			} = info;
			const {
				data: {
					menus
				}
			} = await AuthApi.getRoleList({
				token
			});
			let renderMenusList = handMenuData(JSON.parse(JSON.stringify(menus)));
			let menusList = handRouterTable(JSON.parse(JSON.stringify(menus)));
			setStorage(JSON.stringify(menusList), menu);
			setStorage(JSON.stringify(renderMenusList), renderMenu);
			this.userInfo.shop_supplier_id = shop_supplier_id;
			this.userInfo.userName = user_name;
			this.userInfo.version = version;
			this.userInfo.logoUrl = logoUrl;
			this.userInfo.shopName = shop_name;
			this.userInfo.AppID = app_id;
			this.userInfo.supplier_name = supplier_name;
			this.userInfo.user_type = user_type;
			this.token = token;
			this.renderMenus = renderMenusList;
			this.menus = menusList;
			setStorage(JSON.stringify(token), strongToken);
			setStorage(JSON.stringify(this.userInfo), 'userInfo');
		},
		/**
		 * @description 退出登录
		 * @param {*} token
		 */
		afterLogout() {
			sessionStorage.clear();
			this.token = null;
			this.menus = null;
			this.userInfo = null;
			setStorage(null, 'userInfo');
		},
		changStore(data) {
			this.userInfo.shopName = data.name;
			this.userInfo.logoUrl = data.logoUrl;
			setStorage(JSON.stringify(this.userInfo), 'userInfo');
		}
	}
});
export default useUserStore;