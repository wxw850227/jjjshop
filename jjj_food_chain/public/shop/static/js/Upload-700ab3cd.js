import{E as e,c as t,d as a,e as l,b as i,p as o,n as s,x as c,g as n,y as r,j as d,o as g}from"./element-plus-0b11a037.js";import{r as p,_ as u}from"./index-ece0f3b6.js";import{o as h,T as f,S as m,P as y,W as C,aj as _,c as F,a as k,Q as v,a8 as b,X as w,M as z,a1 as L,Y as S,V}from"./@vue-5441e37d.js";let x={fileCategory:(e,t)=>p._post("/shop/file.file/category",e,t),fileList:(e,t)=>p._post("/shop/file.file/lists",e,t),deleteFiles:(e,t)=>p._post("/shop/file.file/deleteFiles",e,t),addCategory:(e,t)=>p._post("/shop/file.file/addGroup",e,t),editCategory:(e,t)=>p._post("/shop/file.file/editGroup",e,t),deleteCategory:(e,t)=>p._post("/shop/file.file/deleteGroup",e,t),uploadFile:(e,t)=>p._upload("/shop/file.upload/image",e,t),moveFile:(e,t)=>p._upload("/shop/file.upload/moveFiles",e,t)};const T=u({data:()=>({dialogVisible:!0,form:{categoryname:""},categoryName:""}),props:["category"],created(){null!=this.category&&(this.form.categoryname=this.category.group_name,this.form.group_id=this.category.group_id)},methods:{addCategory:function(t){let a=this;x.addCategory({group_name:t}).then((t=>{e({message:"添加成功",type:"success"}),a.handleClose({status:"success"})})).catch((t=>{e.error("添加失败")}))},editCategory:function(t){let a=this,l={group_name:t.categoryname,group_id:t.group_id};x.editCategory(l).then((t=>{e({message:"修改成功",type:"success"}),a.handleClose({status:"success"})})).catch((t=>{e.error("修改失败")}))},submitForm(e){this.$refs[e].validate((e=>{if(!e)return!1;this.category&&null!=this.category.group_id?this.editCategory(this.form):this.addCategory(this.form.categoryname)}))},handleClose(e){this.dialogVisible=!1,this.$emit("closeCategory",e)}}},[["render",function(e,s,c,n,r,d){const g=t,p=a,u=l,_=i,F=o;return h(),f(F,{title:"添加分类",modelValue:r.dialogVisible,"onUpdate:modelValue":s[2]||(s[2]=e=>r.dialogVisible=e),width:"30%","before-close":d.handleClose,"append-to-body":!0},{default:m((()=>[y(_,{size:"small",model:r.form,ref:"form","label-width":"100px",class:"demo-ruleForm"},{default:m((()=>[y(p,{label:"分类名称",prop:"categoryname",rules:[{required:!0,message:"名字不能为空"}]},{default:m((()=>[y(g,{type:"age",modelValue:r.form.categoryname,"onUpdate:modelValue":s[0]||(s[0]=e=>r.form.categoryname=e),autocomplete:"off"},null,8,["modelValue"])])),_:1}),y(p,null,{default:m((()=>[y(u,{size:"small",onClick:d.handleClose},{default:m((()=>[C("取消")])),_:1},8,["onClick"]),y(u,{size:"small",type:"primary",onClick:s[1]||(s[1]=e=>d.submitForm("form"))},{default:m((()=>[C("提交")])),_:1})])),_:1})])),_:1},8,["model"])])),_:1},8,["modelValue","before-close"])}]]),D={class:"upload-wrap"},j={class:"upload-wrap-inline mb16 clearfix"},I={class:"leval-item"},U={class:"move-type"},B=["onClick"],P={class:"leval-item upload-btn"},E={class:"fileContainer"},N={class:"file-type"},$=["onClick"],q=["onClick"],G=["onClick"],W={class:"file-content"},A={class:"fileContainerUI clearfix"},M=["onClick"],O={key:0,class:"selectedIcon"},Q=["scr"],X={class:"desc"},Y=["onClick"],H={class:"pagination-wrap"};const J=u({components:{AddCategory:T},data:()=>({dialogWidth:"910px",activeType:null,typeList:[],imageUrl:null,dialogVisible:!0,fileList:[],addLoading:!1,this_config:{total:1,file_type:"image"},record:0,loading:!0,tableData:[],pageSize:36,totalDataNumber:0,curPage:1,isShowCategory:!1,category:null,fileIds:[],accept:""}),props:["config"],created(){if("[object object]"===Object.prototype.toString.call(this.config).toLowerCase()){for(let e in this.config)this.this_config[e]=this.config[e];"image"==this.this_config.file_type?this.accept="image/jpeg,image/png,image/jpg":this.accept="video/mp4"}this.getFileCategory(),this.getData()},methods:{getFileCategory(){let e=this;x.fileCategory({type:e.this_config.file_type},!0).then((t=>{e.typeList=[{group_id:null,group_name:"全部"}].concat(t.data.group_list)})).catch((t=>{e.loading=!1}))},addCategory(){this.isShowCategory=!0},editCategoryFunc(e){this.isShowCategory=!0,this.category=e},closeCategoryFunc(e){null!=e&&this.getFileCategory(),this.isShowCategory=!1},deleteCategoryFunc(t){s.confirm("此操作将永久删除该记录, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((()=>{this.deleteCategory(t)})).catch((()=>{e({type:"info",message:"已取消删除"})}))},deleteCategory(t){let a=this;x.deleteCategory({group_id:t}).then((t=>{e({message:"删除成功",type:"success"}),a.getFileCategory()})).catch((t=>{e.error("删除失败")}))},selectTypeFunc(e){this.activeType=e,this.curPage=1,this.getData()},handleCurrentChange(e){this.curPage=e,this.getData()},handleSizeChange(e){this.curPage=1,this.pageSize=e,this.getData()},getData:function(){let e=this,t={page:e.curPage,pageSize:e.pageSize,group_id:e.activeType,type:e.this_config.file_type};x.fileList(t,!0).then((t=>{e.loading=!1,e.fileList=t.data.file_list,e.totalDataNumber=e.fileList.total})).catch((t=>{e.loading=!1}))},moveTypeFunc(t){let a=this,l=[];a.fileList.data.forEach((e=>{e.selected&&l.push(e.file_id)})),0!=l.length?s.confirm("确定移动选中的文件吗, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((()=>{x.moveFile({group_id:t,fileIds:l},!0).then((t=>{e({message:"移动成功",type:"success"}),a.getFileCategory(),a.getData()})).catch((e=>{}))})).catch((()=>{})):e({message:"请选择文件",type:"warning"})},fileChange(e){},onBeforeUploadImage:e=>!0,UploadImage(t){let a=this;const l=c.service({lock:!0,text:"图片上传中,请等待",background:"rgba(0, 0, 0, 0.7)"}),i=new FormData;i.append("iFile",t.file),i.append("group_id",a.activeType),i.append("file_type",a.this_config.file_type),x.uploadFile(i).then((e=>{l.close(),a.getData(),t.onSuccess()})).catch((a=>{l.close(),e({message:"本次上传图片失败",type:"warning"}),t.onError()}))},selectFileFunc(t,a){if(t.selected)t.selected=!1,this.record--;else{if(t.selected=!0,this.record++,this.record>=this.this_config.total)return void e({message:"本次最多只能上传 "+this.this_config.total+" 个文件",type:"warning"});t.selected=!0,this.record++}},deleteFileFunc(t){let a=this;s.confirm("此操作将永久删除该记录, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((()=>{const l=c.service({lock:!0,text:"图片上传中,请等待",background:"rgba(0, 0, 0, 0.7)"});let i=[];if(t)i.push(t.file_id);else for(let e=0;e<a.fileList.data.length;e++){let t=a.fileList.data[e];t.selected&&i.push(t.file_id)}x.deleteFiles({fileIds:i},!0).then((t=>{l.close(),e({message:"删除成功",type:"success"}),a.getData()})).catch((e=>{l.close()}))})).catch((()=>{loading.close(),e({message:"删除失败",type:"warning"})}))},confirmFunc(){let e=[],t=0;for(let a=0;a<this.fileList.data.length;a++){let l=this.fileList.data[a];if(l.selected){let a={file_id:l.file_id,file_path:l.file_path};e.push(a),t++}if(t>this.this_config.total)break}this.$emit("returnImgs",e)},cancelFunc(){this.$emit("returnImgs",null)}}},[["render",function(e,t,a,i,s,c){const p=l,u=n,x=r,J=_("Edit"),K=d,R=_("Delete"),Z=_("Check"),ee=g,te=o,ae=T;return h(),F("div",D,[y(te,{title:"文件管理",modelValue:s.dialogVisible,"onUpdate:modelValue":t[3]||(t[3]=e=>s.dialogVisible=e),"close-on-click-modal":!1,"custom-class":"upload-dialog","close-on-press-escape":!1,onClose:c.cancelFunc,width:s.dialogWidth,"append-to-body":!0},{footer:m((()=>[y(p,{size:"small",onClick:c.cancelFunc},{default:m((()=>[C("取 消")])),_:1},8,["onClick"]),y(p,{size:"small",type:"primary",onClick:c.confirmFunc},{default:m((()=>[C("确 定")])),_:1},8,["onClick"])])),default:m((()=>[k("div",j,[k("div",I,[y(p,{size:"small",icon:"Plus",onClick:c.addCategory},{default:m((()=>[C("添加分类")])),_:1},8,["onClick"]),y(u,{placement:"bottom",width:"200",trigger:"click",value:!0},{reference:m((()=>[y(p,{size:"small",icon:"CaretBottom"},{default:m((()=>[C("移动至")])),_:1})])),default:m((()=>[k("ul",U,[(h(!0),F(v,null,b(s.typeList,((e,t)=>(h(),F("li",{key:t,onClick:t=>c.moveTypeFunc(e.group_id)},w(e.group_name),9,B)))),128))])])),_:1}),y(p,{size:"small",type:"danger",icon:"Delete",onClick:t[0]||(t[0]=e=>c.deleteFileFunc(!1))},{default:m((()=>[C("批量删除")])),_:1})]),k("div",P,[y(x,{class:"avatar-uploader",multiple:"",ref:"upload",action:"string",accept:"image/jpeg,image/png,image/jpg","before-upload":c.onBeforeUploadImage,"http-request":c.UploadImage,"show-file-list":!1,"on-change":c.fileChange},{default:m((()=>[y(p,{size:"small",icon:"Upload",type:"primary"},{default:m((()=>[C("点击上传")])),_:1})])),_:1},8,["before-upload","http-request","on-change"])])]),k("div",E,[k("div",N,[k("ul",null,[(h(!0),F(v,null,b(s.typeList,((e,a)=>(h(),F("li",{class:z(s.activeType==e.group_id?"item active":"item"),key:a,onClick:t=>c.selectTypeFunc(e.group_id)},[C(w(e.group_name)+" ",1),null!=e.group_id?(h(),F("div",{key:0,class:"operation",onClick:t[1]||(t[1]=L((()=>{}),["stop"]))},[k("p",{onClick:t=>c.editCategoryFunc(e)},[y(K,null,{default:m((()=>[y(J)])),_:1})],8,q),k("p",{onClick:t=>c.deleteCategoryFunc(e.group_id)},[y(K,null,{default:m((()=>[y(R)])),_:1})],8,G)])):S("",!0)],10,$)))),128))])]),k("div",W,[k("ul",A,[(h(!0),F(v,null,b(s.fileList.data,((e,a)=>(h(),F("li",{class:"file",key:a,onClick:t=>c.selectFileFunc(e,a)},[1==e.selected?(h(),F("div",O,[y(K,null,{default:m((()=>[y(Z)])),_:1})])):S("",!0),"image"==s.this_config.file_type?(h(),F("img",{key:1,scr:e.file_path,style:V("background-image:url("+e.file_path+")"),alt:""},null,12,Q)):S("",!0),k("p",X,w(e.real_name),1),k("div",{class:"bottom-shade",onClick:t[2]||(t[2]=L((()=>{}),["stop"]))},[k("a",{href:"javascript:void(0)",onClick:t=>c.deleteFileFunc(e)},[y(K,null,{default:m((()=>[y(R)])),_:1})],8,Y)])],8,M)))),128))])])]),k("div",H,[y(ee,{onSizeChange:c.handleSizeChange,onCurrentChange:c.handleCurrentChange,"current-page":s.curPage,"page-sizes":[12,24,36,42,48],"page-size":s.pageSize,layout:"total, sizes, prev, pager, next, jumper",total:s.totalDataNumber},null,8,["onSizeChange","onCurrentChange","current-page","page-size","total"])])])),_:1},8,["modelValue","onClose","width"]),s.isShowCategory?(h(),f(ae,{key:0,category:s.category,onCloseCategory:c.closeCategoryFunc},null,8,["category","onCloseCategory"])):S("",!0)])}],["__scopeId","data-v-bdf3207a"]]);export{J as _};
