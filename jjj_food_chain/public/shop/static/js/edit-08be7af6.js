import{E as e,c as o,d as s,b as i,e as l,p as t}from"./element-plus-0b11a037.js";import{P as a}from"./product-6e09d9a6.js";import{_ as r}from"./Upload-700ab3cd.js";import{_ as m}from"./index-ece0f3b6.js";import{o as d,T as p,S as n,a as c,P as u,W as f}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const h={class:"dialog-footer"};const j=m({components:{Upload:r},data:()=>({form:{spec_id:"",spec_name:"",sort:100,spec_value:[]},formRules:{spec_name:[{required:!0,message:"请输入规格名称",trigger:"blur"}],sort:[{required:!0,message:"分类排序不能为空"},{type:"number",message:"分类排序必须为数字"}]},formLabelWidth:"120px",dialogVisible:!1,loading:!1,isupload:!1}),props:["open_edit","editform"],created(){this.dialogVisible=this.open_edit,this.form.spec_id=this.editform.spec_id,this.form.spec_name=this.editform.spec_name,this.form.sort=this.editform.sort},methods:{addvalue(){this.form.spec_value.push("")},deleteattr(e){this.form.spec_value.splice(e,1)},submit(){let o=this,s=o.form;o.$refs.form.validate((i=>{i&&(o.loading=!0,a.editSpec(s).then((s=>{o.loading=!1,e({message:"修改成功",type:"success"}),o.dialogFormVisible(!0)})).catch((e=>{o.loading=!1})))}))},dialogFormVisible(e){e?this.$emit("closeDialog",{type:"success",openDialog:!1}):this.$emit("closeDialog",{type:"error",openDialog:!1})}}},[["render",function(e,a,r,m,j,b){const g=o,_=s,V=i,v=l,y=t;return d(),p(y,{title:"添加规格",modelValue:j.dialogVisible,"onUpdate:modelValue":a[2]||(a[2]=e=>j.dialogVisible=e),onClose:b.dialogFormVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},{footer:n((()=>[c("div",h,[u(v,{onClick:b.dialogFormVisible},{default:n((()=>[f("取 消")])),_:1},8,["onClick"]),u(v,{type:"primary",onClick:b.submit,loading:j.loading},{default:n((()=>[f("确 定")])),_:1},8,["onClick","loading"])])])),default:n((()=>[u(V,{size:"small",model:j.form,rules:j.formRules,ref:"form"},{default:n((()=>[u(_,{label:"排序",prop:"sort","label-width":j.formLabelWidth},{default:n((()=>[u(g,{modelValue:j.form.sort,"onUpdate:modelValue":a[0]||(a[0]=e=>j.form.sort=e),modelModifiers:{number:!0},autocomplete:"off"},null,8,["modelValue"])])),_:1},8,["label-width"]),u(_,{label:"规格名称",prop:"spec_name","label-width":j.formLabelWidth},{default:n((()=>[u(g,{modelValue:j.form.spec_name,"onUpdate:modelValue":a[1]||(a[1]=e=>j.form.spec_name=e),autocomplete:"off"},null,8,["modelValue"])])),_:1},8,["label-width"])])),_:1},8,["model","rules"])])),_:1},8,["modelValue","onClose"])}]]);export{j as default};
