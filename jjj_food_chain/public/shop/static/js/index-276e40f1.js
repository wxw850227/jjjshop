import{E as e,c as s,d as a,e as t,b as o}from"./element-plus-0b11a037.js";import{S as i}from"./setting-829576b6.js";import{_ as r}from"./index-7abb690d.js";import{o as l,c as d,P as m,S as p,W as n,a as c,bb as u,b9 as j}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const f={data:()=>({form:{close_days:"",receive_days:""},loading:!1}),created(){this.getParams()},methods:{getParams(){let e=this;i.tradeDetail({},!0).then((s=>{if(1==s.code){let a=s.data.vars.values;e.form.close_days=a.order.close_days,e.form.receive_days=a.order.receive_days}})).catch((e=>{}))},handleCheckedCitiesChange(e){},onSubmit(){let s=this;s.loading=!0;let a=s.form;i.editTrade(a,!0).then((a=>{s.loading=!1,e({message:"恭喜你，交易设置成功",type:"success"}),s.$router.push("/setting/trade/index")})).catch((e=>{s.loading=!1}))}}},h=e=>(u("data-v-9d613785"),e=e(),j(),e),v={class:"product-add"},y=h((()=>c("div",{class:"common-form"},"订单流程设置",-1))),b=h((()=>c("div",{class:"tips"}," 订单下单未付款，n分钟后自动关闭，设置0不自动关闭 ",-1))),_=h((()=>c("div",{class:"tips"}," 如果在期间未核销，系统自动完成核销，设置0不自动核销(不包括桌台订单) ",-1))),g={class:"common-button-wrapper"};const x=r(f,[["render",function(e,i,r,u,j,f){const h=s,x=a,w=t,V=o;return l(),d("div",v,[m(V,{size:"small",ref:"form",model:j.form,"label-width":"150px"},{default:p((()=>[y,m(x,{label:"未支付订单"},{default:p((()=>[m(h,{modelValue:j.form.close_days,"onUpdate:modelValue":i[0]||(i[0]=e=>j.form.close_days=e),type:"number",style:{width:"200px"}},null,8,["modelValue"]),n(" 分钟后自动关闭 "),b])),_:1}),m(x,{label:"已支付订单"},{default:p((()=>[m(h,{modelValue:j.form.receive_days,"onUpdate:modelValue":i[1]||(i[1]=e=>j.form.receive_days=e),type:"number",style:{width:"200px"}},null,8,["modelValue"]),n(" 分钟后自动核销 "),_])),_:1}),c("div",g,[m(w,{type:"primary",onClick:f.onSubmit,loading:j.loading},{default:p((()=>[n("提交")])),_:1},8,["onClick","loading"])])])),_:1},8,["model"])])}],["__scopeId","data-v-9d613785"]]);export{x as default};
