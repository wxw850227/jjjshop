import{_ as t,o as a,c as e,w as s,a as i,b as l,F as n,r as o,d,t as r,i as c,S as u,e as m,f as g,n as p,g as y,h as f,j as D,k as _,u as h,s as x,l as k,m as b,p as v,q as w,v as C,x as P}from"./index-61e49878.js";import{r as U}from"./uni-app.es.4f849c72.js";const S=t({components:{banner:t({data:()=>({indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,indicatorActiveColor:"#ffffff"}),props:["itemData"],created(){this.interval=this.itemData.params.interval,this.indicatorActiveColor=this.itemData.style.btnColor},methods:{gotoPages(t){this.gotoPage(t.linkUrl)}}},[["render",function(t,p,y,f,D,_){const h=c,x=u,k=m,b=g;return a(),e(b,null,{default:s((()=>[i(b,{class:"diy-banner-box"},{default:s((()=>[i(k,{class:"swiper","indicator-active-color":D.indicatorActiveColor,autoplay:D.autoplay,interval:D.interval,duration:D.duration},{default:s((()=>[(a(!0),l(n,null,o(y.itemData.data,((t,l)=>(a(),e(x,{key:l,onClick:a=>_.gotoPages(t)},{default:s((()=>[i(h,{src:t.imgUrl},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1},8,["indicator-active-color","autoplay","interval","duration"])])),_:1}),i(b,{class:"diy-banner-xf d-b-c"},{default:s((()=>[i(b,{class:"banner-xf-box",onClick:p[0]||(p[0]=a=>t.gotoPage("/"+y.itemData.params.nav[0].navlinkUrl))},{default:s((()=>[i(b,{class:"rides"},{default:s((()=>[i(h,{src:y.itemData.params.nav[0].navimgUrl},null,8,["src"])])),_:1}),i(b,{class:"rides_title"},{default:s((()=>[d(r(y.itemData.params.nav[0].title),1)])),_:1}),i(b,{class:"rides_text"},{default:s((()=>[d(r(y.itemData.params.nav[0].text),1)])),_:1})])),_:1}),i(b,{class:"banner-xf-box",onClick:p[1]||(p[1]=a=>t.gotoPage("/"+y.itemData.params.nav[1].navlinkUrl))},{default:s((()=>[i(b,{class:"rides"},{default:s((()=>[i(h,{src:y.itemData.params.nav[1].navimgUrl},null,8,["src"])])),_:1}),i(b,{class:"rides_title"},{default:s((()=>[d(r(y.itemData.params.nav[1].title),1)])),_:1}),i(b,{class:"rides_text"},{default:s((()=>[d(r(y.itemData.params.nav[1].text),1)])),_:1})])),_:1})])),_:1})])),_:1})}],["__scopeId","data-v-fd84872a"]]),windows:t({data:()=>({}),props:["itemData"],methods:{gotoPages(t){this.gotoPage(t.linkUrl)}}},[["render",function(t,d,r,u,m,D){const _=c,h=g;return a(),e(h,{class:"diy-window",style:y({background:r.itemData.style.background,padding:r.itemData.style.paddingTop+"px "+r.itemData.style.paddingLeft+"px"})},{default:s((()=>[r.itemData.style.layout>-1?(a(),e(h,{key:0,class:p(["data-list","column__"+r.itemData.style.layout])},{default:s((()=>[(a(!0),l(n,null,o(r.itemData.data,((t,l)=>(a(),e(h,{class:"item",key:l,onClick:a=>D.gotoPages(t)},{default:s((()=>[i(h,{class:"item-image",style:y({padding:r.itemData.style.paddingTop+"px "+r.itemData.style.paddingLeft+"px"})},{default:s((()=>[i(_,{src:t.imgUrl,mode:"aspectFill"},null,8,["src"])])),_:2},1032,["style"])])),_:2},1032,["onClick"])))),128))])),_:1},8,["class"])):(a(),e(h,{key:1,class:"display",style:y({padding:r.itemData.style.paddingTop+"px "+r.itemData.style.paddingLeft+"px"})},{default:s((()=>[i(h,{class:"img-box-wrap-1"},{default:s((()=>[i(h,{class:"img-box",onClick:d[0]||(d[0]=t=>D.gotoPages(r.itemData.data[0]))},{default:s((()=>[i(_,{src:r.itemData.data[0].imgUrl,mode:"aspectFill"},null,8,["src"])])),_:1})])),_:1}),i(h,{class:"percent-w50 d-s-c d-c"},{default:s((()=>[r.itemData.data.length>=2?(a(),e(h,{key:0,class:"img-box-wrap-2"},{default:s((()=>[i(h,{class:"img-box",onClick:d[1]||(d[1]=t=>D.gotoPages(r.itemData.data[1]))},{default:s((()=>[i(_,{src:r.itemData.data[1].imgUrl,mode:"aspectFill"},null,8,["src"])])),_:1})])),_:1})):f("",!0),i(h,{class:"d-s-c img-box-wrap-3"},{default:s((()=>[r.itemData.data.length>=3?(a(),e(h,{key:0,class:"img-box-wrap-4"},{default:s((()=>[i(h,{class:"img-box",onClick:d[2]||(d[2]=t=>D.gotoPages(r.itemData.data[2]))},{default:s((()=>[i(_,{src:r.itemData.data[2].imgUrl,mode:"aspectFill"},null,8,["src"])])),_:1})])),_:1})):f("",!0),r.itemData.data.length>=4?(a(),e(h,{key:1,class:"img-box-wrap-4"},{default:s((()=>[i(h,{class:"img-box",onClick:d[3]||(d[3]=t=>D.gotoPages(r.itemData.data[3]))},{default:s((()=>[i(_,{src:r.itemData.data[3].imgUrl,mode:"aspectFill"},null,8,["src"])])),_:1})])),_:1})):f("",!0)])),_:1})])),_:1})])),_:1},8,["style"]))])),_:1},8,["style"])}],["__scopeId","data-v-ba7d0e6e"]]),navBar:t({data:()=>({item_width:"",item_height:""}),props:["itemData"],created(){this.item_width=1==this.itemData.style.rowsNum?"690rpx":"330rpx",this.item_height=1==this.itemData.style.rowsNum?"150rpx":"254rpx"},methods:{gotoDetail(t){let a=this;console.log(t),t.startsWith("scanQrcode")?a[t]():a.gotoPage(t)},scanQrcode:function(){this.$emit("scanQrcode")}}},[["render",function(t,u,m,p,_,h){const x=g,k=D,b=c;return a(),e(x,null,{default:s((()=>[i(x,{class:"diy-navbar"},{default:s((()=>[1==m.itemData.style.rowsNum?(a(!0),l(n,{key:0},o(m.itemData.data,((t,l)=>(a(),e(x,{class:"item rowsNum1",key:l,onClick:a=>h.gotoDetail(t.linkUrl)},{default:s((()=>[i(x,{class:"hh100 d-c-s d-c flex-1"},{default:s((()=>[i(x,{class:"d-s-c mb10"},{default:s((()=>[i(x,{class:"gray3 title flex-1"},{default:s((()=>[d(r(t.title),1)])),_:2},1024),i(x,{class:"icon iconfont icon-jiantou"})])),_:2},1024),i(k,{class:"gray9 text",style:y({color:t.color})},{default:s((()=>[d(r(t.text),1)])),_:2},1032,["style"])])),_:2},1024),i(x,{class:"nav-image"},{default:s((()=>[i(b,{src:t.imageUrl,mode:"aspectFill"},null,8,["src"])])),_:2},1024)])),_:2},1032,["onClick"])))),128)):f("",!0),2==m.itemData.style.rowsNum?(a(!0),l(n,{key:1},o(m.itemData.data,((t,l)=>(a(),e(x,{class:"item rowsNum2",key:l,onClick:a=>h.gotoDetail(t.linkUrl)},{default:s((()=>[i(x,{class:"nav-image"},{default:s((()=>[i(b,{src:t.imageUrl,mode:"aspectFill"},null,8,["src"])])),_:2},1024),i(x,{class:"d-s-c"},{default:s((()=>[i(x,{class:"gray3 title flex-1"},{default:s((()=>[d(r(t.title),1)])),_:2},1024)])),_:2},1024),i(x,{class:"gray9 text",style:y({color:t.color})},{default:s((()=>[d(r(t.text),1)])),_:2},1032,["style"])])),_:2},1032,["onClick"])))),128)):f("",!0)])),_:1})])),_:1})}],["__scopeId","data-v-8bfc0117"]]),blank:t({data:()=>({}),props:["itemData"],methods:{}},[["render",function(t,s,i,l,n,o){const d=g;return a(),e(d,{class:"diy-blank",style:y({height:i.itemData.style.height+"px",background:i.itemData.style.background})},null,8,["style"])}]]),guide:t({data:()=>({}),props:["itemData"],methods:{}},[["render",function(t,l,n,o,d,r){const c=g;return a(),e(c,{class:"diy-guide",style:y({padding:n.itemData.style.paddingTop+"px 0",background:n.itemData.style.background})},{default:s((()=>[i(c,{class:"line",style:y({borderTopWidth:n.itemData.style.lineHeight+"px",borderTopColor:n.itemData.style.lineColor,borderTopStyle:n.itemData.style.lineStyle})},null,8,["style"])])),_:1},8,["style"])}]])},data:()=>({}),props:["diyItems"],created(){},methods:{scanQrcode(){this.$emit("scanQrcode")}}},[["render",function(t,i,d,r,c,u){const m=_("banner"),p=_("windows"),y=_("navBar"),D=_("blank"),h=_("guide"),x=g;return a(),e(x,null,{default:s((()=>[(a(!0),l(n,null,o(d.diyItems,((t,s)=>(a(),l(n,{key:s},["banner"===t.type&&null!=t.data?(a(),e(m,{key:0,itemData:t},null,8,["itemData"])):f("",!0),"window"==t.type&&null!=t.data?(a(),e(p,{key:1,itemData:t},null,8,["itemData"])):f("",!0),"navBar"===t.type&&null!=t.data?(a(),e(y,{key:2,itemData:t,onScanQrcode:u.scanQrcode},null,8,["itemData","onScanQrcode"])):f("",!0),"blank"==t.type?(a(),e(D,{key:3,itemData:t},null,8,["itemData"])):f("",!0),"guide"==t.type?(a(),e(h,{key:4,itemData:t},null,8,["itemData"])):f("",!0)],64)))),128))])),_:1})}]]);const Q=t({components:{diy:S},data:()=>({user:"",loadding:!0,listData:[],urldata:"",longitude:0,latitude:0,delivery_set:[],items:[]}),mounted(){this.isWeixin()&&(this.urldata=window.location.href),this.getcityData(),this.getData()},onLoad(t){let a=h.getSceneData(t),e=t.shop_supplier_id?t.shop_supplier_id:a.sid;e&&x("selectedId",e)},methods:{getData(){let t=this;k({title:"加载中"}),t._post("index/index",{url:t.urldata,longitude:t.longitude||"0",latitude:t.latitude||"0"},(function(a){x("sign",a.data.signPackage),""==b("selectedId")&&x("selectedId",a.data.supplier?a.data.supplier.shop_supplier_id:0),t.items=a.data.data.items,t.user=a.data.user,""!=t.urldata&&(t.jweixin=t.configWxScan(a.data.signPackage)),v()}))},scanQrcode:function(){let t=this;this.isWeixin()?t.jweixin.scanQRCode({needResult:1,scanType:["qrCode","barCode"],success:function(t){window.location.href=t.resultStr},error:function(t){w({title:"扫码失败，请重试"})}}):console.log("H5暂只支持公众号扫码")},onShareAppMessage(){return{title:this.page.params.share_title,path:"/pages/index/index?"+this.getShareUrlParams()}},getcityData(){let t=this;if(t.isWeixin()){let a=b("sign");a?t.getWxLocation(a):(k({title:"加载中"}),t._post("index/index",{url:window.location.href},(function(e){x("sign",e.data.signPackage),a=e.data.signPackage,v(),t.getWxLocation(a)})))}else t.getLocation()},onAuthorize(){let t=this;uni.openSetting({success(a){a.authSetting["scope.userLocation"]&&(t.isAuthor=!0,setTimeout((()=>{t.getLocation((t=>{}))}),1e3))}})},getLocation(t){let a=this;C({type:"wgs84",success(t){a.longitude=t.longitude,a.latitude=t.latitude,a.getData()},fail(t){a.longitude=0,a.latitude=0,w({title:"获取定位失败，请点击右下角按钮打开定位权限",duration:2e3,icon:"none"}),a.getData()}})},getWxLocation(t,a){let e=this;var s=require("jweixin-module");s.config(JSON.parse(t)),s.ready((function(t){s.getLocation({type:"wgs84",success:function(t){e.longitude=t.longitude,e.latitude=t.latitude,e.getData()},fail(t){e.longitude=0,e.latitude=0,e.getData()}})})),s.error((function(t){console.log(t)}))}}},[["render",function(t,l,n,o,d,r){const c=U(P("diy"),S),u=g;return a(),e(u,{"data-theme":t.theme(),class:p(t.theme()||"")},{default:s((()=>[i(c,{style:{position:"relative"},diyItems:d.items,onScanQrcode:r.scanQrcode},null,8,["diyItems","onScanQrcode"])])),_:1},8,["data-theme","class"])}],["__scopeId","data-v-be92582f"]]);export{Q as default};
