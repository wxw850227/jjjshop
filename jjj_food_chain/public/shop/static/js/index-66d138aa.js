import{E as s,l as t,m as e,d as a,e as o,b as p}from"./element-plus-0b11a037.js";import{A as r}from"./appsetting-df0dc32d.js";import{_ as i}from"./index-7abb690d.js";import{o as m,c as l,P as n,S as d,W as c,a as u}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const j={data:()=>({form:{passport_open:0,passport_type:10}}),created(){this.getData()},methods:{getData(){let s=this;r.appDetail({},!0).then((t=>{s.form.passport_open=t.data.data.passport_open,s.form.passport_type=t.data.data.passport_type})).catch((s=>{}))},onSubmit(){let t=this,e=this.form;r.editApp(e,!0).then((e=>{s({message:"恭喜你，设置成功",type:"success"}),t.$router.push("/appsetting/app/index")})).catch((s=>{}))},handleCheckedCitiesChange(s){}}},f={class:"product-add"},h=u("div",{class:"common-form"},"通行证设置",-1),_=u("a",{href:"https://open.weixin.qq.com",target:"_blank",class:"blue"},"微信开放平台",-1),b={class:"common-button-wrapper"};const y=i(j,[["render",function(s,r,i,j,y,g){const v=t,x=e,w=a,k=o,C=p;return m(),l("div",f,[n(C,{size:"small",ref:"form",model:y.form,"label-width":"200px"},{default:d((()=>[h,n(w,{label:"通行证类型",class:"flex items-center text-sm"},{default:d((()=>[n(v,{modelValue:y.form.passport_type,"onUpdate:modelValue":r[0]||(r[0]=s=>y.form.passport_type=s),label:10,size:"large"},{default:d((()=>[c("微信开放平台")])),_:1},8,["modelValue"]),n(x,{class:"gray"},{default:d((()=>[c(" 目前仅支持微信开放平台，未来会支持手机号、用户名。如未注册或未绑定微信开放平台，请前往 "),_])),_:1})])),_:1}),u("div",b,[n(k,{type:"primary",onClick:g.onSubmit},{default:d((()=>[c("提交")])),_:1},8,["onClick"])])])),_:1},8,["model"])])}]]);export{y as default};
