import{s as e,t as a,u as l,w as s,e as t,p as n}from"./element-plus-0b11a037.js";import{_ as i}from"./index-ece0f3b6.js";import{o,T as c,S as u,c as d,Q as r,a8 as p,a as m,X as g,P as v,W as y,Y as h}from"./@vue-5441e37d.js";const k=i({data:()=>({pages:[{url:"/pages/user/address/address",name:"收货地址",type:"菜单"},{url:"/pages/user/set/set",name:"设置",type:"菜单"},{url:"scanQrcode",name:"扫一扫",type:"菜单"}],activePage:"收货地址"}),created(){this.changeFunc(this.pages[0])},methods:{changeFunc(e){this.$emit("changeData",e)}}},[["render",function(l,s,t,n,i,m){const g=e,v=a;return o(),c(v,{modelValue:i.activePage,"onUpdate:modelValue":s[0]||(s[0]=e=>i.activePage=e),placeholder:"请选择",class:"percent-w100",onChange:m.changeFunc,"value-key":"url"},{default:u((()=>[(o(!0),d(r,null,p(i.pages,(e=>(o(),c(g,{key:e.url,"value-key":e.name,label:e.name,value:e},null,8,["value-key","label","value"])))),128))])),_:1},8,["modelValue","onChange"])}]]);const V=i({data:()=>({pages:[{url:"pages/index/index",name:"首页",type:"页面"},{url:"pages/order/myorder",name:"订单",type:"页面"},{url:"pages/user/index/index",name:"我的",type:"页面"},{url:"pages/product/list/takeaway?orderType=takein",name:"自取",type:"页面"},{url:"pages/product/list/takeaway?orderType=takeout",name:"外卖",type:"页面"},{url:"pages/product/list/store",name:"快餐模式",type:"页面"}],activePage:"首页"}),created(){this.changeFunc(this.pages[0])},methods:{changeFunc(e){this.$emit("changeData",e)}}},[["render",function(l,s,t,n,i,m){const g=e,v=a;return o(),c(v,{modelValue:i.activePage,"onUpdate:modelValue":s[0]||(s[0]=e=>i.activePage=e),placeholder:"请选择",class:"percent-w100",onChange:m.changeFunc,"value-key":"url"},{default:u((()=>[(o(!0),d(r,null,p(i.pages,(e=>(o(),c(g,{key:e.url,"value-key":e.name,label:e.name,value:e},null,8,["value-key","label","value"])))),128))])),_:1},8,["modelValue","onChange"])}]]),f={components:{Menu:k,Pages:V},data:()=>({dialogVisible:!0,activeData:null,activeName:"pages"}),props:["is_linkset"],created(){this.dialogVisible=this.is_linkset},methods:{dialogFormVisible(e){e?this.$emit("closeDialog",this.activeData):this.$emit("closeDialog",null)},activeDataFunc(e){this.activeData=e}}},b={class:"dialog-footer d-b-c"},D={class:"flex-1"},F={key:0,class:"d-s-s d-c tl"},C={class:"text-ellipsis setlink-set-link"},_=m("span",null,"当前链接：",-1),x={class:"gray9"},P=m("span",{class:"p-0-10 gray"},"/",-1),w={class:"blue"},N={class:"text-ellipsis gray",style:{"font-size":"10px"}},U={key:1,class:"tl"},$={class:"setlink-footer-btn"};const j=i(f,[["render",function(e,a,i,r,p,f){const j=V,z=l,T=k,Q=s,M=t,S=n;return o(),c(S,{title:"超链接设置",modelValue:p.dialogVisible,"onUpdate:modelValue":a[3]||(a[3]=e=>p.dialogVisible=e),onClose:f.dialogFormVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},{footer:u((()=>[m("div",b,[m("div",D,[null!=p.activeData?(o(),d("div",F,[m("p",C,[_,m("span",x,g(p.activeData.type),1),P,m("span",w,g(p.activeData.name),1)]),m("p",N,g(p.activeData.url),1)])):(o(),d("div",U," 暂无 "))]),m("div",$,[v(M,{size:"small",onClick:a[1]||(a[1]=e=>f.dialogFormVisible(!1))},{default:u((()=>[y("取 消")])),_:1}),v(M,{size:"small",type:"primary",onClick:a[2]||(a[2]=e=>f.dialogFormVisible(!0))},{default:u((()=>[y("确 定")])),_:1})])])])),default:u((()=>[v(Q,{type:"border-card",modelValue:p.activeName,"onUpdate:modelValue":a[0]||(a[0]=e=>p.activeName=e)},{default:u((()=>[v(z,{label:"页面",name:"pages"},{default:u((()=>["pages"==p.activeName?(o(),c(j,{key:0,onChangeData:f.activeDataFunc},null,8,["onChangeData"])):h("",!0)])),_:1}),v(z,{label:"我的菜单",name:"menu"},{default:u((()=>["menu"==p.activeName?(o(),c(T,{key:0,onChangeData:f.activeDataFunc},null,8,["onChangeData"])):h("",!0)])),_:1})])),_:1},8,["modelValue"])])),_:1},8,["modelValue","onClose"])}]]);export{j as _};
