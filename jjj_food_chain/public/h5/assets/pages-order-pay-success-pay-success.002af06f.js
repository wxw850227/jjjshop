import{_ as e,l as a,p as s,o as t,c as d,w as o,n as c,f as r,a as l,d as i,t as n,h as u,j as _,A as p}from"./index-61e49878.js";/* empty css                                                                  */const g=e({data:()=>({loadding:!0,indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,order_id:0,detail:{order_status:[],address:{region:[]},product:[],pay_type:[],delivery_type:[],pay_status:[]}}),onLoad(e){this.order_id=e.order_id},mounted(){a({title:"加载中"}),this.getData()},methods:{getData(){let e=this,a=e.order_id;e._get("user.order/paySuccess",{order_id:a},(function(a){e.detail=a.data.order,e.loadding=!1,s()}))},goHome(){this.gotoPage("/pages/index/index")},goMyorder(){this.gotoPage("/pages/order/myorder")}}},[["render",function(e,a,s,g,f,m){const y=_,h=r,x=p;return t(),d(h,{"data-theme":e.theme(),class:c(e.theme()||"")},{default:o((()=>[f.loadding?u("",!0):(t(),d(h,{key:0,class:"pay-success"},{default:o((()=>[l(h,{class:"success-icon d-c-c d-c"},{default:o((()=>[l(y,{class:"iconfont icon-queren"}),l(y,{class:"name"},{default:o((()=>[i("支付成功")])),_:1})])),_:1}),l(h,{class:"success-price d-c-c"},{default:o((()=>[i(" ￥"),l(y,{class:"num"},{default:o((()=>[i(n(f.detail.pay_price),1)])),_:1})])),_:1}),l(h,{class:"success-btns d-b-c"},{default:o((()=>[l(x,{class:"flex-1 mr10 btn1",onClick:a[0]||(a[0]=e=>m.goHome())},{default:o((()=>[i("返回首页")])),_:1}),l(x,{class:"flex-1 ml10 btn2",onClick:m.goMyorder},{default:o((()=>[i("我的订单")])),_:1},8,["onClick"])])),_:1})])),_:1}))])),_:1},8,["data-theme","class"])}],["__scopeId","data-v-85e1b6e3"]]);export{g as default};
