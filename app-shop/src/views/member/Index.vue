<template>
  <!--
          作者：luoyiming
          时间：2019-10-25
          描述：用户列表
      -->
  <div class="user">
    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="用户名"><el-input v-model="formInline.nick_name" placeholder="请输入用户名"></el-input></el-form-item>
        <el-form-item label="注册时间">
          <div class="block">
            <span class="demonstration"></span>
            <el-date-picker v-model="formInline.value1" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
          </div>
        </el-form-item>
        <el-form-item><el-button type="primary" @click="onSubmit">查询</el-button></el-form-item>
      </el-form>
    </div>
    <!--内容-->
    <div class="user-content">
      <div class="table-wrap">
        <el-table :data="tableData" size="small" border style="width: 100%" v-loading="loading">
          <el-table-column prop="nickName" label="头像" width="50">
            <template slot-scope="scope">
              <img class="user-photo" :src="scope.row.avatarUrl" />
            </template>
          </el-table-column>
          <el-table-column prop="user_id" label="ID"></el-table-column>
          <el-table-column prop="nickName" label="昵称"></el-table-column>
          <el-table-column prop="gender" label="性别">
            <template slot-scope="scope">
              {{ scope.row.gender | convertSex }}
            </template>
          </el-table-column>
          <el-table-column prop="country" label="国家"></el-table-column>
          <el-table-column prop="province" label="省份"></el-table-column>
          <el-table-column prop="city" label="城市"></el-table-column>
          <el-table-column prop="create_time" label="注册时间" width="140"></el-table-column>
          <el-table-column fixed="right" label="操作" width="50">
            <template slot-scope="scope">
              <el-button @click="deleteClick(scope.row)" type="text" size="small" v-auth="'/member/member/delete'">删除</el-button>
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
  </div>
</template>

<script>
import UserApi from '@/api/user.js';
export default {
  components: {},
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
      formInline: {
        nick_name: '',
        value1: ''
      },
      /*时间*/
      value1: ''
    };
  },
  created() {
    /*获取列表*/
    this.getTableList();
  },
  methods: {
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
      let Params = self.formInline;
      Params.page = self.curPage;
      Params.list_rows = self.pageSize;
      UserApi.userlist(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*搜索查询*/
    onSubmit() {
      let self = this;
      self.loading = true;
      let Params = self.formInline;
      self.getTableList();
    },

    /*删除用户*/
    deleteClick(row) {
      let self = this;
      self
        .$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          self.loading = true;
          UserApi.deleteuser(
            {
              user_id: row.user_id
            },
            true
          )
            .then(data => {
              self.loading = false;
              if (data.code == 1) {
                self.$message({
                  message: '恭喜你，用户删除成功',
                  type: 'success'
                });
                self.getTableList();
              } else {
                self.loading = false;
              }
            })
            .catch(error => {
              self.loading = false;
            });
        })
        .catch(() => {
          self.loading = false;
        });
    }
  }
};
</script>
<style scoped="">
  .user-photo{ width: 30px; height: 30px; border-radius: 50%;}
</style>
