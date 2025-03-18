import{_ as e,l as t,p as a,$ as s,E as l,q as d,o as r,c as o,w as c,f as u,a as _,O as i,d as f,t as n,h as p,b as y,r as x,F as g,j as m,A as b,i as v}from"./index-61e49878.js";/* empty css                                                                  */const h=e({data:()=>({indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,isPayPopup:!1,order_no:0,detail:{order_status:[],address:{region:[]},product:[],pay_type:[],delivery_type:[],pay_status:[]},extractStore:{}}),components:{},onLoad(e){this.order_no=e.order_no},mounted(){this.getData()},methods:{getData(){let e=this;t({title:"加载中"}),e._get("store.order/detail",{order_no:e.order_no},(function(t){e.detail=t.data.order,e.extractStore=t.data.order.extractStore,a()}),(function(e){s({url:"/pages/user/my_shop/my_shop"})}))},onSubmitExtract(e){let t=this;l({title:"提示",content:"您确定要核销吗?",success:function(a){a.confirm&&t._post("store.order/extract",{order_id:e},(function(e){d({title:e.msg,duration:2e3,icon:"success"}),setTimeout((function(){t.getData()}),2e3)}))}})}}},[["render",function(e,t,a,s,l,d){const h=u,S=m,k=v,w=b;return r(),o(h,{class:"order-datail"},{default:c((()=>[_(h,{class:"order-state d-s-c"},{default:c((()=>[_(h,{class:"icon-box"},{default:c((()=>[i("span",{class:"icon iconfont icon-gantanhao"})])),_:1}),_(h,{class:"state-cont flex-1"},{default:c((()=>[_(h,{class:"state-txt d-b-c"},{default:c((()=>[_(S,{class:"desc f34"},{default:c((()=>[f(n(l.detail.state_text),1)])),_:1})])),_:1})])),_:1}),_(h,{class:"dot-bg"})])),_:1}),20==l.detail.delivery_type.value?(r(),o(h,{key:0,class:"order-express p30 d-s-c"},{default:c((()=>[_(h,{class:"flow-delivery__title m-top20"},{default:c((()=>[i("span",{class:"icon iconfont icon-dizhi1"},"自提门店")])),_:1}),_(h,{class:"cont-text ml20"},{default:c((()=>[_(h,{class:"express-text"},{default:c((()=>[f(n(l.extractStore.store_name)+" "+n(l.extractStore.phone)+" ",1),_(h,{class:"f24 gray9 pt10"},{default:c((()=>[f(n(l.extractStore.region.province)+" "+n(l.extractStore.region.city)+" "+n(l.extractStore.region.region)+" "+n(l.extractStore.address),1)])),_:1})])),_:1})])),_:1})])),_:1})):p("",!0),_(h,{class:"shops group bg-white"},{default:c((()=>[_(h,{class:"group-hd border-b-e"},{default:c((()=>[_(h,{class:"left"},{default:c((()=>[_(S,{class:"min-name"},{default:c((()=>[f("商品")])),_:1})])),_:1})])),_:1}),_(h,{class:"list"},{default:c((()=>[(r(!0),y(g,null,x(l.detail.product,((e,t)=>(r(),o(h,{class:"one-product p-20-0",key:t},{default:c((()=>[_(h,{class:"d-s-c"},{default:c((()=>[_(h,{class:"cover"},{default:c((()=>[_(k,{src:e.image.file_path,mode:"aspectFit"},null,8,["src"])])),_:2},1024),_(h,{class:"flex-1"},{default:c((()=>[_(h,{class:"pro-info"},{default:c((()=>[f(n(e.product_name),1)])),_:2},1024),_(h,{class:"pt10 p-0-30 d-b-c"},{default:c((()=>[_(h,{class:"price f22"},{default:c((()=>[f(" ¥ "),_(S,{class:"f40"},{default:c((()=>[f(n(e.product_price),1)])),_:2},1024)])),_:2},1024),_(h,{class:"f24 gray9"},{default:c((()=>[f("x"+n(e.total_num),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1}),_(h,{class:"group bg-white f28"},{default:c((()=>[_(h,{class:"p-20-0"},{default:c((()=>[_(S,{class:"gray9"},{default:c((()=>[f("订单编号：")])),_:1}),_(S,null,{default:c((()=>[f(n(l.detail.order_no),1)])),_:1})])),_:1}),_(h,{class:"p-20-0"},{default:c((()=>[_(S,{class:"gray9"},{default:c((()=>[f("下单时间：")])),_:1}),_(S,null,{default:c((()=>[f(n(l.detail.create_time),1)])),_:1})])),_:1}),_(h,{class:"p-20-0"},{default:c((()=>[_(S,{class:"gray9"},{default:c((()=>[f("支付方式：")])),_:1}),_(S,null,{default:c((()=>[f(n(l.detail.pay_type.text),1)])),_:1})])),_:1}),_(h,{class:"p-20-0"},{default:c((()=>[_(S,{class:"gray9"},{default:c((()=>[f("配送方式：")])),_:1}),_(S,null,{default:c((()=>[f(n(l.detail.delivery_type.text),1)])),_:1})])),_:1}),_(h,{class:"p-20-0 d-b-c"},{default:c((()=>[_(S,{class:"gray9"},{default:c((()=>[f("商品金额")])),_:1}),_(S,null,{default:c((()=>[f("¥ "+n(l.detail.order_price),1)])),_:1})])),_:1}),_(h,{class:"p-20-0 d-b-c"},{default:c((()=>[_(S,{class:"gray9"},{default:c((()=>[f("运费")])),_:1}),_(S,null,{default:c((()=>[f("+ ¥ "+n(l.detail.express_price),1)])),_:1})])),_:1}),_(h,{class:"p-20-0 d-e-c fb f34"},{default:c((()=>[f(" 应付金额： "),_(S,{class:"red"},{default:c((()=>[f("¥ "+n(l.detail.order_price),1)])),_:1})])),_:1})])),_:1}),20!=l.detail.order_status.value?(r(),o(h,{key:1,class:"flow-fixed-footer b-f"},{default:c((()=>[20==l.detail.pay_status.value&&20==l.detail.delivery_type.value&&10==l.detail.delivery_status.value?(r(),o(h,{key:0},{default:c((()=>[_(w,{class:"btn-red",onClick:t[0]||(t[0]=e=>d.onSubmitExtract(l.detail.order_id))},{default:c((()=>[f("确认核销")])),_:1})])),_:1})):p("",!0)])),_:1})):p("",!0)])),_:1})}],["__scopeId","data-v-a5ec713d"]]);export{h as default};
