<template>
  <!--
      描述：属性库
  -->
  <div class="product-list">
    <!--添加属性-->
    <div class="common-level-rail">
      <el-button size="small" type="primary" icon="Plus" v-auth="'/product/expand/attr/add'" @click="addClick">添加属性</el-button>
      <el-button size="small" v-auth="'/product/expand/attr/delete'" @click="deleteBatch">批量删除</el-button>
    </div>
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading" @selection-change="handleSelectionChange">
          <el-table-column type="selection" width="45"></el-table-column>
          <el-table-column prop="sort" label="排序"></el-table-column>
          <el-table-column prop="attribute_id" label="ID"></el-table-column>
          <el-table-column prop="attribute_name" label="属性名" width="400px"></el-table-column>
          <el-table-column prop="attribute_value" label="属性值">
            <template  #default="scope">
             {{attrjoin(scope.row.attribute_value)}}
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" width="120">
            <template  #default="scope">
              <el-button @click="editClick(scope.row)" type="text" size="small" v-auth="'/product/expand/attr/edit'" >编辑</el-button>
              <el-button @click="deleteClick(scope.row.attribute_id)" type="text" size="small" v-auth="'/product/expand/attr/delete'" >删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    <!--分页-->
    <div class="pagination">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        background
        :current-page="curPage"
        :page-size="pageSize"
        layout="total, prev, pager, next, jumper"
        :total="totalDataNumber"
      ></el-pagination>
    </div>

    <!--添加-->
    <Add v-if="open_add" :open_add="open_add" :addform="model" @closeDialog="closeDialogFunc($event, 'add')"></Add>
    <!--修改-->
    <Edit v-if="open_edit" :open_edit="open_edit" :editform="model" @closeDialog="closeDialogFunc($event, 'edit')"></Edit>

  </div>
</template>

<script>
import PorductApi from '@/api/product.js';
import Add from './add.vue';
import Edit from './edit.vue';
export default {
  components: {
    Add,
    Edit
  },
  data() {
    return {
      /*切换菜单*/
      activeName: 'sell',
      /*切换选中值*/
      activeIndex: '0',
      /*是否正在加载*/
      loading: true,
      /*一页多少条*/
      pageSize: 10,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*当前编辑的对象*/
      model: {

      },
      open_edit:false,
      open_add:false,
      /*列表数据*/
      tableData: [],
      multipleSelection:[]
    };
  },
  created() {
    /*获取列表*/
    this.getData();
  },
  methods: {
    /*选择第几页*/
    handleCurrentChange(val) {
      let self = this;
      self.loading = true;
      self.curPage = val;
      self.getData();
    },

    /*每页多少条*/
    handleSizeChange(val) {
      this.pageSize = val;
      this.getData();
    },

    /*切换菜单*/
    handleClick(tab, event) {
      let self = this;
      self.curPage = 1;
      self.getData();
    },

    /*获取列表*/
    getData() {
      let self = this;
      let Params = {};
      Params.page = self.curPage;
      Params.list_rows = self.pageSize;
      self.loading = true;
      PorductApi.AttributeList(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {
          self.loading = false;
        });
    },
    attrjoin(e){
      if(e){
        console.log(e)
        // return JSON.parse(e).join('|')
      }else{
        return ''
      }
    },
    /*搜索查询*/
    onSubmit() {
      this.curPage = 1;
      this.getData();
    },

    /*打开添加*/
    addClick() {
       this.open_add = true;
    },
    deleteClick(id){
      let self = this;
      ElMessageBox.confirm('删除后不可恢复，确认删除吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          PorductApi.deleteAttribute({
             attribute_id : id
          }).then(data => {
            ElMessage({
              message: '删除成功',
              type: 'success'
            });
            self.getData();
          });
        });
    },
    deleteBatch(){
      let self = this;
       console.log(this.multipleSelection);
       let arr= [];
       this.multipleSelection.forEach((item,index)=>{
          arr.push(item.attribute_id);
       })
       let attribute_id=arr.join(',');
      ElMessageBox.confirm('删除后不可恢复，确认删除吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          PorductApi.deleteAttribute({
             attribute_id : attribute_id
          }).then(data => {
            ElMessage({
              message: '删除成功',
              type: 'success'
            });
            self.getData();
          });
        });
    },
    handleSelectionChange(e) {
      this.multipleSelection = e;
    },
    /*打开编辑*/
    editClick(row) {
      this.model = row;
      this.open_edit = true;
    },
    /* 强制下架上架*/
    undercarriage(row,state){
      let self = this;
      let war="";
      let war_='';
      if(state==20){
        war="强制下架",
        war_='下架'
      }else if(state==10){
        war="重新上架",
        war_='上架'
      }
      ElMessageBox.confirm("确认要"+war+"吗?", '提示', {
          type: 'warning'
        })
        .then(() => {
          PorductApi.auditProduct({
            product_id: row.product_id,
            state
          }).then(data => {
            ElMessage({
              message: war_+'成功',
              type: 'success'
            });
            self.getData();
          });
        });
    },
    /*打开复制*/
    copyClick(row) {
      this.$router.push({
        path: '/product/product/edit',
        query: {
          product_id: row.product_id,
          scene: 'copy'
        }
      });
    },

    /*删除*/
    delClick: function(row) {
      let self = this;
      ElMessageBox.confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          PorductApi.delProduct({
            product_id: row.product_id
          }).then(data => {
           ElMessage({
              message: '删除成功',
              type: 'success'
            });
            self.getData();
          });
        });
    },
    /*关闭弹窗*/
    closeDialogFunc(e, f) {
      if (f == 'add') {
        this.open_add = e.openDialog;
        if (e.type == 'success') {
          this.getData();
        }
      }
      if (f == 'edit') {
        this.open_edit = e.openDialog;
        if (e.type == 'success') {
          this.getData();
        }
      }
    },
  }
};
</script>

<style></style>
