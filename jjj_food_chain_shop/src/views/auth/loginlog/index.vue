<template>
  <!--
        作者：luoyiming
        时间：2019-10-25
        描述：权限-登录日志
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
          <el-table-column prop="login_log_id" label="ID"></el-table-column>
          <el-table-column prop="ip" label="IP"></el-table-column>
          <el-table-column prop="result" label="登录状态"></el-table-column>
          <el-table-column prop="username" label="用户名"></el-table-column>
          <el-table-column prop="create_time" label="添加时间"></el-table-column>
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
  </div>
</template>

<script>
import AuthApi from '@/api/auth.js';
export default {
  components: {},
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
      /*是否打开添加弹窗*/
      open_add: false,
      /*是否打开编辑弹窗*/
      open_edit: false,
      /*当前编辑的对象*/
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

      AuthApi.loginlog(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {});
    },

    /*打开添加*/
    addClick() {
      this.$router.push('/auth/user/add');
    },

    /*打开编辑*/
    editClick(row) {
      let self = this;
      this.$router.push({
        path: '/auth/user/edit',
        query: {
          shop_user_id: row.shop_user_id
        }
      });
    },

    /*刷新*/
    refresh() {
      this.reload();
      // 直接使用reload方法
    },

    /*删除*/
    deleteClick(row) {
      let self = this;
      ElMessageBox.confirm('此操作将永久删除该记录, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          self.loading = true;
          AuthApi.userDelete(
            {
              shop_user_id: row.shop_user_id
            },
            true
          )
            .then(data => {
              self.loading = false;
              if (data.code == 1) {
                ElMessage({
                  message: '恭喜你，该管理员删除成功',
                  type: 'success'
                });
                //刷新页面
                self.getTableList();
              } else {
                self.loading = false;
              }
            })
            .catch(error => {
              self.loading = false;
            });
        })
        .catch(() => {});
    }
  }
};
</script>

<style></style>
