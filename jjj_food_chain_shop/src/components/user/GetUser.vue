<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-25
    	描述：组件-选择用户
    -->
  <el-dialog title="选择用户" v-model="dialogVisible" @close="cancelFunc" :modal-append-to-body="true" append-to-body :close-on-click-modal="false" :close-on-press-escape="false" width="800px">
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="等级">
          <el-select v-model="formInline.grade_id" placeholder="请选择会员等级" style="width: 120px;">
            <el-option label="全部" value="0"></el-option>
            <el-option v-for="(item, index) in gradeList" :key="index" :label="item.name" :value="item.grade_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="性别">
          <el-select v-model="formInline.sex" placeholder="请选择性别" style="width: 120px;">
            <el-option label="全部" value="-1"></el-option>
            <el-option v-for="(item, index) in sex" :key="index" :label="item" :value="index"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="微信昵称"><el-input placeholder="请输入微信昵称" v-model="formInline.nick_name"></el-input></el-form-item>
        <el-form-item>
          <el-button icon="el-icon-search" @click="search">查询</el-button>
        </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table :data="tableData" size="small" border style="width: 100%" v-loading="loading" @selection-change="handleSelectionChange">
          <el-table-column prop="" label="微信头像" width="70">
            <template #default="scope">
              <img :src="scope.row.avatarUrl" class="radius" :width="30" :height="30" />
            </template>
          </el-table-column>
          <el-table-column prop="nickName" label="昵称"></el-table-column>
          <el-table-column prop="balance" label="用户余额">
            <template #default="scope">
              <span class="orange">￥{{ scope.row.balance }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="grade.name" label="会员等级"></el-table-column>
          <el-table-column prop="pay_money" label="累积消费金额"></el-table-column>
          <el-table-column prop="gender" label="性别" width="50">
            <template #default="scope">
              <span v-if="scope.row.gender == 1">男</span>
              <span v-else-if="scope.row.gender == 0">女</span>
              <span v-else-if="scope.row.gender == 2">未知</span>
            </template>
          </el-table-column>
          <el-table-column prop="create_time" label="注册时间" width="140"></el-table-column>
          <!-- <el-table-column label="操作" width="80">
            <template #default="scope">
              <el-button type="primary" size="mini" @click="selectUser(scope.row)">选择</el-button>
            </template>
          </el-table-column> -->
          <el-table-column type="selection" width="45"></el-table-column>
        </el-table>
      </div>

      <!--分页-->
      <div class="pagination">
        <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          background
          :current-page="curPage"
          :page-sizes="[2, 10, 20, 50, 100]"
          :page-size="pageSize"
          layout="total, prev, pager, next, jumper"
          :total="totalDataNumber"
        ></el-pagination>
      </div>
    </div>
    <template #footer>
      <div class="dialog-footer">
        <el-button size="small" @click="dialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="confirmFunc">确 定</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script>
import DataApi from '@/api/data.js';
export default {
  data() {
    return {
      /*是否加载完成*/
      loading: true,
      /*当前是第几页*/
      curPage: 1,
      /*一页多少条*/
      pageSize: 15,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*搜索表单对象*/
      formInline: {
        /*等级*/
        grade_id: '',
        /*昵称*/
        nick_name: '',
        /*性别*/
        sex: ''
      },
      /*会员等级列表*/
      gradeList: [],
      /*会员列表*/
      tableData: [],
      /*性别列表*/
      sex: ['女', '男', '未知'],
      /*选中的*/
      multipleSelection: [],
      /*是否显示*/
      dialogVisible: false
    };
  },
  props: {
    is_open: Boolean
  },
  watch: {
    is_open: function(n, o) {
      if (n != o) {
        this.dialogVisible = n;
        if (n) {
          this.getTableList();
        }
      }
    }
  },
  created() {},
  methods: {
    /*选择第几页*/
    handleCurrentChange(val) {
      this.curPage = val;
      this.getTableList();
    },

    /*每页多少条*/
    handleSizeChange(val) {
      this.curPage = 1;
      this.pageSize = val;
      this.getTableList();
    },

    /*获取数据*/
    getTableList() {
      let self = this;
      self.loading = true;
      let params = self.formInline;
      params.page = self.curPage;
      params.list_rows = self.pageSize;
      DataApi.getUser(params,true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
          self.gradeList = data.data.grade;
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*查询*/
    search() {
      this.curPage = 1;
      this.tableData = [];
      this.getTableList();
    },

    /*点击确定*/
    confirmFunc() {
      let params = this.multipleSelection;
      this.emitFunc(params);
    },

    /*关闭弹窗*/
    cancelFunc(e) {
      this.emitFunc();
    },

    /*发送事件*/
    emitFunc(e) {
      this.dialogVisible = false;
      if (e && typeof e != 'undefined') {
        this.$emit('close', {
          type: 'success',
          params: e
        });
      } else {
        this.$emit('close', {
          type: 'error'
        });
      }
    },

    /*监听单选按钮选中事件*/
    selectUser(val) {
      this.multipleSelection = val;
      this.confirmFunc();
    },

    /*选择用户*/
    handleSelectionChange(e) {
      this.multipleSelection = e;
    }
  }
};
</script>

<style></style>
