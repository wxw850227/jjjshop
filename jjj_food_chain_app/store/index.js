import { createStore } from "vuex";
const store = createStore({
	// 全局属性变量
	state: { // state的作用是定义属性变量。定义一个参数
		theme: uni.getStorageSync("theme") || 0,
		footTab:'',
		mapkey:''
	},
	// 全局同步方法，在methods{this.$store.commit("changeTheme")}
	mutations: {
		changeTheme(state, value) {
			state.theme = value;
		},
		changeMapkey(state, value) {
			state.mapkey = value;
		}
	},
	getters: {

	},
	actions: {

	}

})
export default store