import{_ as e,k as t,m as a,X as s,E as l,p as d,c as r,w as o,e as c,o as u,a as _,K as i,h as n,t as f,g as p,b as y,r as g,F as x,z as m,B as b,i as v}from"./index-g5-6g3i5.js";/* empty css                                                                  */const h=e({data:()=>({indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,isPayPopup:!1,order_no:0,detail:{order_status:[],address:{region:[]},product:[],pay_type:[],delivery_type:[],pay_status:[]},extractStore:{}}),components:{},onLoad(e){this.order_no=e.order_no},mounted(){this.getData()},methods:{getData(){let e=this;t({title:"加载中"}),e._get("store.order/detail",{order_no:e.order_no},(function(t){e.detail=t.data.order,e.extractStore=t.data.order.extractStore,a()}),(function(e){s({url:"/pages/user/my_shop/my_shop"})}))},onSubmitExtract(e){let t=this;l({title:"提示",content:"您确定要核销吗?",success:function(a){a.confirm&&t._post("store.order/extract",{order_id:e},(function(e){d({title:e.msg,duration:2e3,icon:"success"}),setTimeout((function(){t.getData()}),2e3)}))}})}}},[["render",function(e,t,a,s,l,d){const h=c,S=m,k=v,w=b;return u(),r(h,{class:"order-datail"},{default:o((()=>[_(h,{class:"order-state d-s-c"},{default:o((()=>[_(h,{class:"icon-box"},{default:o((()=>[i("span",{class:"icon iconfont icon-gantanhao"})])),_:1}),_(h,{class:"state-cont flex-1"},{default:o((()=>[_(h,{class:"state-txt d-b-c"},{default:o((()=>[_(S,{class:"desc f34"},{default:o((()=>[n(f(l.detail.state_text),1)])),_:1})])),_:1})])),_:1}),_(h,{class:"dot-bg"})])),_:1}),20==l.detail.delivery_type.value?(u(),r(h,{key:0,class:"order-express p30 d-s-c"},{default:o((()=>[_(h,{class:"flow-delivery__title m-top20"},{default:o((()=>[i("span",{class:"icon iconfont icon-dizhi1"},"自提门店")])),_:1}),_(h,{class:"cont-text ml20"},{default:o((()=>[_(h,{class:"express-text"},{default:o((()=>[n(f(l.extractStore.store_name)+" "+f(l.extractStore.phone)+" ",1),_(h,{class:"f24 gray9 pt10"},{default:o((()=>[n(f(l.extractStore.region.province)+" "+f(l.extractStore.region.city)+" "+f(l.extractStore.region.region)+" "+f(l.extractStore.address),1)])),_:1})])),_:1})])),_:1})])),_:1})):p("",!0),_(h,{class:"shops group bg-white"},{default:o((()=>[_(h,{class:"group-hd border-b-e"},{default:o((()=>[_(h,{class:"left"},{default:o((()=>[_(S,{class:"min-name"},{default:o((()=>[n("商品")])),_:1})])),_:1})])),_:1}),_(h,{class:"list"},{default:o((()=>[(u(!0),y(x,null,g(l.detail.product,((e,t)=>(u(),r(h,{class:"one-product p-20-0",key:t},{default:o((()=>[_(h,{class:"d-s-c"},{default:o((()=>[_(h,{class:"cover"},{default:o((()=>[_(k,{src:e.image.file_path,mode:"aspectFit"},null,8,["src"])])),_:2},1024),_(h,{class:"flex-1"},{default:o((()=>[_(h,{class:"pro-info"},{default:o((()=>[n(f(e.product_name),1)])),_:2},1024),_(h,{class:"pt10 p-0-30 d-b-c"},{default:o((()=>[_(h,{class:"price f22"},{default:o((()=>[n(" ¥ "),_(S,{class:"f40"},{default:o((()=>[n(f(e.product_price),1)])),_:2},1024)])),_:2},1024),_(h,{class:"f24 gray9"},{default:o((()=>[n("x"+f(e.total_num),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1}),_(h,{class:"group bg-white f28"},{default:o((()=>[_(h,{class:"p-20-0"},{default:o((()=>[_(S,{class:"gray9"},{default:o((()=>[n("订单编号：")])),_:1}),_(S,null,{default:o((()=>[n(f(l.detail.order_no),1)])),_:1})])),_:1}),_(h,{class:"p-20-0"},{default:o((()=>[_(S,{class:"gray9"},{default:o((()=>[n("下单时间：")])),_:1}),_(S,null,{default:o((()=>[n(f(l.detail.create_time),1)])),_:1})])),_:1}),_(h,{class:"p-20-0"},{default:o((()=>[_(S,{class:"gray9"},{default:o((()=>[n("支付方式：")])),_:1}),_(S,null,{default:o((()=>[n(f(l.detail.pay_type.text),1)])),_:1})])),_:1}),_(h,{class:"p-20-0"},{default:o((()=>[_(S,{class:"gray9"},{default:o((()=>[n("配送方式：")])),_:1}),_(S,null,{default:o((()=>[n(f(l.detail.delivery_type.text),1)])),_:1})])),_:1}),_(h,{class:"p-20-0 d-b-c"},{default:o((()=>[_(S,{class:"gray9"},{default:o((()=>[n("商品金额")])),_:1}),_(S,null,{default:o((()=>[n("¥ "+f(l.detail.order_price),1)])),_:1})])),_:1}),_(h,{class:"p-20-0 d-b-c"},{default:o((()=>[_(S,{class:"gray9"},{default:o((()=>[n("运费")])),_:1}),_(S,null,{default:o((()=>[n("+ ¥ "+f(l.detail.express_price),1)])),_:1})])),_:1}),_(h,{class:"p-20-0 d-e-c fb f34"},{default:o((()=>[n(" 应付金额： "),_(S,{class:"red"},{default:o((()=>[n("¥ "+f(l.detail.order_price),1)])),_:1})])),_:1})])),_:1}),20!=l.detail.order_status.value?(u(),r(h,{key:1,class:"flow-fixed-footer b-f"},{default:o((()=>[20==l.detail.pay_status.value&&20==l.detail.delivery_type.value&&10==l.detail.delivery_status.value?(u(),r(h,{key:0},{default:o((()=>[_(w,{class:"btn-red",onClick:t[0]||(t[0]=e=>d.onSubmitExtract(l.detail.order_id))},{default:o((()=>[n("确认核销")])),_:1})])),_:1})):p("",!0)])),_:1})):p("",!0)])),_:1})}],["__scopeId","data-v-a5ec713d"]]);export{h as default};
