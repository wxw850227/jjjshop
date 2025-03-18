import{_ as e}from"./Upload-bc443e94.js";import{E as l,c as s,d as o,e as a,G as i,b as t}from"./element-plus-0b11a037.js";import{S as r}from"./setting-829576b6.js";import{_ as m,u as d,f as p}from"./index-7abb690d.js";import{ak as n,o as u,c,P as g,S as f,a as _,W as h,$ as v,T as j,Y as b,bb as w,b9 as x}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const{changStore:U}=d(),V={components:{Upload:e},data:()=>({loading:!1,form:{name:"",is_get_log:0,is_send_wx:0,login_logo:"",login_desc:"",user_name:"",wx_phone:"",avatarUrl:"",logoUrl:""},isupload:!1}),created(){this.getParams()},methods:{getParams(){let e=this;r.storeDetail({},!0).then((l=>{let s=l.data.vars.values;e.form=p(e.form,s),e.loading=!1})).catch((e=>{}))},onSubmit(){let e=this,s=this.form;e.$refs.form.validate((o=>{o&&(e.loading=!0,r.editStore(s,!0).then((o=>{e.loading=!1,l({message:"恭喜你，商城设置成功",type:"success"}),U(s),e.$router.push("/setting/store/index")})).catch((l=>{e.loading=!1})))}))},chooseImg(e){this.type=e,this.isupload=!0},returnImgsFunc(e){this.isupload=!1,null!=e&&e.length>0&&("avatarUrl"==this.type?this.form.avatarUrl=e[0].file_path:"login_logo"==this.type?this.form.login_logo=e[0].file_path:"logoUrl"==this.type&&(this.form.logoUrl=e[0].file_path))}}},y=e=>(w("data-v-077bbac6"),e=e(),x(),e),I={class:"product-add"},k=y((()=>_("div",{class:"common-form"},"平台设置",-1))),q={class:"ww100"},S={class:"d-c"},C={class:"mt10",width:100},M=y((()=>_("div",{class:"gray"},"建议上传图像大小尺寸为100px*100px",-1))),O=y((()=>_("div",{class:"tips"},"小程序和H5端用户默认昵称前缀，和用户id组成默认昵称",-1))),P={class:"ww100"},G={class:"d-c"},$={class:"mt10",width:100},z=y((()=>_("div",{class:"gray"},"建议上传图像大小尺寸为100px*100px",-1))),F=y((()=>_("div",{class:"common-form"},"小程序设置",-1))),L=y((()=>_("div",{class:"tips"},"后台发货后自动向小程序发货",-1))),R={class:"ww100"},D={class:"d-c"},E={class:"mt10",width:100},H=y((()=>_("div",{class:"gray"},"建议上传图像大小尺寸为100px*100px",-1))),K=y((()=>_("div",{class:"tips"},"小程序授权登录文字描述",-1))),T=y((()=>_("div",{class:"tips"},"小程序用户登录是否开启手机号授权",-1))),W=y((()=>_("div",{class:"common-form"},"日志记录",-1))),Y=y((()=>_("div",{class:"tips"},"如果记录，日志量会有点大",-1))),A={class:"common-button-wrapper"};const B=m(V,[["render",function(l,r,m,d,p,w){const x=s,U=o,V=a,y=i,B=t,J=e,N=n("img-url");return u(),c("div",I,[g(B,{size:"small",ref:"form",model:p.form,"label-width":"150px"},{default:f((()=>[k,g(U,{label:"平台名称",rules:[{required:!0,message:" "}],prop:"name"},{default:f((()=>[g(x,{modelValue:p.form.name,"onUpdate:modelValue":r[0]||(r[0]=e=>p.form.name=e),placeholder:"商城名称",class:"max-w460"},null,8,["modelValue"])])),_:1}),g(U,{label:"平台LOGO",rules:[{required:!0,message:" "}],prop:"name"},{default:f((()=>[_("div",q,[g(V,{onClick:r[1]||(r[1]=e=>w.chooseImg("logoUrl"))},{default:f((()=>[h("选择图片")])),_:1})]),_("div",S,[v(_("img",C,null,512),[[N,p.form.logoUrl]]),M])])),_:1}),g(U,{label:"默认昵称",rules:[{required:!0,message:" "}],prop:"user_name"},{default:f((()=>[g(x,{modelValue:p.form.user_name,"onUpdate:modelValue":r[2]||(r[2]=e=>p.form.user_name=e),modelModifiers:{trim:!0},placeholder:"默认昵称",class:"max-w460"},null,8,["modelValue"]),O])),_:1}),g(U,{label:"默认头像",rules:[{required:!0,message:"请选择默认头像"}]},{default:f((()=>[_("div",P,[g(V,{onClick:r[3]||(r[3]=e=>w.chooseImg("avatarUrl"))},{default:f((()=>[h("选择图片")])),_:1})]),_("div",G,[v(_("img",$,null,512),[[N,p.form.avatarUrl]]),z])])),_:1}),F,g(U,{label:"小程序发货",prop:"is_send_wx"},{default:f((()=>[g(y,{modelValue:p.form.is_send_wx,"onUpdate:modelValue":r[4]||(r[4]=e=>p.form.is_send_wx=e),modelModifiers:{trim:!0}},{default:f((()=>[h("向小程序发货")])),_:1},8,["modelValue"]),L])),_:1}),g(U,{label:"登录LOGO",rules:[{required:!0,message:" "}],prop:"login_logo"},{default:f((()=>[_("div",R,[g(V,{onClick:r[5]||(r[5]=e=>w.chooseImg("login_logo"))},{default:f((()=>[h("选择图片")])),_:1})]),_("div",D,[v(_("img",E,null,512),[[N,p.form.login_logo]]),H])])),_:1}),g(U,{label:"授权登录描述",rules:[{required:!0,message:" "}],prop:"login_desc"},{default:f((()=>[g(x,{modelValue:p.form.login_desc,"onUpdate:modelValue":r[6]||(r[6]=e=>p.form.login_desc=e),modelModifiers:{trim:!0},placeholder:"授权登录描述",class:"max-w460"},null,8,["modelValue"]),K])),_:1}),g(U,{label:"是否开启手机号授权",prop:"wx_phone"},{default:f((()=>[g(y,{modelValue:p.form.wx_phone,"onUpdate:modelValue":r[7]||(r[7]=e=>p.form.wx_phone=e),modelModifiers:{trim:!0}},{default:f((()=>[h("开启手机号授权")])),_:1},8,["modelValue"]),T])),_:1}),W,g(U,{label:"是否记录查询日志",prop:"is_get_log"},{default:f((()=>[g(y,{modelValue:p.form.is_get_log,"onUpdate:modelValue":r[8]||(r[8]=e=>p.form.is_get_log=e)},{default:f((()=>[h("记录查询日志")])),_:1},8,["modelValue"]),Y])),_:1}),_("div",A,[g(V,{type:"primary",onClick:w.onSubmit,loading:p.loading},{default:f((()=>[h("提交")])),_:1},8,["onClick","loading"])])])),_:1},8,["model"]),p.isupload?(u(),j(J,{key:0,isupload:p.isupload,type:l.type,config:{total:1},onReturnImgs:w.returnImgsFunc},null,8,["isupload","type","onReturnImgs"])):b("",!0)])}],["__scopeId","data-v-077bbac6"]]);export{B as default};
