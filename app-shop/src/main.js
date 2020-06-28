// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/'
import directive from './directive'
import '@/assets/font/iconfont.js'
import Icon from '@/components/icon/Icon'
import filters from '@/filters/index'

/*路由全局前置守卫*/
import './permission' // permission control
/*导入图标组件*/
Vue.component('Icon', Icon)
/*引用element ui*/
Vue.use(ElementUI)
/*全局需要的过滤方法-视图用*/
for(let key in filters){
	Vue.filter(key,filters[key])
}
/*全局需要的过滤方法-方法用*/
Vue.prototype.$filter = filters
/*阻止启动生产消息，常用作指令*/
Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
