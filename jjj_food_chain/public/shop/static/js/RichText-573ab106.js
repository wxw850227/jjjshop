import{B as t,d as e,A as s,e as o,b as a}from"./element-plus-0b11a037.js";import{U as n}from"./UE-01b4bf17.js";import{aj as l,o as i,c as r,a as m,X as p,P as d,S as u,a1 as c,W as j}from"./@vue-5441e37d.js";import{_ as f}from"./index-ece0f3b6.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./Upload-700ab3cd.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const h={class:"common-form"},g={class:"d-s-c"};const I=f({components:{Uediter:n},data:()=>({ueditor:{text:"",config:{initialFrameWidth:400,initialFrameHeight:500}},content:""}),props:["curItem","selectedIndex","opts"],watch:{},created(){this.curItem.style.paddingTop=parseInt(this.curItem.style.paddingTop),this.curItem.style.paddingLeft=parseInt(this.curItem.style.paddingLeft),this.content=this.curItem.params.content},methods:{contentChangeFunc(t){this.content=t,this.curItem.params.content=t}}},[["render",function(n,f,I,y,b,x){const v=t,C=e,V=s,U=o,w=l("Uediter"),_=a;return i(),r("div",null,[m("div",h,[m("span",null,p(I.curItem.name),1)]),d(_,{size:"small",model:I.curItem,"label-width":"100px"},{default:u((()=>[d(C,{label:"上下边距："},{default:u((()=>[d(v,{modelValue:I.curItem.style.paddingTop,"onUpdate:modelValue":f[0]||(f[0]=t=>I.curItem.style.paddingTop=t),"show-input":""},null,8,["modelValue"])])),_:1}),d(C,{label:"左右边距："},{default:u((()=>[d(v,{modelValue:I.curItem.style.paddingLeft,"onUpdate:modelValue":f[1]||(f[1]=t=>I.curItem.style.paddingLeft=t),"show-input":""},null,8,["modelValue"])])),_:1}),d(C,{label:"背景颜色："},{default:u((()=>[m("div",g,[d(V,{modelValue:I.curItem.style.background,"onUpdate:modelValue":f[2]||(f[2]=t=>I.curItem.style.background=t)},null,8,["modelValue"]),d(U,{type:"button",style:{"margin-left":"10px"},onClick:f[3]||(f[3]=c((t=>n.$parent.onEditorResetColor(I.curItem.style,"btnColor","#ffffff")),["stop"]))},{default:u((()=>[j("重置")])),_:1})])])),_:1}),d(C,{label:"内容："},{default:u((()=>[d(w,{text:b.content,config:b.ueditor.config,onContentChange:x.contentChangeFunc,ref:"ue"},null,8,["text","config","onContentChange"])])),_:1})])),_:1},8,["model"])])}]]);export{I as default};
