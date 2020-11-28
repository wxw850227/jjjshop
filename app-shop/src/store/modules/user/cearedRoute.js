import Main from '@/views/layout/Main'

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
        name: createAlias(item.path),
        component: item.parent_id===0? Main:loadView(item.path)
      }
      /*判断是否重定向*/
      if(item.redirect_name&&typeof(item.redirect_name)!='undefined'&&item.redirect_name!=''){
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
    }else if(item.is_route == 2){
      let obj = {
        path: item.path,
        meta: {
          title: item.name
        },
        name: createAlias(item.path),
        component: loadView(item.path)
      }
       routelist.unshift(obj);
    }
  }
  return routelist;
}

/*生成别名*/
function createAlias(str){
   return str.replace(/\//g,'_');
}

/*引入对应的组件文件*/
function loadView(view)
{
  /*将后台传过来的组件地址改成小写*/
  view=view.toLowerCase();
  /*判断第一个是不是斜杠，如果不是加上斜杠*/
  if(view.substr(0,1)!='/'){
    view+='/'+view;
  }
  /*路由懒加载，如果报错需要下载 syntax-dynamic-import 本系统使用了Babel*/
  return () => import(`@/views${view}`);
}

export {
  cearedRoute
}