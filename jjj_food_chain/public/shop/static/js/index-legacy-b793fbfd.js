System.register(["./element-plus-legacy-9a8d946d.js","./store-legacy-79d6ddec.js","./add-legacy-8fb18573.js","./edit-legacy-ec287c31.js","./index-legacy-b686ba8a.js","./@vue-legacy-74f2101e.js","./@vueuse-legacy-3cbb5e93.js","./lodash-es-legacy-3f73cadd.js","./async-validator-legacy-aa1fd2de.js","./@element-plus-legacy-f0b22bff.js","./dayjs-legacy-4b173016.js","./call-bind-legacy-a7650b66.js","./get-intrinsic-legacy-675d089b.js","./has-symbols-legacy-afcc0593.js","./has-proto-legacy-d280ab1e.js","./function-bind-legacy-b76e1749.js","./has-legacy-06d86c07.js","./escape-html-legacy-ae962a8c.js","./normalize-wheel-es-legacy-13d39621.js","./@ctrl-legacy-33dbca08.js","./vue-router-legacy-d0b0f0e5.js","./pinia-legacy-b10a086a.js","./vue-demi-legacy-97cfbb01.js","./axios-legacy-40880ebd.js","./qs-legacy-a87a5df5.js","./side-channel-legacy-9446be19.js","./object-inspect-legacy-b9938498.js","./vue-ueditor-wrap-legacy-087787de.js"],(function(e,t){"use strict";var l,a,n,i,o,c,s,d,u,r,g,y,f,p,j,h,m,_,k,v,b;return{setters:[function(e){l=e.n,a=e.E,n=e.e,i=e.k,o=e.h,c=e.v},function(e){s=e.S},function(e){d=e.default},function(e){u=e.default},function(e){r=e._},function(e){g=e.aj,y=e.ak,f=e.o,p=e.c,j=e.a,h=e.$,m=e.T,_=e.S,k=e.W,v=e.P,b=e.Y},null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null],execute:function(){var t={class:"product"},D={class:"common-level-rail"},C={class:"product-content"},w={class:"table-wrap"};e("default",r({components:{Add:d,Edit:u},data:function(){return{loading:!0,activeName:"first",tableData:[],open_add:!1,open_edit:!1,categoryModel:{model:""}}},created:function(){this.getData()},methods:{handleClick:function(){this.getData()},getData:function(){var e=this;e.loading=!0,s.arealist({},!0).then((function(t){e.loading=!1,e.tableData=t.data.list.data,e.categoryModel=t.data.list.data})).catch((function(t){e.loading=!1}))},addClick:function(){this.open_add=!0},editClick:function(e){this.categoryModel.model=e,this.open_edit=!0},closeDialogFunc:function(e,t){"add"==t&&(this.open_add=e.openDialog,"success"==e.type&&this.getData()),"edit"==t&&(this.open_edit=e.openDialog,"success"==e.type&&this.getData())},deleteClick:function(e){var t=this;l.confirm("删除后不可恢复，确认删除该记录吗?","提示",{type:"warning"}).then((function(){s.deleteArea({area_id:e.area_id}).then((function(e){a({message:"删除成功",type:"success"}),t.getData()}))}))}}},[["render",function(e,l,a,s,d,u){var r=n,x=i,z=o,M=g("Add"),A=g("Edit"),E=y("auth"),F=c;return f(),p("div",t,[j("div",D,[h((f(),m(r,{size:"small",type:"primary",onClick:u.addClick,icon:"Plus"},{default:_((function(){return[k("添加区域")]})),_:1},8,["onClick"])),[[E,"/store/table/area/add"]])]),j("div",C,[j("div",w,[h((f(),m(z,{size:"small",data:d.tableData,"row-key":"category_id",style:{width:"100%"}},{default:_((function(){return[v(x,{prop:"sort",label:"排序"}),v(x,{prop:"area_name",label:"区域名称",width:"180"}),v(x,{prop:"create_time",label:"添加时间"}),v(x,{fixed:"right",label:"操作",width:"100"},{default:_((function(e){return[h((f(),m(r,{onClick:function(t){return u.editClick(e.row)},type:"text",size:"small"},{default:_((function(){return[k("编辑")]})),_:2},1032,["onClick"])),[[E,"/store/table/table/edit"]]),h((f(),m(r,{onClick:function(t){return u.deleteClick(e.row)},type:"text",size:"small"},{default:_((function(){return[k("删除")]})),_:2},1032,["onClick"])),[[E,"/store/table/area/delete"]])]})),_:1})]})),_:1},8,["data"])),[[F,d.loading]])])]),d.open_add?(f(),m(M,{key:0,open_add:d.open_add,addform:d.categoryModel,onCloseDialog:l[0]||(l[0]=function(e){return u.closeDialogFunc(e,"add")})},null,8,["open_add","addform"])):b("",!0),d.open_edit?(f(),m(A,{key:1,open_edit:d.open_edit,editform:d.categoryModel,onCloseDialog:l[1]||(l[1]=function(e){return u.closeDialogFunc(e,"edit")})},null,8,["open_edit","editform"])):b("",!0)])}]]))}}}));
