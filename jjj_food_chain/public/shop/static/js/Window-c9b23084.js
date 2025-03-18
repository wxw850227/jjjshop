import{_ as e}from"./Setlink-fdaac225.js";import{A as l,c as s,e as a,B as t,i,f as o,d,j as m,b as r}from"./element-plus-0b11a037.js";import{d as n}from"./vuedraggable-fa449dda.js";import{aj as u,ak as c,o as p,c as g,a as f,X as y,P as k,S as h,a1 as v,W as b,Y as j,T as I,$ as x}from"./@vue-5441e37d.js";import{_ as V}from"./index-7abb690d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-ffc0216e.js";import"./sortablejs-88eb33dd.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const _={components:{Setlink:e,draggable:n},data:()=>({is_linkset:!1,index:null}),created(){this.curItem.style.paddingTop=parseInt(this.curItem.style.paddingTop),this.curItem.style.paddingLeft=parseInt(this.curItem.style.paddingLeft)},props:["curItem","selectedIndex"],methods:{changeLink(e){this.is_linkset=!0,this.index=e},closeLinkset(e){this.is_linkset=!1,e&&(this.curItem.data[this.index].linkUrl=e.url,this.curItem.data[this.index].name="链接到 "+e.type+" "+e.name)}}},w={class:"common-form"},U=f("div",{class:"f16 gray3 form-subtitle"},"样式设置",-1),C={class:"form-item"},z=f("div",{class:"form-label"},"底部背景：",-1),L={class:"flex-1 d-s-c",style:{height:"36px"}},D={class:"form-item"},T=f("div",{class:"form-label"},"上边距：",-1),$={class:"form-item"},B=f("div",{class:"form-label"},"下边距：",-1),E={class:"form-item"},F=f("div",{class:"form-label"},"图片边距：",-1),S=f("div",{class:"form-chink"},null,-1),A=f("div",{class:"f16 gray3 form-subtitle"},"布局方式",-1),q={key:0,class:"red"},R=f("div",{class:"gray9"},"请确保所有图片的尺寸/比例相同。",-1),M=f("div",{class:"f16 gray3 form-subtitle",style:{"margin-bottom":"10px"}},[b(" 图片设置 "),f("span",{class:"gray9 f12"},"请确保所有图片的尺寸/比例相同；鼠标拖拽左侧圆点可调整导航顺序")],-1),P={class:"d-c-c param-img-item navbar"},W={class:"d-c d-c-c",style:{"margin-right":"28px"}},X={class:"icon"},Y=["onClick"],G={class:"right"},H={class:"url-box mb16 flex-1 d-s-c ww100"},J=f("span",{class:"key-name"},"名称",-1),K={class:"d-s-c ww100"},N={class:"url-box flex-1 d-s-c"},O=f("span",{class:"key-name"},"链接",-1),Q={class:"d-c-c pb16"};const Z=V(_,[["render",function(n,V,_,Z,ee,le){const se=l,ae=s,te=a,ie=t,oe=i,de=o,me=d,re=u("CloseBold"),ne=m,ue=u("ArrowRight"),ce=u("draggable"),pe=r,ge=e,fe=c("img-url");return p(),g("div",null,[f("div",w,[f("span",null,y(_.curItem.name),1)]),k(pe,{size:"small",model:_.curItem,"label-width":"100px"},{default:h((()=>[U,f("div",C,[z,f("div",L,[k(se,{size:"default",modelValue:_.curItem.style.background,"onUpdate:modelValue":V[0]||(V[0]=e=>_.curItem.style.background=e)},null,8,["modelValue"]),k(ae,{class:"ml10",modelValue:_.curItem.style.background,"onUpdate:modelValue":V[1]||(V[1]=e=>_.curItem.style.background=e)},null,8,["modelValue"]),k(te,{style:{"margin-left":"10px"},onClick:V[2]||(V[2]=v((e=>n.$parent.onEditorResetColor(_.curItem.style,"background","#F2F2F2")),["stop"])),type:"primary",link:""},{default:h((()=>[b("重置")])),_:1})])]),f("div",D,[T,k(ie,{modelValue:_.curItem.style.paddingTop,"onUpdate:modelValue":V[3]||(V[3]=e=>_.curItem.style.paddingTop=e),size:"small","show-input":"","show-input-controls":!1,"input-size":"small"},null,8,["modelValue"])]),f("div",$,[B,k(ie,{modelValue:_.curItem.style.paddingBottom,"onUpdate:modelValue":V[4]||(V[4]=e=>_.curItem.style.paddingBottom=e),size:"small","show-input":"","show-input-controls":!1,"input-size":"small"},null,8,["modelValue"])]),f("div",E,[F,k(ie,{modelValue:_.curItem.style.paddingLeft,"onUpdate:modelValue":V[5]||(V[5]=e=>_.curItem.style.paddingLeft=e),size:"small","show-input":"","show-input-controls":!1,"input-size":"small"},null,8,["modelValue"])]),S,A,k(me,{label:""},{default:h((()=>[k(de,{modelValue:_.curItem.style.layout,"onUpdate:modelValue":V[6]||(V[6]=e=>_.curItem.style.layout=e),size:"medium"},{default:h((()=>[k(oe,{label:2},{default:h((()=>[b("堆积两列")])),_:1}),k(oe,{label:3},{default:h((()=>[b("堆积三列")])),_:1}),k(oe,{label:4},{default:h((()=>[b("堆积四列")])),_:1}),k(oe,{label:-1},{default:h((()=>[b("橱窗样式")])),_:1})])),_:1},8,["modelValue"]),-1==_.curItem.style.layout?(p(),g("div",q,"橱窗样式最多显示四张图片，超出隐藏")):j("",!0),R])),_:1}),M,_.curItem.data&&_.curItem.data.length>0?(p(),I(ce,{key:0,modelValue:_.curItem.data,"onUpdate:modelValue":V[7]||(V[7]=e=>_.curItem.data=e),group:"people","item-key":"index",class:"draggable-list"},{item:h((({element:e,index:l})=>[f("div",P,[f("div",W,[f("div",X,[x(f("img",{alt:"",onClick:l=>n.$parent.onEditorSelectImage(e,"imgUrl")},null,8,Y),[[fe,e.imgUrl]])])]),f("div",G,[k(ne,{class:"el-icon-DeleteFilled",onClick:e=>n.$parent.onEditorDeleleData(l,_.selectedIndex)},{default:h((()=>[k(re)])),_:2},1032,["onClick"]),f("div",H,[J,k(ae,{maxlength:"6","show-word-limit":"",disabled:"",value:"图"+(l+1)},null,8,["value"])]),f("div",K,[f("div",N,[O,k(ae,{onClick:e=>le.changeLink(l),modelValue:e.linkUrl,"onUpdate:modelValue":l=>e.linkUrl=l},{suffix:h((()=>[k(ne,{color:"#333",size:"16px"},{default:h((()=>[k(ue)])),_:1})])),_:2},1032,["onClick","modelValue","onUpdate:modelValue"])])])])])])),_:1},8,["modelValue"])):j("",!0),f("div",Q,[k(te,{plain:"",type:"primary",onClick:n.$parent.onEditorAddData},{default:h((()=>[b("+添加一个")])),_:1},8,["onClick"])])])),_:1},8,["model"]),ee.is_linkset?(p(),I(ge,{key:0,is_linkset:ee.is_linkset,onCloseDialog:le.closeLinkset},{default:h((()=>[b("选择链接")])),_:1},8,["is_linkset","onCloseDialog"])):j("",!0)])}]]);export{Z as default};
