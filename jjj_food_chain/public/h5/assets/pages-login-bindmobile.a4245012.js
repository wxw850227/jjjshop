import{_ as s,y as e,l as a,p as t,z as o,o as n,c as i,w as l,f as d,a as c,d as r,A as u}from"./index-61e49878.js";const _=s({data:()=>({background:"",listData:[],sessionKey:""}),onLoad(){let s=this;e({success(e){s._post("user.user/getSession",{code:e.code},(e=>{s.sessionKey=e.data.session_key}))}})},methods:{onNotLogin:function(){this.gotoPage("/pages/index/index")},getPhoneNumber(s){var n=this;if("getPhoneNumber:ok"!==s.detail.errMsg)return!1;a({title:"正在处理",mask:!0}),e({success(e){n._post("user.user/bindMobile",{session_key:n.sessionKey,encrypted_data:s.detail.encryptedData,iv:s.detail.iv},(s=>{t(),o()}),!1,(()=>{t()}))}})}}},[["render",function(s,e,a,t,o,_){const f=d,g=u;return n(),i(f,{class:"login-container"},{default:l((()=>[c(f,{class:"wechatapp"},{default:l((()=>[c(f,{class:"header"})])),_:1}),c(f,{class:"auth-title"},{default:l((()=>[r("申请获取以下权限")])),_:1}),c(f,{class:"auth-subtitle"},{default:l((()=>[r("为了为您提供更优质的服务，我们需要获得你的手机号")])),_:1}),c(f,{class:"login-btn"}),c(f,{class:"no-login-btn"},{default:l((()=>[c(g,{class:"btn-normal",onClick:_.onNotLogin},{default:l((()=>[r("暂不授权")])),_:1},8,["onClick"])])),_:1})])),_:1})}],["__scopeId","data-v-70222afe"]]);export{_ as default};
