import Vue from 'vue'
import {defaultImg} from '../config/env'
import {
  getSessionStorage
} from '@/utils/base'
import {
  createdAuth
} from '@/utils/createdAuth'

/*指令测试*/
Vue.directive('demo', {
  bind: function (el, binding, vnode) {
    var s = JSON.stringify
    el.innerHTML =
      'name: '       + s(binding.name) + '<br>' +
      'value: '      + s(binding.value) + '<br>' +
      'expression: ' + s(binding.expression) + '<br>' +
      'argument: '   + s(binding.arg) + '<br>' +
      'modifiers: '  + s(binding.modifiers) + '<br>' +
      'vnode keys: ' + Object.keys(vnode).join(', ')
  }
})

/*权限*/
Vue.directive('auth',function(el,binding){
  let auth=getSessionStorage('authlist');
  if(!auth){
    let authlist={}
    createdAuth(auth,authlist);
    setSessionStorage('authlist', authlist);
    auth=authlist;
  }
  if(auth[binding.value]!=true){
    el.style.display="none";
  }

})

/*默认图片*/
Vue.directive('img-url', async function (el, binding) {
    let imgURL = binding.value;
    if (imgURL) {
        let exist = await imageIsExist(imgURL);
        if (exist) {
           el.setAttribute('src', imgURL);
        }else{
          el.setAttribute('src', defaultImg);
        }
    }else{
      el.setAttribute('src', defaultImg);
    }
})

/**
 * 检测图片是否存在
 * @param url
 */
let imageIsExist = function(url) {
    return new Promise((resolve) => {
        var img = new Image();
        img.onload = function () {
            if (this.complete == true){
                resolve(true);
                img = null;
            }
        }
        img.onerror = function () {
            resolve(false);
            img = null;
        }
        img.src = url;
    })
}
