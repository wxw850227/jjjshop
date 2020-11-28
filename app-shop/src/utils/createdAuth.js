function createdAuth(list,obj){
  for (let i = 0; i < list.length; i++) {
    let item = list[i];
    if(item.path!=null&&item.path!=''){
       obj[item.path]=true;
    }
    if (Object.prototype.toString.call(item.children) == '[object Array]' && item.children.length > 0) {
      createdAuth(item.children,obj);
    }
  }
}

export {
  createdAuth
}
