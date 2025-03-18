import{n as e,E as t,e as a,k as o,h as s,v as i}from"./element-plus-0b11a037.js";import{S as d}from"./store-d7a6e102.js";import l from"./add-959dd420.js";import r from"./edit-daded473.js";import{_ as p}from"./index-ece0f3b6.js";import{aj as n,ak as m,o as c,c as u,a as h,$ as j,T as y,S as _,W as g,P as f,X as k,Y as b}from"./@vue-5441e37d.js";import"./@vueuse-d256812d.js";import"./lodash-es-d1425fe7.js";import"./async-validator-cf877c1f.js";import"./@element-plus-2416ebe7.js";import"./dayjs-9faf8871.js";import"./call-bind-0c463fe3.js";import"./get-intrinsic-bac01933.js";import"./has-symbols-456daba2.js";import"./has-proto-4a87f140.js";import"./function-bind-72d06d3b.js";import"./has-885c3436.js";import"./escape-html-1935ddb3.js";import"./normalize-wheel-es-3222b0a2.js";import"./@ctrl-91de2ec7.js";import"./vue-router-e88e0a38.js";import"./pinia-f65196e0.js";import"./vue-demi-71ba0ef2.js";import"./axios-85bcd05e.js";import"./qs-942d6868.js";import"./side-channel-ba7aab8a.js";import"./object-inspect-860361a9.js";import"./vue-ueditor-wrap-026a3d0f.js";const D={class:"product"},C={class:"common-level-rail"},w={class:"product-content"},v={class:"table-wrap"};const x=p({components:{Add:l,Edit:r},data:()=>({loading:!0,tableData:[],open_add:!1,open_edit:!1,categoryModel:{model:""}}),created(){this.getData()},methods:{handleClick(){this.getData()},getData(){let e=this;e.loading=!0,d.typelist({},!0).then((t=>{e.loading=!1,e.tableData=t.data.list.data,e.categoryModel=t.data.list.data})).catch((t=>{e.loading=!1}))},addClick(){this.open_add=!0},editClick(e){this.categoryModel.model=e,this.open_edit=!0},closeDialogFunc(e,t){"add"==t&&(this.open_add=e.openDialog,"success"==e.type&&this.getData()),"edit"==t&&(this.open_edit=e.openDialog,"success"==e.type&&this.getData())},deleteClick(a){let o=this;e.confirm("删除后不可恢复，确认删除该记录吗?","提示",{type:"warning"}).then((()=>{d.deleteType({type_id:a.type_id}).then((e=>{t({message:"删除成功",type:"success"}),o.getData()}))}))}}},[["render",function(e,t,d,l,r,p){const x=a,z=o,M=s,E=n("Add"),F=n("Edit"),q=m("auth"),A=i;return c(),u("div",D,[h("div",C,[j((c(),y(x,{size:"small",type:"primary",onClick:p.addClick,icon:"Plus"},{default:_((()=>[g("添加类型")])),_:1},8,["onClick"])),[[q,"/store/table/type/add"]])]),h("div",w,[h("div",v,[j((c(),y(M,{size:"small",data:r.tableData,"row-key":"type_id",style:{width:"100%"}},{default:_((()=>[f(z,{prop:"sort",label:"排序"}),f(z,{prop:"type_name",label:"类型名称",width:"180"}),f(z,{prop:"max_num",label:"人数区间",width:"180"},{default:_((e=>[g(k(e.row.min_num)+"-"+k(e.row.max_num)+"人 ",1)])),_:1}),f(z,{prop:"create_time",label:"添加时间"}),f(z,{fixed:"right",label:"操作",width:"100"},{default:_((e=>[j((c(),y(x,{onClick:t=>p.editClick(e.row),type:"text",size:"small"},{default:_((()=>[g("编辑")])),_:2},1032,["onClick"])),[[q,"/store/table/type/edit"]]),j((c(),y(x,{onClick:t=>p.deleteClick(e.row),type:"text",size:"small"},{default:_((()=>[g("删除")])),_:2},1032,["onClick"])),[[q,"/store/table/type/delete"]])])),_:1})])),_:1},8,["data"])),[[A,r.loading]])])]),r.open_add?(c(),y(E,{key:0,open_add:r.open_add,addform:r.categoryModel,onCloseDialog:t[0]||(t[0]=e=>p.closeDialogFunc(e,"add"))},null,8,["open_add","addform"])):b("",!0),r.open_edit?(c(),y(F,{key:1,open_edit:r.open_edit,editform:r.categoryModel,onCloseDialog:t[1]||(t[1]=e=>p.closeDialogFunc(e,"edit"))},null,8,["open_edit","editform"])):b("",!0)])}]]);export{x as default};
