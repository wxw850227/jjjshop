<template>
  <!--
    	作者：4948286@qq.com
    	时间：2019-10-30
    	描述：图片上传
    -->
  <div class="upload-wrap">
    <el-dialog title="文件管理" :visible.sync="dialogVisible" :close-on-click-modal="false" custom-class="upload-dialog" :close-on-press-escape="false" @close="cancelFunc" :width="dialogWidth">
      <!-- <el-tabs v-model="activeType" @tab-click="selectTypeFunc">
        <el-tab-pane label="我的资源库" name="0"></el-tab-pane>
        <el-tab-pane label="文件上传" name="1"></el-tab-pane>
        <el-tab-pane label="素材库" name="2"></el-tab-pane>
        <el-tab-pane label="图标库" name="3"></el-tab-pane>
      </el-tabs> -->

      <div class="upload-wrap-inline mb16 clearfix">
        <div class="leval-item">
          <el-button size="small" icon="el-icon-plus" @click="addCategory">添加分类</el-button>
        </div>
        <div class="leval-item upload-btn">
          <el-upload
            class="avatar-uploader"
            multiple
            ref="upload"
            action="string"
            accept="image/jpeg,image/png,image/jpg"
            :before-upload="onBeforeUploadImage"
            :http-request="UploadImage"
            :show-file-list="false"
            :on-change="fileChange">
            <el-button size="small" type="primary">点击上传</el-button>
          </el-upload>
        </div>
      </div>

      <!--我的资源库-->
      <div class="fileContainer">
        <div class="file-type">
          <ul>
          	<li :class="activeType==item.group_id?'item active':'item'" v-for="(item,index) in typeList" :key="index" @click="selectTypeFunc(item.group_id)">
              {{item.group_name}}
              <div class="operation" v-if="item.group_id!=null">
                <p><i class="el-icon-edit"></i></p>
                <p @click.capture.stop="deleteCategoryFunc(item.group_id)"><i class="el-icon-delete"></i></p>
              </div>
            </li>
          </ul>
        </div>
        <div class="file-content">
          <ul class="fileContainerUI clearfix">
            <li class="file" v-for="(item, index) in fileList.data" :key="index" @click="selectFileFunc(item,index)">
              <div v-if="item.selected==true" class="selectedIcon"><i class="el-icon-check"></i></div>
              <img :scr="item.file_path"  :style="'background-image:url('+item.file_path+')'" alt="" />
              <p class="desc">{{ item.file_name }}</p>
              <div class="bottom-shade">
                  <i class="el-icon-delete" @click="deleteFileFunc(item)"></i>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!--分页-->
      <div class="pagination-wrap">
        <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page="curPage"
          :page-sizes="[12, 24, 36, 42, 48]"
          :page-size="pageSize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="totalDataNumber"
        ></el-pagination>
      </div>

      <div slot="footer" class="dialog-footer">
        <el-button @click="cancelFunc">取 消</el-button>
        <el-button type="primary" @click="confirmFunc">确 定</el-button>
      </div>
    </el-dialog>

    <!--图片类别-->
    <AddCategory v-if="isShowCategory" @closeCategory="closeCategoryFunc"></AddCategory>
  </div>
</template>

<script>
import FileApi from '@/api/file.js';
import AddCategory from './part/AddCategory'
export default {
  components:{
    AddCategory
  },
  data() {
    return {
      /*宽度*/
      dialogWidth: '910px',
      /*类别*/
      activeType: null,
      /*图片类别*/
      typeList:[],
      /*图标路径*/
      imageUrl: null,
      /*弹窗显示*/
      dialogVisible: true,
      /*文件列表*/
      fileList: [],
      /*分类添加loading*/
      addLoading: false,
      /*默认值*/
      this_config:{
        /*上传个数*/
        total:1
      },
      /*记录选中的个数*/
      record:0,
      /*是否加载完成*/
      loading: true,
      /*列表数据*/
      tableData: [],
      /*一页多少条*/
      pageSize: 36,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*是否显示图片类别编辑框*/
      isShowCategory:false
    };
  },
  props:['config'],
  created() {
    this.getFileCategory();
    this.getData();
  },
  methods: {
    getFileCategory(){
      let self = this;
      let param={}
      FileApi.fileCategory(param, true)
        .then(data => {
          let type=[{group_id:null,group_name:'全部'}];
          self.typeList =type.concat(data.data.group_list);
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*添加图片类别*/
    addCategory: function() {
      this.isShowCategory=true;
    },

    /*关闭类别层*/
    closeCategoryFunc(e){
      if(e!=null){
        this.getFileCategory();
      }
      this.isShowCategory=false;
    },

    /*删除类别提示*/
    deleteCategoryFunc(e){
      this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.deleteCategory(e);
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          });
        });
    },

    /*删除类别*/
    deleteCategory(e){
      let self = this;
      FileApi.deleteCategory({group_id: e},).then(data => {
          self.$message({
            message: '删除成功',
            type: 'success'
          });
          self.getFileCategory();
        })
        .catch(error => {
          self.$message.error('删除失败');
        });
    },

    /*选择类别*/
    selectTypeFunc(id){
      this.activeType=id;
      this.curPage = 1;
      this.getData();
    },

    /*选择第几页*/
    handleCurrentChange(val) {
      this.curPage = val;
      this.getData();
    },

    /*每页多少条*/
    handleSizeChange(val) {
      this.curPage = 1;
      this.pageSize = val;
      this.getData();
    },

    /*获取图片列表数据*/
    getData: function() {
      let self = this;
      let param={
        page:self.curPage,
        pageSize:self.pageSize,
        group_id:self.activeType
      }
      FileApi.fileList(param, true)
        .then(data => {
          self.loading = false;
          self.fileList = data.data.file_list;
          self.totalDataNumber=self.fileList.total;
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*选择上传图片*/
    fileChange(e){
      console.log(e);
    },

    /*选择图片之前*/
    onBeforeUploadImage(file) {
      const isIMAGE = file.type === 'image/jpeg' || 'image/jpg' || 'image/png';
      const isLt1M = file.size / 1024 / 1024 < 1;
      if (!isIMAGE) {
        this.$message.error('上传文件只能是图片格式!');
      }
      if (!isLt1M) {
        this.$message.error('上传文件大小不能超过 1MB!');
      }
      return isIMAGE && isLt1M;
    },

    /*上传图片*/
    UploadImage(param) {
      let self=this;
      const loading = this.$loading({
        lock: true,
        text: '图片上传中,请等待',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      const formData = new FormData();
      formData.append('iFile', param.file);
      FileApi.uploadFile(formData)
        .then(response => {
          loading.close();
          self.getData();
          param.onSuccess(); // 上传成功的图片会显示绿色的对勾
          // 但是我们上传成功了图片， fileList 里面的值却没有改变，还好有on-change指令可以使用
        })
        .catch(response => {
          loading.close();
          self.$message({
            message: '本次上传图片失败',
            type: 'warning'
          });
          param.onError();
        });
    },

    /*选择图片*/
    selectFileFunc(item,index){

      if(item.selected){
         item.selected=false;
         this.record--;
      }else{
        if(this.record>=this.this_config.total){
          this.$message({
            message: '本次最多只能上传 '+this.this_config.total+' 个文件',
            type: 'warning'
          });
          return;
        }else{
          item.selected=true;
          this.record++;
        }
      }
      this.$set(this.fileList.data,index,item);
    },

    /*删除图片*/
    deleteFileFunc(e){
      console.log(e);
    },

    /*确认*/
    confirmFunc(){
      let list=[];
      for(let i=0;i<this.fileList.data.length;i++){
        let item=this.fileList.data[i];
        if(item.selected){
          let obj={
            file_id:item.file_id,
            file_path:item.file_path
          }
          list.push(obj);
        }
      }

      this.$emit('returnImgs',list);
    },

    /*取消*/
    cancelFunc(){
      this.$emit('returnImgs',null);
    }
  }
};
</script>

<style lang="scss">
.upload-dialog .el-dialog__body {
  padding-top: 0;
  padding-bottom: 0;
}
.upload-wrap-inline .leval-item{display: inline-block;}
.upload-wrap-inline .upload-btn{ float:right;}
.fileContainer {
  position: relative;
  padding-left: 120px;
}

.fileContainer .file-type {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  overflow-y: auto;
  width: 120px;
}

.fileContainer .file-type li{
  padding: 10px 0;
  cursor: pointer;
  text-align: center;
  padding-right: 20px;
  min-height: 20px;
  position: relative;
}
.fileContainer .file-type li.active{ background: #b4b4b4; color:#FFFFFF}
.fileContainer .file-type li .operation{ display: none; position: absolute; top:0; right:0; bottom: 0; width: 20px;}
.fileContainer .file-type li:hover .operation{display: block;}

.fileContainer .file-content {
  height: 300px;
  text-align: center;
  overflow: hidden;
  padding: 10px;
  margin: 0;
  overflow-y: auto;
  border: 1px solid #c6c6c6;
}

.fileContainer li.file {
  float: left;
  margin: 10px 9px 0px;
  position: relative;
  width: 100px;
}

.fileContainer li.file img {
  display: inline-block;
  width: 100px;
  height: 100px;
  cursor: pointer;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  background-color: #fff;
}

.fileContainer li.file p.desc {
  font-size: 12px;
  height: 15px;
  line-height: 15px;
  overflow: hidden;
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  word-wrap: break-word;
}
.fileContainer li.file:hover .bottom-shade{
  display:block;
}
.fileContainer .bottom-shade {
  position: absolute;
  bottom: 16px;
  left: 0;
  background: rgba(0, 0, 0, 0.7);
  height: 26px;
  line-height: 26px;
  width: 100%;
  display: none;
  transform: all 0.2s ease-out 0s;
  color:#FFFFFF;
}
.fileContainer .selectedIcon{width: 20px;
    height: 20px;
    position: absolute;
    top: 0;
    left: 0;
    background: #ff9900;
    z-index: 1;
    color: #FFFFFF;
    cursor: pointer;
    }

:focus {
  outline: -webkit-focus-ring-color auto 1px;
}

.upload-dialog .pagination-wrap{margin-top: 16px; text-align: right;}
</style>
