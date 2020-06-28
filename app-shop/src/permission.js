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
  setCookie,
  delCookie,
  setSessionStorage,
  getSessionStorage
} from '@/utils/base'

NProgress.configure({
  showSpinner: false
})
const whiteList = ['/login', '/test', '/fonticon'];

// 在每个路由生效之前，先进行一些处理，请参考 vue-router官方文档-导航钩子
router.beforeEach(async (to, from, next) => {
  const isLogin = getCookie('isLogin');
  if (to.meta.title) {
    document.title = to.meta.title + '-三勾商城后台管理';
  }
   //判断是否登录
  if (isLogin) {
    NProgress.start();
    let hasRoles = store.state.user.roles && store.state.user.roles.length > 0;
    if (hasRoles) {
      next();
      NProgress.done();
    } else {
      try {
        //参数暂时无效
        const roles = Math.random();
        const accessRoutes = await store.dispatch('user/generateRoutes', roles);
        resetRouter();
        router.addRoutes(accessRoutes);
        next({ ...to,
          replace: true
        });
      } catch (error) {
        next('/login');
      }
      NProgress.done();
    }

  } else {
    if (whiteList.indexOf(to.path.toLowerCase()) !== -1) {
      next();
      NProgress.done();
    } else {
      next('/login');
    }
  }
})

/*注册一个全局守卫*/
router.beforeResolve((to, from, next) => {
  next();
})

/*全局后置钩子*/
router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
