import{E as e,c as o,d as i,b as l,e as s,p as t}from"./element-plus-0b11a037.js";import{P as r}from"./product-6e09d9a6.js";import{_ as a}from"./Upload-700ab3cd.js";import{_ as m}from"./index-ece0f3b6.js";import{o as d,T as p,S as f,a as n,P as u,W as c}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const h={class:"dialog-footer"};const b=m({components:{Upload:a},data:()=>({form:{feed_id:"",feed_name:"",sort:100,price:"",feed_value:[]},formRules:{feed_name:[{required:!0,message:"请输入加料名称",trigger:"blur"}],price:[{required:!0,message:"请输入价格",trigger:"blur"}],sort:[{required:!0,message:"分类排序不能为空"},{type:"number",message:"分类排序必须为数字"}]},formLabelWidth:"120px",dialogVisible:!1,loading:!1,isupload:!1}),props:["open_edit","editform"],created(){this.dialogVisible=this.open_edit,this.form.feed_id=this.editform.feed_id,this.form.feed_name=this.editform.feed_name,this.form.price=this.editform.price,this.form.sort=this.editform.sort},methods:{addvalue(){this.form.feed_value.push("")},deleteattr(e){this.form.feed_value.splice(e,1)},submit(){let o=this,i=o.form;o.$refs.form.validate((l=>{l&&(o.loading=!0,r.editFeed(i).then((i=>{o.loading=!1,e({message:"修改成功",type:"success"}),o.dialogFormVisible(!0)})).catch((e=>{o.loading=!1})))}))},dialogFormVisible(e){e?this.$emit("closeDialog",{type:"success",openDialog:!1}):this.$emit("closeDialog",{type:"error",openDialog:!1})}}},[["render",function(e,r,a,m,b,g){const j=o,_=i,V=l,v=s,y=t;return d(),p(y,{title:"添加加料",modelValue:b.dialogVisible,"onUpdate:modelValue":r[3]||(r[3]=e=>b.dialogVisible=e),onClose:g.dialogFormVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},{footer:f((()=>[n("div",h,[u(v,{onClick:g.dialogFormVisible},{default:f((()=>[c("取 消")])),_:1},8,["onClick"]),u(v,{type:"primary",onClick:g.submit,loading:b.loading},{default:f((()=>[c("确 定")])),_:1},8,["onClick","loading"])])])),default:f((()=>[u(V,{size:"small",model:b.form,rules:b.formRules,ref:"form"},{default:f((()=>[u(_,{label:"排序",prop:"sort","label-width":b.formLabelWidth},{default:f((()=>[u(j,{type:"text",modelValue:b.form.sort,"onUpdate:modelValue":r[0]||(r[0]=e=>b.form.sort=e),modelModifiers:{number:!0}},null,8,["modelValue"])])),_:1},8,["label-width"]),u(_,{label:"加料名称",prop:"feed_name","label-width":b.formLabelWidth},{default:f((()=>[u(j,{type:"text",modelValue:b.form.feed_name,"onUpdate:modelValue":r[1]||(r[1]=e=>b.form.feed_name=e)},null,8,["modelValue"])])),_:1},8,["label-width"]),u(_,{label:"价格",prop:"price","label-width":b.formLabelWidth},{default:f((()=>[u(j,{type:"number",modelValue:b.form.price,"onUpdate:modelValue":r[2]||(r[2]=e=>b.form.price=e)},null,8,["modelValue"])])),_:1},8,["label-width"])])),_:1},8,["model","rules"])])),_:1},8,["modelValue","onClose"])}]]);export{b as default};
