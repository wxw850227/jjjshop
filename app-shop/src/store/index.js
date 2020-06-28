import Vue from 'vue'
import Vuex from 'vuex'
import common from './modules/common/index.js'
import user from './modules/user/index.js'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
  	common:common,
  	user:user
  }
  /* state,
  actions,
  mutations */
})
