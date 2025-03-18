import{n as t,E as e,u as s,w as a}from"./element-plus-0b11a037.js";import{u as i,_ as r}from"./index-7abb690d.js";import{U as o}from"./user-241de972.js";import{b as p}from"./vue-router-e88e0a38.js";import n from"./UpdatePassword-8a6b3ab2.js";import{F as u,K as l,z as c,L as h,aj as m,o as d,c as y,a as b,X as v,P as _,S as j,Q as x,a8 as f,T as g,Y as q}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const w=u({components:{UpdatePassword:n},setup(){const t=p(),{userInfo:e,bus_on:s,bus_emit:a,bus_off:r,afterLogout:o}=i(),n=l({menu_title:"菜单",tabList:[],activeValue:0,is_password:!1,tab_type:""});return s("MenuName",(t=>{n.menu_title=t})),s("tabData",(t=>{n.tabList=t.list,n.activeValue=t.active,n.tab_type=t.tab_type})),s("activeValue",(t=>{t&&t.params?n.activeValue=t.params:n.activeValue=t})),s("noTarget",(t=>{n.activeValue=t})),a("headLoad",!0),c((()=>{r("menuName"),r("tabData")})),{...h(n),userInfo:e,afterLogout:o,router:t,bus_emit:a}},methods:{exit(){let s=this;t.confirm("此操作将退出登录, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((()=>{o.loginOut({},!0).then((t=>{s.logout()})).catch((t=>{}))})).catch((()=>{e({type:"info",message:"已取消退出"})}))},async logout(){await this.afterLogout(),this.router.push("/login")},tabClick(t){let e=t.props;"queuemanage"==this.tab_type&&this.router.push({path:"/plus/queue/index",query:{type:e.name}}),"tablemanage"==this.tab_type&&this.router.push({path:"/store/table/index",query:{type:e.name}}),"takeaway"==this.tab_type&&this.$router.push({path:"/product/takeaway/index",query:{type:e.name}}),"storeproduct"==this.tab_type&&this.$router.push({path:"/product/store/index",query:{type:e.name}}),"expand"==this.tab_type&&this.$router.push({path:"/product/expand/index",query:{type:e.name}}),"agent"==this.tab_type&&this.$router.push({path:"/plus/agent/index",query:{type:e.name}}),"driver"==this.tab_type&&this.$router.push({path:"/plus/driver/index",query:{type:e.name}}),"coupon"==this.tab_type&&this.$router.push({path:"/plus/coupon/index",query:{type:e.name}}),"points"==this.tab_type&&this.$router.push({path:"/plus/points/index",query:{type:e.name}}),"seckill"==this.tab_type&&this.$router.push({path:"/plus/seckill/index",query:{type:e.name}}),"assemble"==this.tab_type&&this.$router.push({path:"/plus/assemble/index",query:{type:e.name}}),"bargain"==this.tab_type&&this.$router.push({path:"/plus/bargain/index",query:{type:e.name}}),"store"==this.tab_type&&this.$router.push({path:"/store/index",query:{type:e.name}}),"takeout"==this.tab_type&&this.$router.push({path:"/takeout/index",query:{type:e.name}}),"appopen"==this.tab_type&&this.$router.push({path:"/appsetting/appopen/event",query:{type:e.name}}),this.activeValue=e.name,this.bus_emit("activeValue",e.name)},passwordFunc(){this.is_password=!0},closeFunc(){this.is_password=!1}}}),k={class:"common-header"},$={class:"breadcrumb"},V={class:"baseInfo-left-base d-s-c"},C={class:"name"},L={class:"el-tabs-container"},T={class:"header-navbar"},F={class:"header-navbar-icon"},I={class:"gray"},P={class:"header-navbar-icon"},U=b("span",{class:"ml4 icon iconfont icon-geren9"},null,-1),N={class:"text ml4 blue"},z=b("div",{class:"header-navbar-icon"},[b("span",{class:"gray"},"|")],-1),B=[b("span",{class:"text"},"修改密码",-1)],D=[b("span",{class:"icon iconfont icon-tuichu"},null,-1),b("span",{class:"text ml4"},"退出",-1)];const S=r(w,[["render",function(t,e,i,r,o,p){const n=s,u=a,l=m("UpdatePassword");return d(),y("div",k,[b("div",$,[b("div",V,[b("span",C,v(t.menu_title),1),b("div",L,[_(u,{"model-value":t.activeValue,onTabClick:t.tabClick},{default:j((()=>[(d(!0),y(x,null,f(t.tabList,((t,e)=>(d(),g(n,{label:t.value,name:t.key,key:e},null,8,["label","name"])))),128))])),_:1},8,["model-value","onTabClick"])])]),b("div",T,[b("div",F,[b("span",I,"当前版本："+v(t.userInfo.version),1)]),b("div",P,[U,b("span",N,v(t.userInfo.userName)+"，欢迎您！",1)]),z,b("div",{class:"header-navbar-icon",onClick:e[0]||(e[0]=e=>t.passwordFunc())},B),b("div",{class:"header-navbar-icon login-out",onClick:e[1]||(e[1]=e=>t.exit())},D)])]),t.is_password?(d(),g(l,{key:0,onClose:t.closeFunc},null,8,["onClose"])):q("",!0)])}]]);export{S as default};
