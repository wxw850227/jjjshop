import{E as t,e,b as o,v as s}from"./element-plus-0b11a037.js";import{P as i}from"./product-27fdf87b.js";import r from"./Basic-e1e12c92.js";import a from"./Attr-a3e0b380.js";import d from"./Ingredients-dccbe660.js";import n from"./Spec-b5d5fb89.js";import m from"./Content-791dddd5.js";import c from"./Buyset-82dd631b.js";import{_ as p,f as l}from"./index-7abb690d.js";import{aj as u,$ as _,o as f,c as j,T as g,S as h,P as y,a as v,W as b,Y as k}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./Upload-bc443e94.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";import"./UE-6c8f278a.js";const S={class:"product-add"},I={class:"common-button-wrapper"};const C=p({components:{Basic:r,Spec:n,Attr:a,Ingredients:d,Content:m,Buyset:c},data:()=>({old_audit:20,product_id:0,scene:"edit",loading:!0,save_loading:!1,form:{model:{},category:[],gradeList:[],feed:[],special:[],specData:null,isSpecLocked:!1,basicSetting:{},agentSetting:{}},model:{product_name:"",category_id:null,image:[],selling_point:"",spec_type:10,deduct_stock_type:20,sku:[{product_price:"",stock_num:"",product_weight:"",cost_price:""}],product_attr:[],product_feed:[],min_buy:1,product_unit:"",content:"",product_status:10,sales_initial:0,product_sort:100,limit_num:0,special_id:0}}),provide:function(){return{form:this.form}},created(){this.product_id=this.$route.query.product_id,this.scene=this.$route.query.scene,this.getData()},methods:{getData:function(){let t=this;i.takeGetEditData({product_id:t.product_id,scene:t.scene},!0).then((e=>{t.loading=!1,Object.assign(t.form,e.data),t.form.model.product_status=e.data.model.product_status.value})).catch((e=>{t.loading=!1}))},onSubmit:function(){let e=this;e.$refs.form.validate((o=>{if(l(e.model,e.form.model),o){let o=l(e.model,e.form.model);o.scene=e.scene,o.image=e.ImgKeepId(o.image),o.product_id=e.product_id,o.sku=e.form.model.sku,e.save_loading=!0,i.takeEditProduct({product_id:e.product_id,params:JSON.stringify(o)},!0).then((o=>{e.save_loading=!1,t({message:"保存成功",type:"success"}),e.cancelFunc()})).catch((t=>{e.save_loading=!1}))}}))},ImgKeepId(t){let e=[];for(let o=0,s=t.length;o<s;o++){let s={image_id:t[o].image_id,file_id:t[o].file_id};e.push(s)}return e},cancelFunc(){this.$router.back(-1)}}},[["render",function(t,i,r,a,d,n){const m=u("Basic"),c=u("Spec"),p=u("Attr"),l=u("Ingredients"),C=u("Content"),w=u("Buyset"),B=e,x=o,$=s;return _((f(),j("div",S,[d.loading?k("",!0):(f(),g(x,{key:0,size:"small",ref:"form",model:d.form,"label-width":"180px"},{default:h((()=>[y(m),y(c),y(p),y(l),y(C),y(w),v("div",I,[y(B,{size:"small",type:"info",onClick:n.cancelFunc},{default:h((()=>[b("取消")])),_:1},8,["onClick"]),y(B,{size:"small",type:"primary",onClick:n.onSubmit,loading:d.loading},{default:h((()=>[b("修改")])),_:1},8,["onClick","loading"])])])),_:1},8,["model"]))])),[[$,d.loading]])}],["__scopeId","data-v-0b035185"]]);export{C as default};
