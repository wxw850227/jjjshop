import{_ as e}from"./Upload-bc443e94.js";import{E as l,c as a,d as s,e as o,C as p,l as r,f as i,b as t}from"./element-plus-0b11a037.js";import{S as d}from"./supplier-8e828806.js";import{_ as u,u as m,f as n}from"./index-7abb690d.js";import c from"./Map-335b6fd3.js";import{aj as f,o as _,c as h,P as g,S as y,W as b,a as V,Y as j,T as k,bb as w,b9 as x}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const{userInfo:v}=m(),U={components:{Upload:e,Map:c},data:()=>({form:{shop_supplier_id:0,supplier:{user_name:"",logo:"",business_id:0,name:"",link_name:"",link_phone:"",address:"",description:"",user_id:0,store_type:10,coordinate:"",status:0,is_recycle:0,is_main:0,category_set:""}},loading:!1,isupload:!1,type:"logo",user_type:"",open_map:!1,mapData:{},tx_key:""}),created(){this.form.shop_supplier_id=this.$route.query.shop_supplier_id,this.getData(),this.getBaseInof()},methods:{async getBaseInof(){this.user_type=v.user_type},getData(){let e=this;d.toEditSupplier({shop_supplier_id:e.form.shop_supplier_id},!0).then((l=>{e.form.supplier=n(e.form.supplier,l.data.model),e.form.supplier.coordinate=l.data.model.latitude+","+l.data.model.longitude,l.data.model.superUser&&(e.form.supplier.user_name=l.data.model.superUser.user_name),e.tx_key=l.data.key})).catch((e=>{}))},onSubmit(){let e=this,a=e.form;e.$refs.form.validate((s=>{s&&(e.loading=!0,d.editSupplier(a,!0).then((a=>{e.loading=!1,1==a.code?(l({message:"恭喜你，门店修改成功",type:"success"}),e.$router.push("/supplier/supplier/index")):e.loading=!1})).catch((l=>{e.loading=!1})))}))},cancelFunc(){this.$router.back(-1)},openUpload(e){this.type=e,this.isupload=!0},returnImgsFunc(e){null!=e&&e.length>0&&"logo"==this.type&&(this.form.supplier.logo=e[0].file_path),this.isupload=!1},mapClick(){this.mapData.coordinate=this.form.supplier.coordinate,this.mapData.address=this.form.supplier.address,this.open_map=!0},closeDialogFunc(e,l){"map"==l&&(e&&"error"!=e.type&&e.params.coordinate&&(this.form.supplier.address=e.params.address,this.form.supplier.coordinate=e.params.coordinate),this.open_map=!1)}}},q=e=>(w("data-v-1c8f870d"),e=e(),x(),e),C={class:"product-add"},D=q((()=>V("div",{class:"common-form"},"编辑门店",-1))),I={key:0,class:"img"},F=["src"],S=q((()=>V("span",{class:"gray9 f12"},"建议尺寸800px*800px",-1))),z={class:"d-s-c"},$=q((()=>V("div",{class:"ml10"},null,-1))),M={class:"max-w460"},P=q((()=>V("div",null,[V("a",{href:"https://lbs.qq.com/getPoint/",target:"_blank"},"腾讯地图坐标获取")],-1))),R=q((()=>V("div",{class:"tips",style:{color:"red"}}," 未申请腾讯地图key，在腾讯地图里面搜索地址获取经纬度，例经度40.002749,116.323467 ",-1))),B={class:"common-button-wrapper"};const E=u(U,[["render",function(l,d,u,m,n,c){const w=a,x=s,v=o,U=p,q=r,E=i,K=t,L=e,T=f("Map");return _(),h("div",C,[g(K,{size:"small",ref:"form",model:n.form,"label-width":"200px"},{default:y((()=>[D,g(x,{label:"门店名称",prop:"supplier.name",rules:[{required:!0,message:" "}]},{default:y((()=>[g(w,{class:"max-w460",modelValue:n.form.supplier.name,"onUpdate:modelValue":d[0]||(d[0]=e=>n.form.supplier.name=e),placeholder:"请输入门店名称"},null,8,["modelValue"])])),_:1}),g(x,{label:"账号",prop:"supplier.user_name",rules:[{required:!0,message:" "}]},{default:y((()=>[g(w,{disabled:1==n.form.supplier.is_main,class:"max-w460",modelValue:n.form.supplier.user_name,"onUpdate:modelValue":d[1]||(d[1]=e=>n.form.supplier.user_name=e),placeholder:"请输入账号"},null,8,["disabled","modelValue"])])),_:1}),g(x,{label:"登录密码",prop:"supplier.password"},{default:y((()=>[g(w,{modelValue:n.form.supplier.password,"onUpdate:modelValue":d[2]||(d[2]=e=>n.form.supplier.password=e),placeholder:"请输入登录密码",type:"password",class:"max-w460"},null,8,["modelValue"])])),_:1}),g(x,{label:"确认密码",prop:"supplier.confirm_password"},{default:y((()=>[g(w,{modelValue:n.form.supplier.confirm_password,"onUpdate:modelValue":d[3]||(d[3]=e=>n.form.supplier.confirm_password=e),placeholder:"请输入确认密码",type:"password",class:"max-w460"},null,8,["modelValue"])])),_:1}),g(x,{label:"联系人",prop:"supplier.link_name",rules:[{required:!0,message:" "}]},{default:y((()=>[g(w,{class:"max-w460",modelValue:n.form.supplier.link_name,"onUpdate:modelValue":d[4]||(d[4]=e=>n.form.supplier.link_name=e),placeholder:"请输入联系人"},null,8,["modelValue"])])),_:1}),g(x,{label:"联系电话",prop:"supplier.link_phone",rules:[{required:!0,message:" "}]},{default:y((()=>[g(w,{class:"max-w460",modelValue:n.form.supplier.link_phone,"onUpdate:modelValue":d[5]||(d[5]=e=>n.form.supplier.link_phone=e),placeholder:"请输入联系电话"},null,8,["modelValue"])])),_:1}),g(x,{label:"门店logo"},{default:y((()=>[g(U,null,{default:y((()=>[g(v,{icon:"Picture",onClick:d[6]||(d[6]=e=>c.openUpload("logo"))},{default:y((()=>[b("选择图片")])),_:1}),""!=n.form.supplier.logo?(_(),h("div",I,[V("img",{src:n.form.supplier.logo,width:"100",height:"100"},null,8,F)])):j("",!0)])),_:1}),S])),_:1}),g(x,{label:"地址",prop:"supplier.address",rules:[{required:!0,message:" "}]},{default:y((()=>[g(w,{class:"max-w460",modelValue:n.form.supplier.address,"onUpdate:modelValue":d[7]||(d[7]=e=>n.form.supplier.address=e),placeholder:"请输入联系人"},null,8,["modelValue"])])),_:1}),g(x,{label:"门店坐标",prop:"supplier.coordinate",rules:[{required:!0,message:" "}]},{default:y((()=>[n.tx_key?(_(),k(v,{key:0,icon:"LocationInformation",onClick:d[8]||(d[8]=e=>c.mapClick())},{default:y((()=>[b("选择经纬度")])),_:1})):j("",!0),V("div",z,[$,V("div",M,[g(w,{modelValue:n.form.supplier.coordinate,"onUpdate:modelValue":d[9]||(d[9]=e=>n.form.supplier.coordinate=e)},null,8,["modelValue"])])]),P,R])),_:1}),g(x,{label:"商家介绍",prop:"supplier.description"},{default:y((()=>[g(w,{rows:"6",type:"textarea",modelValue:n.form.supplier.description,"onUpdate:modelValue":d[10]||(d[10]=e=>n.form.supplier.description=e),class:"max-w460"},null,8,["modelValue"])])),_:1}),0==n.user_type&&0==n.form.supplier.is_main?(_(),k(x,{key:0,label:"状态"},{default:y((()=>[g(E,{modelValue:n.form.supplier.is_recycle,"onUpdate:modelValue":d[11]||(d[11]=e=>n.form.supplier.is_recycle=e)},{default:y((()=>[g(q,{label:0},{default:y((()=>[b("开启")])),_:1}),g(q,{label:1},{default:y((()=>[b("禁止")])),_:1})])),_:1},8,["modelValue"])])),_:1})):j("",!0),g(x,{label:"营业状态"},{default:y((()=>[g(E,{modelValue:n.form.supplier.status,"onUpdate:modelValue":d[12]||(d[12]=e=>n.form.supplier.status=e)},{default:y((()=>[g(q,{label:0},{default:y((()=>[b("营业中")])),_:1}),g(q,{label:1},{default:y((()=>[b("停止营业")])),_:1})])),_:1},8,["modelValue"])])),_:1}),V("div",B,[g(v,{size:"small",type:"info",onClick:c.cancelFunc},{default:y((()=>[b("取消")])),_:1},8,["onClick"]),g(v,{size:"small",type:"primary",onClick:c.onSubmit,loading:n.loading},{default:y((()=>[b("提交")])),_:1},8,["onClick","loading"])])])),_:1},8,["model"]),n.isupload?(_(),k(L,{key:0,isupload:n.isupload,type:n.type,onReturnImgs:c.returnImgsFunc},{default:y((()=>[b("上传图片")])),_:1},8,["isupload","type","onReturnImgs"])):j("",!0),g(T,{open:n.open_map,mapData:n.mapData,tx_key:n.tx_key,onClose:d[13]||(d[13]=e=>c.closeDialogFunc(e,"map"))},null,8,["open","mapData","tx_key"])])}],["__scopeId","data-v-1c8f870d"]]);export{E as default};
