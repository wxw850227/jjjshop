import{n as t,E as e,e as a,k as i,h as o,o as s,v as l}from"./element-plus-0b11a037.js";import{P as d}from"./product-6e09d9a6.js";import r from"./add-893b62c7.js";import n from"./edit-74e3e527.js";import{_ as p}from"./index-ece0f3b6.js";import{aj as c,ak as u,o as m,c as h,a as g,$ as j,T as _,S as C,W as b,P as f,X as D,Y as y}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./Upload-700ab3cd.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const k={class:"product-list"},v={class:"common-level-rail"},S={class:"product-content"},w={class:"table-wrap"},z={class:"pagination"};const x=p({components:{Add:r,Edit:n},data:()=>({activeName:"sell",activeIndex:"0",loading:!0,pageSize:10,totalDataNumber:0,curPage:1,model:{},open_edit:!1,open_add:!1,tableData:[],multipleSelection:[]}),created(){this.getData()},methods:{handleCurrentChange(t){let e=this;e.loading=!0,e.curPage=t,e.getData()},handleSizeChange(t){this.pageSize=t,this.getData()},handleClick(t,e){this.curPage=1,this.getData()},getData(){let t=this,e={};e.page=t.curPage,e.list_rows=t.pageSize,t.loading=!0,d.AttributeList(e,!0).then((e=>{t.loading=!1,t.tableData=e.data.list.data,t.totalDataNumber=e.data.list.total})).catch((e=>{t.loading=!1}))},attrjoin(t){if(!t)return""},onSubmit(){this.curPage=1,this.getData()},addClick(){this.open_add=!0},deleteClick(a){let i=this;t.confirm("删除后不可恢复，确认删除吗?","提示",{type:"warning"}).then((()=>{d.deleteAttribute({attribute_id:a}).then((t=>{e({message:"删除成功",type:"success"}),i.getData()}))}))},deleteBatch(){let a=this,i=[];this.multipleSelection.forEach(((t,e)=>{i.push(t.attribute_id)}));let o=i.join(",");t.confirm("删除后不可恢复，确认删除吗?","提示",{type:"warning"}).then((()=>{d.deleteAttribute({attribute_id:o}).then((t=>{e({message:"删除成功",type:"success"}),a.getData()}))}))},handleSelectionChange(t){this.multipleSelection=t},editClick(t){this.model=t,this.open_edit=!0},undercarriage(a,i){let o=this,s="",l="";20==i?(s="强制下架",l="下架"):10==i&&(s="重新上架",l="上架"),t.confirm("确认要"+s+"吗?","提示",{type:"warning"}).then((()=>{d.auditProduct({product_id:a.product_id,state:i}).then((t=>{e({message:l+"成功",type:"success"}),o.getData()}))}))},copyClick(t){this.$router.push({path:"/product/product/edit",query:{product_id:t.product_id,scene:"copy"}})},delClick:function(a){let i=this;t.confirm("删除后不可恢复，确认删除该记录吗?","提示",{type:"warning"}).then((()=>{d.delProduct({product_id:a.product_id}).then((t=>{e({message:"删除成功",type:"success"}),i.getData()}))}))},closeDialogFunc(t,e){"add"==e&&(this.open_add=t.openDialog,"success"==t.type&&this.getData()),"edit"==e&&(this.open_edit=t.openDialog,"success"==t.type&&this.getData())}}},[["render",function(t,e,d,r,n,p){const x=a,P=i,A=o,E=s,N=c("Add"),q=c("Edit"),F=u("auth"),B=l;return m(),h("div",k,[g("div",v,[j((m(),_(x,{size:"small",type:"primary",icon:"Plus",onClick:p.addClick},{default:C((()=>[b("添加属性")])),_:1},8,["onClick"])),[[F,"/product/expand/attr/add"]]),j((m(),_(x,{size:"small",onClick:p.deleteBatch},{default:C((()=>[b("批量删除")])),_:1},8,["onClick"])),[[F,"/product/expand/attr/delete"]])]),g("div",S,[g("div",w,[j((m(),_(A,{size:"small",data:n.tableData,border:"",style:{width:"100%"},onSelectionChange:p.handleSelectionChange},{default:C((()=>[f(P,{type:"selection",width:"45"}),f(P,{prop:"sort",label:"排序"}),f(P,{prop:"attribute_id",label:"ID"}),f(P,{prop:"attribute_name",label:"属性名",width:"400px"}),f(P,{prop:"attribute_value",label:"属性值"},{default:C((t=>[b(D(p.attrjoin(t.row.attribute_value)),1)])),_:1}),f(P,{fixed:"right",label:"操作",width:"120"},{default:C((t=>[j((m(),_(x,{onClick:e=>p.editClick(t.row),type:"text",size:"small"},{default:C((()=>[b("编辑")])),_:2},1032,["onClick"])),[[F,"/product/expand/attr/edit"]]),j((m(),_(x,{onClick:e=>p.deleteClick(t.row.attribute_id),type:"text",size:"small"},{default:C((()=>[b("删除")])),_:2},1032,["onClick"])),[[F,"/product/expand/attr/delete"]])])),_:1})])),_:1},8,["data","onSelectionChange"])),[[B,n.loading]])])]),g("div",z,[f(E,{onSizeChange:p.handleSizeChange,onCurrentChange:p.handleCurrentChange,background:"","current-page":n.curPage,"page-size":n.pageSize,layout:"total, prev, pager, next, jumper",total:n.totalDataNumber},null,8,["onSizeChange","onCurrentChange","current-page","page-size","total"])]),n.open_add?(m(),_(N,{key:0,open_add:n.open_add,addform:n.model,onCloseDialog:e[0]||(e[0]=t=>p.closeDialogFunc(t,"add"))},null,8,["open_add","addform"])):y("",!0),n.open_edit?(m(),_(q,{key:1,open_edit:n.open_edit,editform:n.model,onCloseDialog:e[1]||(e[1]=t=>p.closeDialogFunc(t,"edit"))},null,8,["open_edit","editform"])):y("",!0)])}]]);export{x as default};
