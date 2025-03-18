<template>
  <!--
        作者：luoyiming
        时间：2020-07-25
        描述：权限-操作日志
    -->
  <div class="user">

    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item><el-input size="small" v-model="searchForm.search" placeholder="请输入用户名和真实姓名"></el-input></el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" icon="Search" @click="searchSubmit">查询</el-button>
        </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="opt_log_id" label="id" width="70"></el-table-column>
          <el-table-column prop="user_name" label="用户名"></el-table-column>
          <el-table-column prop="real_name" label="真实姓名"></el-table-column>
          <el-table-column prop="url" label="Url" width="300">
            <template #default="scope">
              <el-input size="small" placeholder="请输入内容" v-model="scope.row.url">
                <template #append>
                  <el-button @click="gotoUrl(scope.row.url)" icon="Link"></el-button>
                </template>
              </el-input>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="标题"></el-table-column>
          <el-table-column prop="ip" label="IP" width="120"></el-table-column>
          <el-table-column prop="browser" label="Browser" width="120"></el-table-column>
          <el-table-column prop="create_time" label="添加时间"></el-table-column>
          <el-table-column fixed="right" label="操作" width="50">
            <template #default="scope">
              <el-button @click="openDetail(scope.row)" type="text" size="small">详情</el-button>
            </template>
          </el-table-column>
        </el-table>
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
    </div>

    <Detail :open="open" :form="userModel" @close="closeDetail"></Detail>

  </div>
</template>

<script>
import AuthApi from '@/api/auth.js';
import Detail from './dialog/Detail.vue'
export default {
  components: {
    Detail
  },
  inject: ['reload'],
  data() {
    return {
      /*是否加载完成*/
      loading: true,
      /*列表数据*/
      tableData: [],
      /*一页多少条*/
      pageSize: 20,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*横向表单数据模型*/
      searchForm: {
        search:''
      },
      /*是否打开弹窗*/
      open: false,
      /*编辑对象*/
      userModel: {}
    };
  },
  created() {
    /*获取列表*/
    this.getTableList();
  },
  methods: {

    /*搜索*/
    searchSubmit(){
      this.curPage = 1;
      this.getTableList();
    },

    /*选择第几页*/
    handleCurrentChange(val) {
      let self = this;
      self.curPage = val;
      self.loading = true;
      self.getTableList();
    },

    /*每页多少条*/
    handleSizeChange(val) {
      this.curPage = 1;
      this.pageSize = val;
      this.getTableList();
    },

    /*获取列表*/
    getTableList() {
      let self = this;
      let Params = {
        page: self.curPage,
        list_rows: self.pageSize,
        username: self.searchForm.search
      };

      AuthApi.optlog(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {});
    },

    /*跳转链接*/
    gotoUrl(row) {
      let self = this;
      this.$router.push({
        path: row,
        query: {
        }
      });
    },

    /*刷新*/
    refresh() {
      this.reload();
    },

    /*打开详情*/
    openDetail(row){
      this.userModel=row;
      this.open=true;
    },

    /*关闭详情*/
    closeDetail(){
      this.open=false;
    }

  }
};
</script>

<style></style>
