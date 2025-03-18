<template>
  <!--
          作者：luoyiming
          时间：2019-10-25
          描述：会员-用户列表
      -->
  <div class="user">
    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="昵称"><el-input v-model="formInline.nick_name" placeholder="请输入昵称"></el-input></el-form-item>
        <el-form-item label="手机号"><el-input v-model="formInline.mobile" placeholder="请输入手机号"></el-input></el-form-item>
        <el-form-item label="注册时间">
          <div class="block">
            <span class="demonstration"></span>
            <el-date-picker v-model="formInline.reg_date" type="daterange"  value-format="YYYY-MM-DD" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
          </div>
        </el-form-item>
        <el-form-item><el-button type="primary" icon="Search" @click="onSubmit">查询</el-button></el-form-item>
      </el-form>
    </div>
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="user_id" label="ID" width="80"></el-table-column>
          <el-table-column prop="nickName" label="昵称"></el-table-column>
          <el-table-column prop="nickName" label="微信头像">
            <template #default="scope">
              <img :src="scope.row.avatarUrl" :width="30" :height="30" />
            </template>
          </el-table-column>
          <el-table-column prop="balance" label="余额"></el-table-column>
          <el-table-column prop="mobile" label="手机号"></el-table-column>
          <el-table-column prop="create_time" label="注册时间" width="140"></el-table-column>
          <el-table-column fixed="right" label="操作" width="160">
            <template #default="scope">
              <el-button @click="addClick(scope.row)" type="text" size="small" v-auth="'/user/user/recharge'">充值</el-button>
              <el-button @click="deleteClick(scope.row)" type="text" size="small" v-auth="'/user/user/delete'">删除</el-button>
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

    <!--会员充值-->
    <Recharge v-if="open_add" :open_add="open_add" :form="userModel" :gradeList="gradeList" @closeDialog="closeDialogFunc($event, 'add')"></Recharge>
  </div>
</template>

<script>
import UserApi from '@/api/user.js';
import Recharge from './dialog/Recharge.vue';
export default {
  components: {
    /*编辑组件*/
    Recharge
  },
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
        grade_id: '',
        reg_date: '',
        mobile:''
      },
      /*是否打开添加弹窗*/
      open_add: false,
      /*当前编辑的对象*/
      userModel: {},
      /*等级*/
      gradeList: []
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
          self.gradeList = data.data.grade;
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

    /*打开添加*/
    addClick(item) {
      this.userModel = item;
      this.open_add = true;
    },

    /*打开编辑*/
    editClick(item) {
      this.userModel = item;
      this.open_edit = true;
    },

    /*关闭弹窗*/
    closeDialogFunc(e, f) {
      if (f == 'add') {
        this.open_add = e.openDialog;
        if (e.type == 'success') {
          this.getTableList();
        }
      }
    },

    /*删除用户*/
    deleteClick(row) {
      let self = this;
      ElMessageBox.confirm('此操作将永久删除该记录, 是否继续?', '提示', {
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
                ElMessage({
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
<style></style>
