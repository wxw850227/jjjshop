System.register(["./Upload-legacy-9c715279.js","./element-plus-legacy-9a8d946d.js","./vuedraggable-legacy-a2e73a2e.js","./@vue-legacy-74f2101e.js","./index-legacy-e51438e5.js","./@vueuse-legacy-3cbb5e93.js","./lodash-es-legacy-3f73cadd.js","./async-validator-legacy-aa1fd2de.js","./@element-plus-legacy-f0b22bff.js","./dayjs-legacy-4b173016.js","./call-bind-legacy-a7650b66.js","./get-intrinsic-legacy-675d089b.js","./has-symbols-legacy-afcc0593.js","./has-proto-legacy-d280ab1e.js","./function-bind-legacy-b76e1749.js","./has-legacy-06d86c07.js","./escape-html-legacy-ae962a8c.js","./normalize-wheel-es-legacy-13d39621.js","./@ctrl-legacy-33dbca08.js","./vue-legacy-923b5a9f.js","./sortablejs-legacy-5ac11355.js","./vue-router-legacy-d0b0f0e5.js","./pinia-legacy-b10a086a.js","./vue-demi-legacy-97cfbb01.js","./axios-legacy-40880ebd.js","./qs-legacy-a87a5df5.js","./side-channel-legacy-9446be19.js","./object-inspect-legacy-b9938498.js","./vue-ueditor-wrap-legacy-087787de.js"],(function(e,l){"use strict";var n,a,t,u,o,r,c,s,i,d,m,g,f,p,y,j,_,v,h,b,U,P,V;return{setters:[function(e){n=e._},function(e){a=e.c,t=e.d,u=e.s,o=e.t,r=e.j},function(e){c=e.d},function(e){s=e.aj,i=e.ak,d=e.o,m=e.c,g=e.P,f=e.S,p=e.a,y=e.Q,j=e.a8,_=e.T,v=e.Y,h=e.Z,b=e.$,U=e.a1,P=e.W},function(e){V=e._},null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null],execute:function(){var l={components:{Upload:n,draggable:c},data:function(){return{formData:{},isProductUpload:!1}},inject:["form"],created:function(){this.formData=this.form},methods:{openProductUpload:function(){this.isProductUpload=!0},returnProductImgsFunc:function(e){if(null!=e){var l=this.form.model.image.concat(e);this.form.model.image=l}this.isProductUpload=!1},deleteImg:function(e){this.form.model.image.splice(e,1)}}},k={class:"basic-setting-content pl16 pr16"},x=p("div",{class:"common-form"},"基本信息",-1),w={class:"draggable-list"},I=["onClick"];e("default",V(l,[["render",function(e,l,c,V,q,C){var D=a,F=t,R=u,S=o,z=s("Close"),K=r,Q=s("draggable"),T=s("Plus"),W=n,Y=i("img-url");return d(),m("div",k,[x,g(F,{label:"商品名称：",rules:[{required:!0,message:"请填写商品名称"}],prop:"model.product_name"},{default:f((function(){return[g(D,{modelValue:C.form.model.product_name,"onUpdate:modelValue":l[0]||(l[0]=function(e){return C.form.model.product_name=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1}),g(F,{label:"所属分类：",rules:[{required:!0,message:"请选择商品分类"}],prop:"model.category_id"},{default:f((function(){return[g(S,{modelValue:C.form.model.category_id,"onUpdate:modelValue":l[1]||(l[1]=function(e){return C.form.model.category_id=e})},{default:f((function(){return[p("template",null,[q.formData.category.length>0?(d(!0),m(y,{key:0},j(q.formData.category,(function(e){return d(),_(R,{value:e.category_id,key:e.category_id,label:e.name},null,8,["value","label"])})),128)):v("",!0)])]})),_:1},8,["modelValue"])]})),_:1}),g(F,{label:"商品图片：",rules:[{required:!0,message:"请上传商品图片"}],prop:"model.image"},{default:f((function(){return[p("div",w,[g(Q,{class:"wrapper",modelValue:C.form.model.image,"onUpdate:modelValue":l[2]||(l[2]=function(e){return C.form.model.image=e})},{default:f((function(){return[g(h,null,{default:f((function(){return[(d(!0),m(y,null,j(C.form.model.image,(function(e,l){return d(),m("div",{class:"item",key:e.file_path},[b(p("img",null,null,512),[[Y,e.file_path]]),p("a",{href:"javascript:void(0);",class:"delete-btn",onClick:U((function(e){return C.deleteImg(l)}),["stop"])},[g(K,null,{default:f((function(){return[g(z)]})),_:1})],8,I)])})),128))]})),_:1})]})),_:1},8,["modelValue"]),p("div",{class:"item img-select",onClick:l[3]||(l[3]=function(){return C.openProductUpload&&C.openProductUpload.apply(C,arguments)})},[g(K,null,{default:f((function(){return[g(T)]})),_:1})])])]})),_:1}),g(F,{label:"商品卖点："},{default:f((function(){return[g(D,{type:"textarea",modelValue:C.form.model.selling_point,"onUpdate:modelValue":l[4]||(l[4]=function(e){return C.form.model.selling_point=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1}),q.isProductUpload?(d(),_(W,{key:0,config:{total:9},isupload:q.isProductUpload,onReturnImgs:C.returnProductImgsFunc},{default:f((function(){return[P("上传图片")]})),_:1},8,["isupload","onReturnImgs"])):v("",!0)])}]]))}}}));
