/*用户状态*/
import {
  setSessionStorage,
  getSessionStorage
} from '@/utils/base'
import getRolelist from './getRolelist.js'
import getBaseRouter, { errpage } from '@/router/baseRouter.js'

import {
  cearedRoute
} from './cearedRoute.js'
import {
  createdAuth
} from '@/utils/createdAuth'


const menu = getBaseRouter;

const user = {

  namespaced: true,

  /*状态值*/
  state: {
    roles: null,
    page: null
  },

  /*状态值转换*/
  getters: {

    getroles: (state) => (name) => {
      return state.roles;
    },

  },

  /*改变状态的方法 不可异步*/
  mutations: {

    setState(state, value) {
      state[value.key] = value.val;
    },

    saveMenulist(state, value) {
      state.getters.menulist = value;
    }

  },

  /*可异步改变*/
  actions: {

    generateRoutes: async function(context, str) {
      /*返回理由*/
      return new Promise((resolve, reject) => {
        /*判断本地缓存是否有菜单数据*/
        let rolelist = getSessionStorage('rolelist');
        let routerlist = null;
        if (rolelist) {
          let auth = getSessionStorage('authlist');
          if (!auth) {
            let authlist = {}
            createdAuth(auth, authlist);
            setSessionStorage('authlist', authlist);
          }
          let list = cearedRoute(rolelist);
          routerlist = menu.concat(list, errpage);
          context.commit("setState", {
            key: 'roles',
            val: rolelist
          });
          resolve(routerlist);
        } else {
          getRolelist().then(res => {
              let list = cearedRoute(res);
              routerlist = menu.concat(list, errpage);
              setSessionStorage('rolelist', res);
              let authlist = {}
              createdAuth(res, authlist);
              setSessionStorage('authlist', authlist);
              context.commit("setState", {
                key: 'roles',
                val: rolelist
              });
              resolve(routerlist);
            })
            .catch(error => {
              reject(error);
            });
        }
      })
    }

  }
}


export default user;
