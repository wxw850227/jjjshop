import Main from '@/views/layout/Main'

let main = '@/views/layout/Main'

/*把数据列表生成vue路由*/
function cearedRoute(list, flag) {
  let routelist = [];
  for (let i = 0; i < list.length; i++) {
    let item = list[i];
    if (item.is_route == 1) {
      let obj = {
        path: item.path,
        meta: {
          title: item.name
        },
        name: item.alias,
        component: loadView(item.views)
      }
      /*判断是否重定向*/
      if(item.redirect_name!=null&&item.redirect_name!=''){
        obj['redirect']=item.redirect_name;
      }
      /*判断子路径是否是数组*/
      if (Object.prototype.toString.call(item.children) == '[object Array]' && item.children.length > 0) {
        let num = null;
        if (flag != null) {
          num = flag;
          num++;
        } else {
          num = 0;
        }
        let child = cearedRoute(item.children, num);
        if (num >= 1) {
          let temp = routelist.concat(child);
          routelist = temp;
        } else {
          obj.children = child;
        }
      }
      routelist.unshift(obj);
    }
  }
  return routelist;
}

function loadView(view)
{
  /*路由懒加载，如果报错需要下载 syntax-dynamic-import 本系统使用了Babel*/
  return () => import(`@/views/${view}`);
}

export {
  cearedRoute
}
