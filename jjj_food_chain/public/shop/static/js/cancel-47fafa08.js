import{E as e,c as o,d as l,b as s,e as i,p as a}from"./element-plus-0b11a037.js";import{T as r}from"./takeout-ba57967c.js";import{d as t}from"./vuedraggable-fa449dda.js";import{_ as d}from"./index-7abb690d.js";import{o as m,c as n,P as p,S as c,a as u,W as j}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-ffc0216e.js";import"./sortablejs-88eb33dd.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const f={class:"dialog-footer"};const g=d({components:{draggable:t},data:()=>({loading:!1,formLabelWidth:"120px",dialogVisible:!1,form:{deliver_id:"",cancel_reason:"",order_no:""}}),props:["open_edit","deliver_id","order_no"],created(){this.dialogVisible=this.open_edit,this.form.deliver_id=this.deliver_id,this.form.order_no=this.order_no},methods:{submit(){let o=this,l=o.form;o.$refs.form.validate((s=>{s&&(o.loading=!0,r.cancel(l,!0).then((l=>{o.loading=!1,e({message:l.msg,type:"success"}),o.dialogFormVisible(!0)})).catch((e=>{o.loading=!1})))}))},dialogFormVisible(e){e?this.$emit("closeDialog",{type:"success",openDialog:!1}):this.$emit("closeDialog",{type:"error",openDialog:!1})}}},[["render",function(e,r,t,d,g,h){const b=o,_=l,V=s,v=i,y=a;return m(),n("div",null,[p(y,{title:"取消订单",modelValue:g.dialogVisible,"onUpdate:modelValue":r[2]||(r[2]=e=>g.dialogVisible=e),onClose:h.dialogFormVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},{footer:c((()=>[u("div",f,[p(v,{onClick:h.dialogFormVisible},{default:c((()=>[j("取 消")])),_:1},8,["onClick"]),p(v,{type:"primary",onClick:h.submit,loading:g.loading},{default:c((()=>[j("确 定")])),_:1},8,["onClick","loading"])])])),default:c((()=>[p(V,{size:"small",ref:"form",model:g.form},{default:c((()=>[p(_,{label:"订单号","label-width":g.formLabelWidth,prop:"order_no",rules:[{required:!0,message:" "}]},{default:c((()=>[p(b,{modelValue:g.form.order_no,"onUpdate:modelValue":r[0]||(r[0]=e=>g.form.order_no=e),placeholder:"请输入订单号",class:"max-w460",readonly:!0},null,8,["modelValue"])])),_:1},8,["label-width"]),p(_,{label:"取消原因","label-width":g.formLabelWidth,prop:"cancel_reason",rules:[{required:!0,message:" "}]},{default:c((()=>[p(b,{type:"textarea",modelValue:g.form.cancel_reason,"onUpdate:modelValue":r[1]||(r[1]=e=>g.form.cancel_reason=e),placeholder:"请输入取消原因"},null,8,["modelValue"])])),_:1},8,["label-width"])])),_:1},8,["model"])])),_:1},8,["modelValue","onClose"])])}]]);export{g as default};
