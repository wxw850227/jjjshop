import { defineStore } from 'pinia';
import { addSessionStorage, getSessionStorage } from '@/utils/base.js';  
import configObj from "@/config";  
let { strongToken } = configObj;
export const useUserStore = defineStore('main', {
	state: () => {
		return {
			token: getSessionStorage(strongToken),
			userInfo: getSessionStorage('userInfo'),
			list: {},
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
			const { data: { token, user_name } } = info;
			this.userInfo.userName = user_name;
			this.token = token;
			addSessionStorage(strongToken,token);  
			addSessionStorage('userInfo',this.userInfo);
		},
		/**
		 * @description 退出登录
		 * @param {*} token
		 */
		afterLogout() {
			sessionStorage.clear();
			this.token = null;
			this.menus = null;
		},
	}
});
export default useUserStore;