System.register(["./element-plus-legacy-9a8d946d.js","./index-legacy-e51438e5.js","./@vue-legacy-74f2101e.js","./@vueuse-legacy-3cbb5e93.js","./lodash-es-legacy-3f73cadd.js","./async-validator-legacy-aa1fd2de.js","./@element-plus-legacy-f0b22bff.js","./dayjs-legacy-4b173016.js","./call-bind-legacy-a7650b66.js","./get-intrinsic-legacy-675d089b.js","./has-symbols-legacy-afcc0593.js","./has-proto-legacy-d280ab1e.js","./function-bind-legacy-b76e1749.js","./has-legacy-06d86c07.js","./escape-html-legacy-ae962a8c.js","./normalize-wheel-es-legacy-13d39621.js","./@ctrl-legacy-33dbca08.js","./vue-router-legacy-d0b0f0e5.js","./pinia-legacy-b10a086a.js","./vue-demi-legacy-97cfbb01.js","./axios-legacy-40880ebd.js","./qs-legacy-a87a5df5.js","./side-channel-legacy-9446be19.js","./object-inspect-legacy-b9938498.js","./vue-ueditor-wrap-legacy-087787de.js"],(function(e,t){"use strict";var n,l,a,r,o,u,c,d,s,i,p,m,f,g,y,_,h,b,k,v,w,x,V,j=document.createElement("style");return j.textContent=".spec-many-type[data-v-d3a0a2d8]{margin-left:180px;margin-top:16px;padding:20px;border:1px solid #e5ecf4;background:#f6f9fc}.spec-wrap .spec-hd[data-v-d3a0a2d8]{padding:10px;display:flex;justify-content:space-between;align-items:center;background:#fff;font-weight:700}.spec-wrap .spec-hd .el-icon-delete-solid[data-v-d3a0a2d8]{font-size:16px;color:#999}.spec-wrap .min-spc[data-v-d3a0a2d8]{border:1px solid #dfecf8}.spec-wrap .spec-bd[data-v-d3a0a2d8]{padding:5px;display:flex;justify-content:flex-start;flex-wrap:wrap;border-top:1px solid #dfecf8;background:#ffffff}.spec-wrap .spec-bd .el-tag[data-v-d3a0a2d8]{color:#333}.spec-wrap .spec-bd .item[data-v-d3a0a2d8]{position:relative;padding:5px}.spec-wrap .spec-bd .item input[data-v-d3a0a2d8]{padding-right:30px}.spec-wrap .spec-hd a[data-v-d3a0a2d8],.spec-wrap .spec-hd .svg-icon[data-v-d3a0a2d8],.spec-wrap .spec-bd .item .svg-icon[data-v-d3a0a2d8]{display:block;width:16px;height:16px}.spec-wrap .spec-bd .item a[data-v-d3a0a2d8]{position:absolute;top:6px;right:5px;width:30px;height:30px;display:flex;justify-content:center;align-items:center}.add-spec .from-box[data-v-d3a0a2d8]{display:flex;justify-content:flex-start}.add-spec .item[data-v-d3a0a2d8]{display:flex;justify-content:flex-start;align-items:center;width:200px;margin-right:20px}.add-spec .item .key[data-v-d3a0a2d8]{display:block;white-space:nowrap}\n",document.head.appendChild(j),{setters:[function(e){n=e.c,l=e.d,a=e.n,r=e.E,o=e.e,u=e.F,c=e.k,d=e.h,s=e.l,i=e.f},function(e){p=e._},function(e){m=e.o,f=e.c,g=e.P,y=e.S,_=e.a,h=e.W,b=e.Y,k=e.T,v=e.aj,w=e.Q,x=e.a8,V=e.X},null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null],execute:function(){var t={class:"spec-many-type"},j={class:"spec-wrap"},S={key:0},U={class:"mt16"},C={components:{Single:p({inject:["form"]},[["render",function(e,t,a,r,o,u){var c=n,d=l;return m(),f("div",null,[g(d,{label:"产品价格：",width:"80",rules:[{required:!0,message:"请填写产品价格"}],prop:"model.sku[0].product_price"},{default:y((function(){return[g(c,{type:"number",modelValue:u.form.model.sku[0].product_price,"onUpdate:modelValue":t[0]||(t[0]=function(e){return u.form.model.sku[0].product_price=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1}),g(d,{label:"包装费：",rules:[{required:!0,message:"请填写包装费"}],prop:"model.sku[0].bag_price"},{default:y((function(){return[g(c,{type:"number",modelValue:u.form.model.sku[0].bag_price,"onUpdate:modelValue":t[1]||(t[1]=function(e){return u.form.model.sku[0].bag_price=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1}),g(d,{label:"库存数量：",rules:[{required:!0,message:"请填写库存数量"}],prop:"model.sku[0].stock_num"},{default:y((function(){return[g(c,{type:"number",modelValue:u.form.model.sku[0].stock_num,"onUpdate:modelValue":t[2]||(t[2]=function(e){return u.form.model.sku[0].stock_num=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1})])}]]),Many:p({components:{Type:p({data:function(){return{showAddGroupBtn:!0,showAddGroupForm:!1,addGroupFrom:{specName:"",specValue:""},groupLoading:!1}},inject:["form"],created:function(){},methods:{onToggleAddGroupForm:function(){var e=this;Array.isArray(e.form.model.sku)||(e.form.model.sku=[]),e.form.model.sku.push({spec_name:"",product_price:"",bag_price:"",stock_num:"",cost_price:0,is_full:0})},onDeleteGroup:function(e){var t=this;a.confirm("删除后不可恢复，确认删除该记录吗?","提示",{type:"warning"}).then((function(){t.form.model.spec_many.spec_attr.splice(e,1),t.buildSkulist()}))},onDeleteValue:function(e,t){var n=this;n.form.isSpecLocked?r({message:"本商品正在参加活动，不能删除规格！",type:"warning"}):a.confirm("删除后不可恢复，确认删除该记录吗?","提示",{type:"warning"}).then((function(){n.form.model.spec_many.spec_attr[e].spec_items.splice(t,1),n.buildSkulist()}))}}},[["render",function(e,n,l,a,r,u){var c=o;return m(),f("div",t,[_("div",j,[u.form.isSpecLocked?b("",!0):(m(),f("div",S,[g(c,{size:"small",class:"el-icon-circle-plus",onClick:u.onToggleAddGroupForm},{default:y((function(){return[h("添加规格 ")]})),_:1},8,["onClick"])]))])])}],["__scopeId","data-v-d3a0a2d8"]]),Table:p({components:{},data:function(){return{restaurants:[],formData:{},batchData:{product_price:"",line_price:"",stock_num:"",product_weight:""},isupload:!1,spec_index:-1}},inject:["form"],created:function(){this.formData=this.form},mounted:function(){},methods:{deleteAttr:function(e){this.form.model.sku.splice(e,1)},querySearch:function(e,t){var n=this;0==this.restaurants.length&&this.formData.spec.forEach((function(e,t){n.restaurants.push({value:e.spec_name})}));var l=this.restaurants;t(e?l.filter(this.createFilter(e)):l)},createFilter:function(e){return function(t){return 0===t.value.toLowerCase().indexOf(e.toLowerCase())}}}},[["render",function(e,t,a,r,s,i){var p=u,_=l,v=c,w=n,x=o,V=d;return m(),f("div",U,[i.form.model.sku.length>0?(m(),k(_,{key:0,label:"规格明细："},{default:y((function(){return[g(V,{size:"mini",data:i.form.model.sku,border:"",style:{width:"100%","margin-top":"20px"}},{default:y((function(){return[g(v,{label:"规格名称"},{default:y((function(e){return[g(_,{label:"",style:{"margin-bottom":"0"}},{default:y((function(){return[g(p,{class:"inline-input",modelValue:e.row.spec_name,"onUpdate:modelValue":function(t){return e.row.spec_name=t},"fetch-suggestions":i.querySearch,placeholder:"请输入内容"},null,8,["modelValue","onUpdate:modelValue","fetch-suggestions"])]})),_:2},1024)]})),_:1}),g(v,{label:"价格"},{default:y((function(e){return[g(_,{label:"",style:{"margin-bottom":"0"}},{default:y((function(){return[g(w,{type:"number",size:"small",prop:"product_price",modelValue:e.row.product_price,"onUpdate:modelValue":function(t){return e.row.product_price=t}},null,8,["modelValue","onUpdate:modelValue"])]})),_:2},1024)]})),_:1}),g(v,{label:"包装费"},{default:y((function(e){return[g(_,{label:"",style:{"margin-bottom":"0"}},{default:y((function(){return[g(w,{type:"number",size:"small",prop:"bag_price",modelValue:e.row.bag_price,"onUpdate:modelValue":function(t){return e.row.bag_price=t}},null,8,["modelValue","onUpdate:modelValue"])]})),_:2},1024)]})),_:1}),g(v,{label:"库存"},{default:y((function(e){return[g(_,{label:"",style:{"margin-bottom":"0"}},{default:y((function(){return[g(w,{type:"number",size:"small",prop:"stock_num",modelValue:e.row.stock_num,"onUpdate:modelValue":function(t){return e.row.stock_num=t}},null,8,["modelValue","onUpdate:modelValue"])]})),_:2},1024)]})),_:1}),g(v,{label:""},{default:y((function(e){return[g(_,{label:"",style:{"margin-bottom":"0"}},{default:y((function(){return[g(x,{type:"text",onClick:function(t){return i.deleteAttr(e.$index)}},{default:y((function(){return[h("删除")]})),_:2},1032,["onClick"])]})),_:2},1024)]})),_:1})]})),_:1},8,["data"])]})),_:1})):b("",!0)])}]])}},[["render",function(e,t,n,l,a,r){var o=v("Type"),u=v("Table");return m(),f("div",null,[g(o),g(u)])}]])},data:function(){return{restaurants:[]}},inject:["form"],methods:{changeSpec:function(e){10==e?(this.form.sku=[],this.form.sku[0]={product_price:"",stock_num:"",bag_price:"",cost_price:0,is_full:0}):20==e&&(this.form.sku=[])},querySearch:function(e,t){var n=this;0==n.restaurants.length&&n.form.unit.forEach((function(e,t){n.restaurants.push({value:e.unit_name})}));var l=this.restaurants;t(e?l.filter(this.createFilter(e)):l)},createFilter:function(e){return function(t){return 0===t.value.toLowerCase().indexOf(e.toLowerCase())}}}},L=_("div",{class:"common-form mt50"},"规格/库存",-1),q={key:0,class:"red"};e("default",p(C,[["render",function(e,t,n,a,r,o){var c=s,d=i,p=l,_=u,j=v("Single"),S=v("Many");return m(),f("div",null,[L,g(p,{label:"库存计算方式："},{default:y((function(){return[g(d,{modelValue:o.form.model.deduct_stock_type,"onUpdate:modelValue":t[0]||(t[0]=function(e){return o.form.model.deduct_stock_type=e})},{default:y((function(){return[g(c,{label:10},{default:y((function(){return[h("下单减库存")]})),_:1}),g(c,{label:20},{default:y((function(){return[h("付款减库存")]})),_:1})]})),_:1},8,["modelValue"])]})),_:1}),g(p,{label:"特色分类："},{default:y((function(){return[g(d,{modelValue:o.form.model.special_id,"onUpdate:modelValue":t[1]||(t[1]=function(e){return o.form.model.special_id=e})},{default:y((function(){return[g(c,{label:0},{default:y((function(){return[h("无")]})),_:1}),(m(!0),f(w,null,x(o.form.special,(function(e,t){return m(),k(c,{label:e.category_id,key:t},{default:y((function(){return[h(V(e.name),1)]})),_:2},1032,["label"])})),128))]})),_:1},8,["modelValue"])]})),_:1}),g(p,{label:"商品单位：",rules:[{required:!0,message:"请填写商品单位"}],prop:"model.product_unit"},{default:y((function(){return[g(_,{class:"inline-input",modelValue:o.form.model.product_unit,"onUpdate:modelValue":t[2]||(t[2]=function(e){return o.form.model.product_unit=e}),placeholder:"如:大份","fetch-suggestions":o.querySearch},null,8,["modelValue","fetch-suggestions"])]})),_:1}),g(p,{label:"产品规格："},{default:y((function(){return[g(d,{modelValue:o.form.model.spec_type,"onUpdate:modelValue":t[3]||(t[3]=function(e){return o.form.model.spec_type=e}),onChange:o.changeSpec},{default:y((function(){return[!o.form.isSpecLocked||o.form.isSpecLocked&&10==o.form.model.spec_type?(m(),k(c,{key:0,label:10},{default:y((function(){return[h("单规格")]})),_:1})):b("",!0),!o.form.isSpecLocked||o.form.isSpecLocked&&20==o.form.model.spec_type?(m(),k(c,{key:1,label:20},{default:y((function(){return[h("多规格")]})),_:1})):b("",!0)]})),_:1},8,["modelValue","onChange"]),o.form.isSpecLocked?(m(),f("div",q,"此商品正在参加活动，不能修改规格")):b("",!0)]})),_:1}),10==o.form.model.spec_type?(m(),k(j,{key:0})):b("",!0),20==o.form.model.spec_type?(m(),k(S,{key:1})):b("",!0)])}]]))}}}));
