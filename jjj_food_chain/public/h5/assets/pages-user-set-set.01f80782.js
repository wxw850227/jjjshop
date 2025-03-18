import{_ as e,l as a,p as t,m as s,J as o,R as l,o as n,c as i,w as u,n as d,f as c,a as p,d as r,t as f,h as m,j as h,i as g,I as _,A as b,K as y,k as I}from"./index-61e49878.js";import{P as k}from"./uni-popup.5fe8e197.js";import{U as w}from"./upload.a6979ced.js";/* empty css                                                                  */const v=e({components:{Popup:k,Upload:w},data:()=>({userInfo:{},isPopup:!1,imageList:[],newName:"",type:"",isUpload:!1}),onShow(){this.getData()},methods:{changeName(e){let a=this;console.log(e),"mobile"==e&&(a.oldmobile=a.userInfo.mobile),a.type=e,a.newName=a.userInfo[e],a.isPopup=!0},onChooseAvatar(e){console.log(e),this.uploadFile([e.detail.avatarUrl])},getData(){let e=this;a({title:"加载中"}),e._get("user.index/setting",{},(function(a){e.userInfo=a.data.userInfo,t()}))},changeAvatarUrl(){this.isUpload=!0},changeinput(e){this.newName=e.target.value},subName(e){let a=this;a.loading||(a.newName=e.detail.value.newName,a.userInfo[a.type]=this.newName,a.update())},uploadFile:function(e){let l=this;l.imageList=[];let n=0,i=e.length,u={token:s("token"),app_id:l.getAppId()};a({title:"图片上传中"}),e.forEach((function(e,a){o({url:l.websiteUrl+"/index.php?s=/api/file.upload/image",filePath:e,name:"iFile",formData:u,success:function(e){let a="object"==typeof e.data?e.data:JSON.parse(e.data);1===a.code?l.imageList.push(a.data):l.showError(a.msg)},complete:function(){n++,i===n&&(t(),l.getImgsFunc(l.imageList))}})}))},getImgsFunc(e){if(e&&void 0!==e){let a=this;a.userInfo.avatarUrl=e[0].file_path,a.update(),a.isUpload=!1}},hidePopupFunc(){this.isPopup=!1},fbindDateChange(e){this.birthday=e.detail.value},logout(){l("token"),l("user_id"),this.gotoPage("/pages/index/index")},Bindbirthday(){this.isBirthday=!0},update(){let e=this;if(e.loading)return;a({title:"加载中"});let s=e.userInfo;e.loading=!0,e._post("user.user/updateInfo",s,(function(a){e.showSuccess("修改成功",(function(){e.loading=!1,e.isPopup=!1,t(),e.getData()}),(function(a){t(),e.loading=!1,e.isPopup=!1}))}))}}},[["render",function(e,a,t,s,o,l){const k=h,w=g,v=c,N=_,P=b,U=y,x=I("Popup"),C=I("Upload");return n(),i(v,{class:d(["address-form",e.theme()||""]),"data-theme":e.theme()},{default:u((()=>[p(v,{class:"bg-white p-0-30 f30"},{default:u((()=>[p(v,{class:"d-b-c border-b p-30-0 info-item avatar"},{default:u((()=>[p(k,{class:"key-name"},{default:u((()=>[r("头像")])),_:1}),p(v,{class:"d-e-c",onClick:l.changeAvatarUrl},{default:u((()=>[p(v,{class:"info-image"},{default:u((()=>[p(w,{src:o.userInfo.avatarUrl||"/static/default.png",mode:""},null,8,["src"])])),_:1}),p(k,{class:"icon iconfont icon-jiantou"})])),_:1},8,["onClick"])])),_:1}),p(v,{class:"d-b-c p-30-0 border-b"},{default:u((()=>[p(k,{class:"key-name"},{default:u((()=>[r("会员ID")])),_:1}),p(v,{class:"d-e-c"},{default:u((()=>[p(k,{class:"mr20"},{default:u((()=>[r(f(o.userInfo.user_id),1)])),_:1})])),_:1})])),_:1}),p(v,{class:"d-b-c p-30-0 border-b",onClick:a[0]||(a[0]=e=>l.changeName("nickName"))},{default:u((()=>[p(k,{class:"key-name"},{default:u((()=>[r("昵称")])),_:1}),p(v,{class:"d-e-c"},{default:u((()=>[p(k,{class:"mr20"},{default:u((()=>[r(f(o.userInfo.nickName),1)])),_:1}),p(k,{class:"icon iconfont icon-jiantou"})])),_:1})])),_:1}),p(v,{class:"d-b-c p-30-0 border-b"},{default:u((()=>[p(k,{class:"key-name"},{default:u((()=>[r("手机号码")])),_:1}),o.userInfo.mobile?(n(),i(v,{key:0,class:"d-e-c"},{default:u((()=>[p(k,{class:"mr20"},{default:u((()=>[r(f(o.userInfo.mobile),1)])),_:1})])),_:1})):m("",!0),o.userInfo.mobile?m("",!0):(n(),i(v,{key:1,class:"d-e-c"},{default:u((()=>[p(k,{class:"mr20"},{default:u((()=>[r("未绑定")])),_:1})])),_:1}))])),_:1}),p(v,{class:"setup-btn theme-btn",onClick:a[1]||(a[1]=e=>l.logout())},{default:u((()=>[r("退出登录")])),_:1})])),_:1}),p(x,{show:o.isPopup,type:"bottom",width:750,padding:0,onHidePopup:l.hidePopupFunc},{default:u((()=>[p(U,{onSubmit:l.subName},{default:u((()=>[p(v,{class:"d-s-s d-c p20 mpservice-wrap"},{default:u((()=>[p(v,{class:"tc f32 fb ww100"},{default:u((()=>[r("修改")])),_:1}),"mobile"==o.type||"nickName"==o.type||"user_name"==o.type||"email"==o.type||"idcard"==o.type?(n(),i(v,{key:0,class:"pop-input d-b-c"},{default:u((()=>[p(N,{type:"text",name:"newName",class:"flex-1",placeholder:"请输入",value:o.newName,onInput:l.changeinput},null,8,["value","onInput"]),p(v,{class:"icon-guanbi icon iconfont",onClick:e.clearName},null,8,["onClick"])])),_:1})):m("",!0),p(v,{class:"d-a-c ww100"},{default:u((()=>[p(P,{class:"theme-borderbtn",onClick:l.hidePopupFunc},{default:u((()=>[r("取消")])),_:1},8,["onClick"]),p(P,{class:"theme-btn","form-type":"submit"},{default:u((()=>[r("确认")])),_:1})])),_:1})])),_:1})])),_:1},8,["onSubmit"])])),_:1},8,["show","onHidePopup"]),o.isUpload?(n(),i(C,{key:0,num:1,onGetImgs:l.getImgsFunc},null,8,["onGetImgs"])):m("",!0)])),_:1},8,["data-theme","class"])}],["__scopeId","data-v-7192b564"]]);export{v as default};
