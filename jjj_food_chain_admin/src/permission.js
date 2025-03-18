import router, {
  resetRouter
} from './router'
import store from './store/'
import {
  Message,
  Loading
} from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style
import {
  getCookie,
  setCookie
} from '@/utils/base'

 const whiteList = ['/login', '/test', '/fonticon']
// 在每个路由生效之前，先进行一些处理，请参考 vue-router官方文档-导航钩子
router.beforeEach((to, from, next) => {

  const isLogin = getCookie('isLogin');
  
  if(isLogin){
    NProgress.start();
    next();
    NProgress.done();
  }else{
    if(whiteList.indexOf(to.path.toLowerCase()) !== -1) {
      next();
    }else{
       next('/Login');
    }
  }

})

