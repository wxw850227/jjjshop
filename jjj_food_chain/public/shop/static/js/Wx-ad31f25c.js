import{E as e,c as l,d as t,k as a,h as s,b as i,e as o,p as d,v as n}from"./element-plus-0b11a037.js";import{r as g,_ as m}from"./index-7abb690d.js";import{o as _,T as p,S as u,a as c,P as f,W as r,X as h,$ as b}from"./@vue-5441e37d.js";let w={messageList:(e,l)=>g._post("/shop/setting.message/index",e,l),fieldList:(e,l)=>g._post("/shop/setting.message/field",e,l),saveSettings:(e,l)=>g._post("/shop/setting.message/saveSettings",e,l),updateSettingsStatus:(e,l)=>g._post("/shop/setting.message/updateSettingsStatus",e,l)};const v={data:()=>({formLabelWidth:"120px",dialogVisible:!1,loading:!1,isupload:!1,fieldList:[],title:"设置小程序消息订阅模板",checkList:[],settings:{},template_id:"",msg:"3、变量填写、例如{{thing4.DATA}}，只需要填写thing4。"}),props:["open_wx","messageModel"],created(){this.dialogVisible=this.open_wx,this.title=this.title+"("+this.messageModel.message_name+")",this.getData()},methods:{getData:function(){let e=this;e.loading=!0,w.fieldList({message_id:e.messageModel.message_id,message_type:"wx"},!0).then((l=>{l.data.list.forEach((function(e){e.field_new_ename=e.field_ename,e.filed_new_value=e.filed_value})),e.fieldList=l.data.list,null==l.data.settings||0==l.data.settings.length?(e.settings={},e.template_id=""):(e.settings=l.data.settings,e.template_id=l.data.settings.template_id),e.loading=!1,e.$nextTick((function(){e.initChecked()}))})).catch((e=>{}))},saveTemplate(){let l=this;l.loading=!0,w.saveSettings({fieldList:l.checkList,message_id:l.messageModel.message_id,message_type:"wx",template_id:l.template_id}).then((t=>{l.loading=!1,e({message:"保存成功",type:"success"}),l.dialogFormVisible(!0)})).catch((e=>{l.loading=!1}))},dialogFormVisible(e){e?this.$emit("closeDialog",{type:"success",openDialog:!1}):this.$emit("closeDialog",{type:"error",openDialog:!1})},handleSelectionChange(e){this.checkList=e},initChecked:function(){let e=this;"{}"!=JSON.stringify(e.settings)&&Object.keys(e.settings.var_data).forEach((function(l){e.fieldList.forEach((function(t){t.field_ename==l&&(e.$refs.fieldTable.toggleRowSelection(t,!0),t.field_new_ename=e.settings.var_data[l].field_name,t.filed_new_value=e.settings.var_data[l].filed_value)}))}))}}},V={class:"table-wrap"},S={class:"operation-wrap"},C=c("p",null," 配置说明：",-1),k=c("p",null," 1、微信小程序订阅模板里有的字段才勾选，如果没有请勿勾选。",-1),x=c("p",null," 2、模板变量替换成微信小程序订阅模板里的字段。",-1),y=["textContent"],L={class:"dialog-footer"};const T=m(v,[["render",function(e,g,m,w,v,T){const D=l,j=t,M=a,U=s,z=i,$=o,E=d,F=n;return _(),p(E,{title:v.title,modelValue:v.dialogVisible,"onUpdate:modelValue":g[1]||(g[1]=e=>v.dialogVisible=e),onClose:T.dialogFormVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},{footer:u((()=>[c("div",L,[f($,{onClick:T.dialogFormVisible},{default:u((()=>[r("取 消")])),_:1},8,["onClick"]),f($,{type:"primary",onClick:T.saveTemplate,loading:v.loading},{default:u((()=>[r("确 定")])),_:1},8,["onClick","loading"])])])),default:u((()=>[f(z,{size:"small"},{default:u((()=>[c("div",V,[c("div",S,[C,k,x,c("p",null,h(v.msg),1)]),c("div",null,[f(j,{label:"模板id："},{default:u((()=>[f(D,{size:"small",class:"max-w460",modelValue:v.template_id,"onUpdate:modelValue":g[0]||(g[0]=e=>v.template_id=e),placeholder:"请填写申请的微信小程序订阅模板消息idid"},null,8,["modelValue"])])),_:1})]),b((_(),p(U,{border:"",ref:"fieldTable",data:v.fieldList,onSelectionChange:T.handleSelectionChange},{default:u((()=>[f(M,{type:"selection",width:"55"}),f(M,{label:"字段名称"},{default:u((e=>[c("label",{textContent:h(e.row.field_name)},null,8,y)])),_:1}),f(M,{label:"模板变量名"},{default:u((e=>[f(D,{size:"small",prop:"field_new_ename",modelValue:e.row.field_new_ename,"onUpdate:modelValue":l=>e.row.field_new_ename=l},null,8,["modelValue","onUpdate:modelValue"])])),_:1}),f(M,{label:"模板内容"},{default:u((e=>[f(D,{size:"small",prop:"filed_new_value",disabled:1===e.row.is_var,modelValue:e.row.filed_new_value,"onUpdate:modelValue":l=>e.row.filed_new_value=l},null,8,["disabled","modelValue","onUpdate:modelValue"])])),_:1})])),_:1},8,["data","onSelectionChange"])),[[F,v.loading]])])])),_:1})])),_:1},8,["title","modelValue","onClose"])}]]),D=Object.freeze(Object.defineProperty({__proto__:null,default:T},Symbol.toStringTag,{value:"Module"}));export{w as M,T as W,D as a};
