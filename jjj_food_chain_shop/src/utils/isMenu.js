/*封装菜单的方法*/
export const isMenu = (list) => {
  for(let i=0;i<list.length;i++){
    let item=list[i];
    console.log(item,item.name);
    if(item.is_route==0||((item.is_route==1||item.is_route==2)&&item.is_menu==0)){
      list.splice(i,1);
      i--;
    }else{
      if(Object.prototype.toString.call(item.children) === '[object Array]'){
         isMenu(item.children);
      }
    }
  }
}

function allChildMenu(item,arr){
  let list=[];
  if(typeof item.children !='undefined'){
    for(let i=0,leng=item.children.length;i<leng;i++){
      let child=item.children[i];
      if((child.is_route==1||child.is_route==2)&&child.is_menu==1){
        let obj={
          name:child.name,
          icon:child.icon,
          path:child.path,
          alias:child.alias,
          redirect_name:child.redirect_name,
        }
        list.push(obj);
      }
    }
  }
  arr=arr.concat(list);
  return list;
}
