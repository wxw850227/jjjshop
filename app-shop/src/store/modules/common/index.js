/*全局状态*/
const common={

	namespaced: true,

	/*状态值*/
	state: {
		is_show: false,
		page: null
	},

	/*状态值转换*/
	getters: {
		getTset: (state) => (name) => {
			return state.test + name;
		}
	},

	/*改变状态的方法 不可异步*/
	mutations: {
		setState(state, value) {
			state[value.key]=value.val;
		}
	},

	/*可异步改变*/
	actions:{
		saveTest(context) {
			context.commit('saveTest')
		}
	}
}
export default common;
