System.register(["./element-plus-legacy-9a8d946d.js","./setting-legacy-c18f3dbb.js","./index-legacy-e51438e5.js","./@vue-legacy-74f2101e.js","./@vueuse-legacy-3cbb5e93.js","./lodash-es-legacy-3f73cadd.js","./async-validator-legacy-aa1fd2de.js","./@element-plus-legacy-f0b22bff.js","./dayjs-legacy-4b173016.js","./call-bind-legacy-a7650b66.js","./get-intrinsic-legacy-675d089b.js","./has-symbols-legacy-afcc0593.js","./has-proto-legacy-d280ab1e.js","./function-bind-legacy-b76e1749.js","./has-legacy-06d86c07.js","./escape-html-legacy-ae962a8c.js","./normalize-wheel-es-legacy-13d39621.js","./@ctrl-legacy-33dbca08.js","./vue-router-legacy-d0b0f0e5.js","./pinia-legacy-b10a086a.js","./vue-demi-legacy-97cfbb01.js","./axios-legacy-40880ebd.js","./qs-legacy-a87a5df5.js","./side-channel-legacy-9446be19.js","./object-inspect-legacy-b9938498.js","./vue-ueditor-wrap-legacy-087787de.js"],(function(e,n){"use strict";var l,r,t,u,a,o,i,s,c,d,m,f,p,_,E,y,g,N,U,v,b,j=document.createElement("style");return j.textContent=".tips[data-v-1d16b990]{color:#ccc}\n",document.head.appendChild(j),{setters:[function(e){l=e.E,r=e.c,t=e.d,u=e.s,a=e.t,o=e.e,i=e.b},function(e){s=e.S},function(e){c=e._},function(e){d=e.o,m=e.c,f=e.P,p=e.S,_=e.Q,E=e.a8,y=e.T,g=e.Y,N=e.a,U=e.W,v=e.bb,b=e.b9},null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null],execute:function(){var n={data:function(){return{form:{printer_id:"",printer_name:"",printer_type:"",sort:1,print_times:"",FEI_E_YUN:{USER:"",UKEY:"",SN:""},PRINT_CENTER:{deviceNo:"",key:""}},loading:!1,type:[]}},created:function(){this.getData()},methods:{getData:function(){var e=this,n=this.$route.query.printer_id;s.printerDetail({printer_id:n},!0).then((function(n){var l=n.data.detail;e.type=n.data.printerType,e.form.printer_name=l.printer_name,e.form.printer_type=l.printer_type.value,e.form.sort=l.sort,e.form.printer_id=l.printer_id,e.form.print_times=l.print_times,"FEI_E_YUN"==l.printer_type.value&&(e.form.FEI_E_YUN.USER=l.printer_config.USER,e.form.FEI_E_YUN.UKEY=l.printer_config.UKEY,e.form.FEI_E_YUN.SN=l.printer_config.SN),"PRINT_CENTER"==l.printer_type.value&&(e.form.PRINT_CENTER.deviceNo=l.printer_config.deviceNo,e.form.PRINT_CENTER.key=l.printer_config.key)})).catch((function(e){}))},onSubmit:function(){var e=this,n=e.form;e.$refs.form.validate((function(r){r&&(e.loading=!0,s.editPrinter(n,!0).then((function(n){e.loading=!1,l({message:"恭喜你，修改成功",type:"success"}),e.$router.push("/setting/printer/index")})).catch((function(n){e.loading=!1})))}))}}},j=function(e){return v("data-v-1d16b990"),e=e(),b(),e},R={class:"product-add"},I=j((function(){return N("div",{class:"common-form"},"编辑小票打印机",-1)})),V=j((function(){return N("div",{class:"tips"},"目前支持 飞鹅打印机、365云打印",-1)})),T={key:0},Y=j((function(){return N("div",{class:"tips"},"飞鹅云后台注册用户名",-1)})),h=j((function(){return N("div",{class:"tips"},"飞鹅云后台登录生成的UKEY",-1)})),S=j((function(){return N("div",{class:"tips"},"打印机编号为9位数字，查看飞鹅打印机底部贴纸上面的编号",-1)})),C={key:1},F=j((function(){return N("div",{class:"tips"},"同一订单，打印的次数",-1)})),x=j((function(){return N("div",{class:"tips"},"数字越小越靠前",-1)})),P={class:"common-button-wrapper"};e("default",c(n,[["render",function(e,n,l,s,c,v){var b=r,j=t,k=u,w=a,q=o,K=i;return d(),m("div",R,[f(K,{size:"small",ref:"form",model:c.form,"label-width":"200px"},{default:p((function(){return[I,f(j,{label:"打印机名称 ",prop:"printer_name",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.printer_name,"onUpdate:modelValue":n[0]||(n[0]=function(e){return c.form.printer_name=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1}),f(j,{label:"打印机类型 "},{default:p((function(){return[f(w,{modelValue:c.form.printer_type,"onUpdate:modelValue":n[1]||(n[1]=function(e){return c.form.printer_type=e}),placeholder:"请选择"},{default:p((function(){return[(d(!0),m(_,null,E(c.type,(function(e,n){return d(),y(k,{key:n,label:e,value:n},null,8,["label","value"])})),128))]})),_:1},8,["modelValue"]),V]})),_:1}),"FEI_E_YUN"==c.form.printer_type?(d(),m("div",T,[f(j,{label:"USER",prop:"FEI_E_YUN.USER",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.FEI_E_YUN.USER,"onUpdate:modelValue":n[2]||(n[2]=function(e){return c.form.FEI_E_YUN.USER=e}),class:"max-w460"},null,8,["modelValue"]),Y]})),_:1}),f(j,{label:"UKEY",prop:"FEI_E_YUN.UKEY",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.FEI_E_YUN.UKEY,"onUpdate:modelValue":n[3]||(n[3]=function(e){return c.form.FEI_E_YUN.UKEY=e}),class:"max-w460"},null,8,["modelValue"]),h]})),_:1}),f(j,{label:"打印机编号",prop:"FEI_E_YUN.SN",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.FEI_E_YUN.SN,"onUpdate:modelValue":n[4]||(n[4]=function(e){return c.form.FEI_E_YUN.SN=e}),class:"max-w460"},null,8,["modelValue"]),S]})),_:1})])):g("",!0),"PRINT_CENTER"==c.form.printer_type?(d(),m("div",C,[f(j,{label:"打印机编号 ",prop:"PRINT_CENTER.deviceNo",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.PRINT_CENTER.deviceNo,"onUpdate:modelValue":n[5]||(n[5]=function(e){return c.form.PRINT_CENTER.deviceNo=e}),class:"max-w460"},null,8,["modelValue"])]})),_:1}),f(j,{label:"打印机秘钥",prop:"PRINT_CENTER.key",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.PRINT_CENTER.key,"onUpdate:modelValue":n[6]||(n[6]=function(e){return c.form.PRINT_CENTER.key=e})},null,8,["modelValue"])]})),_:1})])):g("",!0),f(j,{label:"打印联数",prop:"print_times",rules:[{required:!0,message:" "}]},{default:p((function(){return[f(b,{modelValue:c.form.print_times,"onUpdate:modelValue":n[7]||(n[7]=function(e){return c.form.print_times=e}),type:"number",class:"max-w460"},null,8,["modelValue"]),F]})),_:1}),f(j,{label:"排序"},{default:p((function(){return[f(b,{modelValue:c.form.sort,"onUpdate:modelValue":n[8]||(n[8]=function(e){return c.form.sort=e}),type:"number",class:"max-w460"},null,8,["modelValue"]),x]})),_:1}),N("div",P,[f(q,{type:"primary",onClick:v.onSubmit,loading:c.loading},{default:p((function(){return[U("提交")]})),_:1},8,["onClick","loading"])])]})),_:1},8,["model"])])}],["__scopeId","data-v-1d16b990"]]))}}}));
