import{_ as e,a0 as t,m as a,l as s,J as i,p as o,o as n,c,f as p}from"./index-61e49878.js";const l=e({data:()=>({imageList:[]}),onLoad(){},mounted(){this.chooseImageFunc()},methods:{chooseImageFunc(){let e=this;t({count:9,sizeType:["original","compressed"],sourceType:["album"],success:function(t){e.uploadFile(t.tempFilePaths)},fail:function(t){e.$emit("getImgs",null)},complete:function(e){}})},uploadFile:function(e){let t=this,n=0,c=e.length,p={token:a("token"),app_id:t.getAppId()};s({title:"上传中"}),e.forEach((function(e,a){i({url:t.websiteUrl+"/index.php?s=/api/file.upload/image",filePath:e,name:"iFile",formData:p,header:{appId:t.getAppId()},success:function(e){let a="object"==typeof e.data?e.data:JSON.parse(e.data);1===a.code&&t.imageList.push(a.data)},complete:function(){n++,c===n&&(o(),t.$emit("getImgs",t.imageList))}})}))}}},[["render",function(e,t,a,s,i,o){const l=p;return n(),c(l)}]]);export{l as U};
