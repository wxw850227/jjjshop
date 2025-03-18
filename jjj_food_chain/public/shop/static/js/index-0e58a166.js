import{n as a,E as e,D as t,u as l,w as o,s as r,t as s,d as i,c as d,e as c,b as u,k as p,h as n,o as m,v as h}from"./element-plus-0b11a037.js";import{P as g}from"./product-6e09d9a6.js";import{_}from"./index-ece0f3b6.js";import{ak as y,o as k,c as b,a as C,P as f,S as w,W as v,X as j,Q as z,a8 as D,T as x,$ as S,M as P}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const V={class:"product-list"},F={class:"common-seach-wrap"},N={class:"common-level-rail"},L={class:"product-content"},q={class:"table-wrap"},$={class:"product-info"},T={class:"pic"},U={alt:""},E={class:"info"},I={class:"name"},M={class:"price"},Q={class:"pagination"};const W=_({components:{},data:()=>({activeName:"sell",activeIndex:"0",loading:!0,pageSize:10,totalDataNumber:0,curPage:1,searchForm:{product_name:"",category_id:""},tableData:[],categoryList:[],product_count:{}}),created(){this.getData()},methods:{handleCurrentChange(a){let e=this;e.loading=!0,e.curPage=a,e.getData()},handleSizeChange(a){this.pageSize=a,this.getData()},handleClick(a,e){this.curPage=1,this.getData()},getData(){let a=this,e=a.searchForm;e.page=a.curPage,e.list_rows=a.pageSize,e.type=a.activeName,a.loading=!0,g.takeProductList(e,!0).then((e=>{a.loading=!1,a.tableData=e.data.list.data,a.categoryList=e.data.category,a.totalDataNumber=e.data.list.total,a.product_count=e.data.product_count})).catch((e=>{a.loading=!1}))},onSubmit(){this.curPage=1,this.getData()},addClick(){this.$router.push("/product/takeaway/product/add")},editClick(a){this.$router.push({path:"/product/takeaway/product/edit",query:{product_id:a.product_id,scene:"edit"}})},undercarriage(t,l){let o=this,r="",s="";20==l?(r="强制下架",s="下架"):10==l&&(r="重新上架",s="上架"),a.confirm("确认要"+r+"吗?","提示",{type:"warning"}).then((()=>{g.takeStateProduct({product_id:t.product_id,state:l}).then((a=>{e({message:s+"成功",type:"success"}),o.getData()}))}))},copyClick(a){this.$router.push({path:"/product/product/edit",query:{product_id:a.product_id,scene:"copy"}})},deleteClick:function(t){let l=this;a.confirm("删除后不可恢复，确认删除该记录吗?","提示",{type:"warning"}).then((()=>{g.takeDelProduct({product_id:t.product_id}).then((a=>{e({message:"删除成功",type:"success"}),l.getData()}))}))}}},[["render",function(a,e,g,_,W,X){const A=t,B=l,G=o,H=r,J=s,K=i,O=d,R=c,Y=u,Z=p,aa=n,ea=m,ta=y("auth"),la=y("img-url"),oa=h;return k(),b("div",V,[C("div",F,[f(G,{modelValue:W.activeName,"onUpdate:modelValue":e[0]||(e[0]=a=>W.activeName=a),onTabChange:X.handleClick},{default:w((()=>[f(B,{label:"上架中",name:"sell"},{label:w((()=>[C("span",null,[v("上架中 "),f(A,{size:"small"},{default:w((()=>[v(j(W.product_count.sell),1)])),_:1})])])),_:1}),f(B,{label:"下架",name:"lower"},{label:w((()=>[C("span",null,[v(" 下架 "),f(A,{size:"small"},{default:w((()=>[v(j(W.product_count.lower),1)])),_:1})])])),_:1})])),_:1},8,["modelValue","onTabChange"]),f(Y,{size:"small",inline:!0,model:W.searchForm,class:"demo-form-inline"},{default:w((()=>[f(K,{label:"商品分类"},{default:w((()=>[f(J,{size:"small",modelValue:W.searchForm.category_id,"onUpdate:modelValue":e[1]||(e[1]=a=>W.searchForm.category_id=a),placeholder:"所有分类"},{default:w((()=>[f(H,{label:"全部",value:"0"}),(k(!0),b(z,null,D(W.categoryList,((a,e)=>(k(),x(H,{key:e,label:a.name,value:a.category_id},null,8,["label","value"])))),128))])),_:1},8,["modelValue"])])),_:1}),f(K,{label:"商品名称"},{default:w((()=>[f(O,{size:"small",modelValue:W.searchForm.product_name,"onUpdate:modelValue":e[2]||(e[2]=a=>W.searchForm.product_name=a),placeholder:"请输入商品名称"},null,8,["modelValue"])])),_:1}),f(K,null,{default:w((()=>[f(R,{size:"small",type:"primary",icon:"Search",onClick:X.onSubmit},{default:w((()=>[v("查询")])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])]),C("div",N,[S((k(),x(R,{size:"small",type:"primary",icon:"Plus",onClick:X.addClick},{default:w((()=>[v("添加产品")])),_:1},8,["onClick"])),[[ta,"/product/takeaway/product/add"]])]),C("div",L,[C("div",q,[S((k(),x(aa,{size:"small",data:W.tableData,border:"",style:{width:"100%"}},{default:w((()=>[f(Z,{prop:"product_name",label:"产品",width:"400px"},{default:w((a=>[C("div",$,[C("div",T,[S(C("img",U,null,512),[[la,a.row.image[0].file_path]])]),C("div",E,[C("div",I,j(a.row.product_name),1),C("div",M,"销售价："+j(a.row.product_price),1)])])])),_:1}),f(Z,{prop:"category.name",label:"分类"}),f(Z,{prop:"sales_actual",label:"实际销量"}),f(Z,{prop:"product_stock",label:"库存"}),f(Z,{prop:"product_status.text",label:"状态"},{default:w((a=>[C("span",{class:P({green:10==a.row.product_status.value,gray:20==a.row.product_status.value})},j(a.row.product_status.text),3)])),_:1}),f(Z,{prop:"create_time",label:"发布时间"}),f(Z,{prop:"product_sort",label:"排序"}),f(Z,{fixed:"right",label:"操作",width:"160"},{default:w((a=>[S((k(),x(R,{onClick:e=>X.editClick(a.row),type:"text",size:"small"},{default:w((()=>[v("编辑")])),_:2},1032,["onClick"])),[[ta,"/product/takeaway/product/edit"]]),20!=a.row.product_status.value?S((k(),x(R,{key:0,onClick:e=>X.undercarriage(a.row,20),type:"text",size:"small"},{default:w((()=>[v("下架")])),_:2},1032,["onClick"])),[[ta,"/product/takeaway/product/state"]]):S((k(),x(R,{key:1,onClick:e=>X.undercarriage(a.row,10),type:"text",size:"small"},{default:w((()=>[v("上架")])),_:2},1032,["onClick"])),[[ta,"/product/takeaway/product/state"]]),S((k(),x(R,{onClick:e=>X.deleteClick(a.row),type:"text",size:"small"},{default:w((()=>[v("删除")])),_:2},1032,["onClick"])),[[ta,"/product/takeaway/product/delete"]])])),_:1})])),_:1},8,["data"])),[[oa,W.loading]])])]),C("div",Q,[f(ea,{onSizeChange:X.handleSizeChange,onCurrentChange:X.handleCurrentChange,background:"","current-page":W.curPage,"page-size":W.pageSize,layout:"total, prev, pager, next, jumper",total:W.totalDataNumber},null,8,["onSizeChange","onCurrentChange","current-page","page-size","total"])])])}]]);export{W as default};
