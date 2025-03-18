import{E as e,l,d as o,s as r,t as a,e as t,b as i}from"./element-plus-0b11a037.js";import{S as m}from"./setting-aa01e22d.js";import{_ as s}from"./index-ece0f3b6.js";import{o as d,c as n,P as p,S as u,a as _,W as f,T as b,Q as c,a8 as j,Y as h,bb as V,b9 as y}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const v={data:()=>({form:{buyer_open:"0",room_open:"0",seller_open:"0",buyer_printer_id:"",room_printer_id:"",seller_printer_id:""},checked:!1,printerList:[],loading:!1}),created(){this.getData()},methods:{getData(){let e=this;m.printingDetail({},!0).then((l=>{let o=l.data.vars.values;e.form.buyer_open=o.buyer_open,e.form.seller_open=o.seller_open,e.form.room_open=o.room_open,e.form.buyer_printer_id=""+o.buyer_printer_id,e.form.room_printer_id=""+o.room_printer_id,e.form.seller_printer_id=""+o.seller_printer_id,e.printerList=l.data.vars.printerList,null!=o.order_status&&20==o.order_status[0]&&(e.checked=!0)})).catch((e=>{}))},onSubmit(){let l=this,o=this.form;l.loading=!0,m.editPrinting(o,!0).then((o=>{l.loading=!1,e({message:"恭喜你，打印设置成功",type:"success"})})).catch((e=>{l.loading=!1}))},handleCheckedCitiesChange(e){let l=this;e?l.form.order_status[0]=20:l.form.order_status=[]}}},g={class:"product-add"},k=(e=>(V("data-v-50972768"),e=e(),y(),e))((()=>_("div",{class:"common-form"},"小票打印设置",-1))),U={class:"common-button-wrapper"};const L=s(v,[["render",function(e,m,s,V,y,v){const L=l,w=o,x=r,C=a,S=t,D=i;return d(),n("div",g,[p(D,{size:"small",ref:"form",model:y.form,"label-width":"200px"},{default:u((()=>[k,p(w,{label:"是否开启商户小票打印"},{default:u((()=>[_("div",null,[p(L,{modelValue:y.form.seller_open,"onUpdate:modelValue":m[0]||(m[0]=e=>y.form.seller_open=e),label:"1"},{default:u((()=>[f("开启")])),_:1},8,["modelValue"]),p(L,{modelValue:y.form.seller_open,"onUpdate:modelValue":m[1]||(m[1]=e=>y.form.seller_open=e),label:"0"},{default:u((()=>[f("关闭")])),_:1},8,["modelValue"])])])),_:1}),1==y.form.seller_open?(d(),b(w,{key:0,label:"选择打印机"},{default:u((()=>[p(C,{modelValue:y.form.seller_printer_id,"onUpdate:modelValue":m[2]||(m[2]=e=>y.form.seller_printer_id=e),placeholder:"请选择"},{default:u((()=>[(d(!0),n(c,null,j(y.printerList,((e,l)=>(d(),b(x,{key:l,label:e.printer_name,value:e.printer_id+""},null,8,["label","value"])))),128))])),_:1},8,["modelValue"])])),_:1})):h("",!0),p(w,{label:"是否开启顾客小票打印"},{default:u((()=>[_("div",null,[p(L,{modelValue:y.form.buyer_open,"onUpdate:modelValue":m[3]||(m[3]=e=>y.form.buyer_open=e),label:"1"},{default:u((()=>[f("开启")])),_:1},8,["modelValue"]),p(L,{modelValue:y.form.buyer_open,"onUpdate:modelValue":m[4]||(m[4]=e=>y.form.buyer_open=e),label:"0"},{default:u((()=>[f("关闭")])),_:1},8,["modelValue"])])])),_:1}),1==y.form.buyer_open?(d(),b(w,{key:1,label:"选择打印机二"},{default:u((()=>[p(C,{modelValue:y.form.buyer_printer_id,"onUpdate:modelValue":m[5]||(m[5]=e=>y.form.buyer_printer_id=e),placeholder:"请选择"},{default:u((()=>[(d(!0),n(c,null,j(y.printerList,((e,l)=>(d(),b(x,{key:l,label:e.printer_name,value:e.printer_id+""},null,8,["label","value"])))),128))])),_:1},8,["modelValue"])])),_:1})):h("",!0),p(w,{label:"是否开启厨房小票打印"},{default:u((()=>[_("div",null,[p(L,{modelValue:y.form.room_open,"onUpdate:modelValue":m[6]||(m[6]=e=>y.form.room_open=e),label:"1"},{default:u((()=>[f("开启")])),_:1},8,["modelValue"]),p(L,{modelValue:y.form.room_open,"onUpdate:modelValue":m[7]||(m[7]=e=>y.form.room_open=e),label:"0"},{default:u((()=>[f("关闭")])),_:1},8,["modelValue"])])])),_:1}),1==y.form.room_open?(d(),b(w,{key:2,label:"选择打印机三"},{default:u((()=>[p(C,{modelValue:y.form.room_printer_id,"onUpdate:modelValue":m[8]||(m[8]=e=>y.form.room_printer_id=e),placeholder:"请选择"},{default:u((()=>[(d(!0),n(c,null,j(y.printerList,((e,l)=>(d(),b(x,{key:l,label:e.printer_name,value:e.printer_id+""},null,8,["label","value"])))),128))])),_:1},8,["modelValue"])])),_:1})):h("",!0),_("div",U,[p(S,{type:"primary",onClick:v.onSubmit,loading:y.loading},{default:u((()=>[f("提交")])),_:1},8,["onClick","loading"])])])),_:1},8,["model"])])}],["__scopeId","data-v-50972768"]]);export{L as default};
