import{_ as e,o as a,c as t,w as l,a as s,d,b as i,r as c,F as u,N as r,n as o,i as _,f as n,O as p,h as A,j as f,l as y,p as m,E as g,q as h,D as b,t as k,x as P}from"./index-61e49878.js";import{r as x}from"./uni-app.es.4f849c72.js";import{p as C}from"./pay.1d1505fb.js";const F=e({data:()=>({checkedPay:[10,20],pay_type:10}),props:["isPayPopup"],methods:{hidePopupFunc(e){this.$emit("close",e)},payTypeFunc(e){this.pay_type=e},subFunc(){this.$emit("submit",this.pay_type)}}},[["render",function(e,y,m,g,h,b){const k=_,P=n,x=f;return a(),t(P,{class:o(m.isPayPopup?"pop-bg open cashier-pop":"pop-bg close cashier-pop"),onClick:y[2]||(y[2]=r((()=>{}),["stop"]))},{default:l((()=>[s(P,{class:"pop-content",onClick:y[1]||(y[1]=r((()=>{}),["stop"]))},{default:l((()=>[s(k,{onClick:y[0]||(y[0]=e=>b.hidePopupFunc(null)),class:"close-img",src:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAAXNSR0IArs4c6QAAAEtQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA5mA6dAAAABh0Uk5TAC0uMDE0NjdeYWJjZGVmZ2lrbG1ucP3+sVMQoAAAAMRJREFUOMutk+sOwiAMhedtKrIxBlLe/0ktA6adLYuJ/QX9Tko4bbvuH3Ht2/kTgOf4E+C4HM4QYf7mM6YP+eg5ReJrYYeXiXKLKUuvRDFRnhWG8k3JlBrqxVD5O1kUA8ezQlU+cMYgCKhQyDVvrY4x3FSIQUtNSTVQ9JDbphO/N/qqdgQjRIwwig+k97GI9AlTvikpVqO0bGTLavvpL6Ng2k3Gwe0MjN8ZOb8tWIfW1b0Qx77uBceXxSl70V94a6X8j/EC3DIS0h2SQCgAAAAASUVORK5CYII=",mode:""}),s(P,{class:"tc f32 fb"},{default:l((()=>[d("支付方式")])),_:1}),(a(!0),i(u,null,c(h.checkedPay,((e,c)=>(a(),t(P,{key:c,class:o(["cashier-item",h.pay_type==e?"active":""]),onClick:a=>b.payTypeFunc(e)},{default:l((()=>[20==e?(a(),i(u,{key:0},[s(x,{class:"f28 gray3"},{default:l((()=>[d("微信支付")])),_:1}),s(P,{class:"icon-box d-c-c"},{default:l((()=>[p("span",{class:"icon iconfont icon-tijiaochenggong"})])),_:1})],64)):A("",!0),30==e?(a(),i(u,{key:1},[s(x,{class:"f28 gray3"},{default:l((()=>[d("支付宝支付")])),_:1}),s(P,{class:"icon-box d-c-c"},{default:l((()=>[p("span",{class:"icon iconfont icon-tijiaochenggong"})])),_:1})],64)):A("",!0),10==e?(a(),i(u,{key:2},[s(x,{class:"f28 gray3"},{default:l((()=>[d("余额支付")])),_:1}),s(P,{class:"icon-box d-c-c"},{default:l((()=>[p("span",{class:"icon iconfont icon-tijiaochenggong"})])),_:1})],64)):A("",!0)])),_:2},1032,["class","onClick"])))),128)),s(P,{class:"pay-btn",onClick:b.subFunc},{default:l((()=>[d("立即支付")])),_:1},8,["onClick"])])),_:1})])),_:1},8,["class"])}]]);const v=e({components:{cashier:F},data:()=>({checkedPay:[10,20],pay_type:20,loadding:!0,indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,isPayPopup:!1,order_id:0,detail:{order_status:[],address:{region:[]},product:[],pay_type:[],delivery_type:[],pay_status:[]},extractStore:{},is_fightgroup:!1,showAlipay:!1,qrimg:""}),onLoad(e){this.order_id=e.order_id},mounted(){y({title:"加载中"}),this.getData()},methods:{getData(){let e=this,a=e.order_id;e._get("user.order/detail",{order_id:a},(function(a){e.detail=a.data.order,e.extractStore=a.data.order.extractStore,e.loadding=!1,m()}))},hidePopupFunc(){this.isPayPopup=!1},cancelOrder(e){let a=this,t=e;g({title:"提示",content:"您确定要取消当前订单吗?",success:function(e){e.confirm&&(y({title:"正在处理"}),a._get("user.order/cancel",{order_id:t},(function(e){m(),h({title:"操作成功",duration:2e3,icon:"success"}),a.getData()})))}})},orderReceipt(e){let a=this;g({title:"提示",content:"您确定要收货吗?",success:function(t){t.confirm&&(y({title:"正在处理"}),a._post("user.order/receipt",{order_id:e},(function(e){m(),h({title:e.msg,duration:2e3,icon:"success"}),a.getData()})))}})},gotoExpress(e){b({url:"/pages/order/express/express?order_id="+e})},onApplyRefund(e){b({url:"/pages/order/refund/apply/apply?order_product_id="+e})},payTypeFunc(e){this.pay_type=e},subFunc(e){let a=this;if(!a.isPayPopup)return;a.isPayPopup=!1;let t=a.order_id;y({title:"加载中"}),a._post("user.order/pay",{payType:e,order_id:t,pay_source:a.getPlatform()},(function(e){m(),C(e,a)}))},onPayOrder(e){this.isPayPopup=!0,this.order_id=e}}},[["render",function(e,r,p,y,m,g){const h=n,b=f,C=_,v=x(P("cashier"),F);return a(),t(h,{"data-theme":e.theme(),class:o(e.theme()||"")},{default:l((()=>[m.loadding?A("",!0):(a(),t(h,{key:0,class:"order-box"},{default:l((()=>[s(h,{class:"f40 fb mb40"},{default:l((()=>[d(k(m.detail.state_text),1)])),_:1}),10==m.detail.pay_status.value&&20!=m.detail.order_status.value?(a(),t(h,{key:0,class:"d-s-c mt20 mb40"},{default:l((()=>[s(h,{class:"f26"},{default:l((()=>[d("交易将在："),s(b,{class:"orange"},{default:l((()=>[d(k(m.detail.pay_end_time),1)])),_:1}),d("后关闭，请及时付款")])),_:1})])),_:1})):A("",!0),1!=m.detail.order_type&&10==m.detail.delivery_type.value&&10==m.detail.order_status.value?(a(),t(h,{key:1,class:"top-state"},{default:l((()=>[s(h,{class:"d-b-c state-height border-b"},{default:l((()=>[s(h,{class:""},{default:l((()=>[d("配送员正在为你配送中")])),_:1})])),_:1}),s(h,{class:"d-b-c state-height"},{default:l((()=>[s(h,null,{default:l((()=>[d("预计取餐时间")])),_:1}),s(h,{class:"blue"},{default:l((()=>[d(k(m.detail.mealtime),1)])),_:1})])),_:1})])),_:1})):A("",!0),s(h,{class:"order-content"},{default:l((()=>[s(h,{class:"shop-name border-b"},{default:l((()=>[d(k(m.detail.supplier.name),1)])),_:1}),s(h,{class:"order-prolist"},{default:l((()=>[(a(!0),i(u,null,c(m.detail.product,((e,i)=>(a(),t(h,{class:"d-s-c proitem",key:i},{default:l((()=>[s(h,{class:"pro-image"},{default:l((()=>[s(C,{src:e.image.file_path,mode:"aspectFill"},null,8,["src"])])),_:2},1024),s(h,{class:"d-b-s pro-price flex-1"},{default:l((()=>[s(h,{class:""},{default:l((()=>[s(h,{class:"f28 text-ellipsis fb mb10"},{default:l((()=>[d(k(e.product_name),1)])),_:2},1024),s(h,{class:"f20 gray9 w-b-a"},{default:l((()=>[d(k(e.product_attr),1)])),_:2},1024),s(h,{class:"f22 gray9"},{default:l((()=>[d("x"+k(e.total_num),1)])),_:2},1024)])),_:2},1024),s(h,{class:"pro-price-item"},{default:l((()=>[s(h,{class:"f24 gray3 mb10"},{default:l((()=>[d("￥"+k(e.product_price),1)])),_:2},1024),s(h,{class:"f22 gray9 text-d-line"},{default:l((()=>[d("￥"+k(e.line_price),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1}),s(h,null,{default:l((()=>[s(h,{class:"pro-cont-item"},{default:l((()=>[s(h,null,{default:l((()=>[d("商品小计")])),_:1}),s(h,null,{default:l((()=>[d("￥"+k(m.detail.total_price),1)])),_:1})])),_:1}),0!=m.detail.bag_price?(a(),t(h,{key:0,class:"pro-cont-item"},{default:l((()=>[s(h,null,{default:l((()=>[d("包装费")])),_:1}),s(h,null,{default:l((()=>[d("￥"+k(m.detail.bag_price),1)])),_:1})])),_:1})):A("",!0),m.detail.express_price>0?(a(),t(h,{key:1,class:"pro-cont-item"},{default:l((()=>[s(h,null,{default:l((()=>[d("配送费")])),_:1}),s(h,null,{default:l((()=>[d("￥"+k(m.detail.express_price),1)])),_:1})])),_:1})):A("",!0),s(h,{class:"pro-cont-item pro-cont-total"},{default:l((()=>[d(" 共"+k(m.detail.product.length)+"件商品 小计 ",1),s(b,null,{default:l((()=>[d("￥"+k(m.detail.pay_price),1)])),_:1})])),_:1})])),_:1})])),_:1}),s(h,{class:"other_box"},{default:l((()=>[s(h,{class:"meal_item-title"},{default:l((()=>[d("配送信息")])),_:1}),s(h,{class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("配送服务")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.order_type_text),1)])),_:1})])),_:1}),m.detail.mealtime?(a(),t(h,{key:0,class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("期望时间")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.mealtime),1)])),_:1})])),_:1})):A("",!0),1!=m.detail.order_type&&null!=m.detail.address?(a(),t(h,{key:1,class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("配送地址")])),_:1}),s(h,{class:"right"},{default:l((()=>[s(h,null,{default:l((()=>[d(k(m.detail.address.detail+m.detail.address.address),1)])),_:1}),s(h,null,{default:l((()=>[d(k(m.detail.address.name+" "+m.detail.address.phone),1)])),_:1})])),_:1})])),_:1})):A("",!0)])),_:1}),s(h,{class:"other_box"},{default:l((()=>[s(h,{class:"meal_item-title"},{default:l((()=>[d("订单信息")])),_:1}),s(h,{class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("订单号码")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.order_no),1)])),_:1})])),_:1}),m.detail.table_no?(a(),t(h,{key:0,class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("桌号")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.table_no),1)])),_:1})])),_:1})):A("",!0),s(h,{class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("下单时间")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.create_time),1)])),_:1})])),_:1}),s(h,{class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("支付金额")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.pay_price),1)])),_:1})])),_:1}),s(h,{class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("支付方式")])),_:1}),s(h,{class:"right"},{default:l((()=>[d(k(m.detail.pay_type.text),1)])),_:1})])),_:1}),s(h,{class:"meal_item"},{default:l((()=>[s(h,null,{default:l((()=>[d("备注")])),_:1}),s(h,{class:"right"},{default:l((()=>[s(h,null,{default:l((()=>[d(k(m.detail.buyer_remark),1)])),_:1})])),_:1})])),_:1})])),_:1}),10==m.detail.pay_status.value&&10==m.detail.order_status?(a(),t(h,{key:2,class:"d-c-c"},{default:l((()=>[s(h,{class:"f26 theme-btn pay_btn",onClick:r[0]||(r[0]=e=>g.onPayOrder(m.detail.order_id))},{default:l((()=>[d("立即支付")])),_:1})])),_:1})):A("",!0),s(v,{isPayPopup:m.isPayPopup,onClose:g.hidePopupFunc,onSubmit:g.subFunc},null,8,["isPayPopup","onClose","onSubmit"])])),_:1}))])),_:1},8,["data-theme","class"])}],["__scopeId","data-v-35dd7ab8"]]);export{v as default};
