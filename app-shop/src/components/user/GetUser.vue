<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-25
    	描述：会员-添加
    -->
  <el-dialog title="选择用户" :visible.sync="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false" :close-on-press-escape="false">
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="">
          <el-select v-model="formInline.grade_id" placeholder="请选择会员等级">
            <el-option label="全部" value="0"></el-option>
            <el-option v-for="(item, index) in gradeList" :key="index" :label="item.name" :value="item.grade_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select v-model="formInline.sex" placeholder="请选择性别" style="width: 150px;">
            <el-option label="全部" value="-1"></el-option>
            <el-option v-for="(item, index) in sex" :key="index" :label="item" :value="index"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-input placeholder="请输入微信昵称" v-model="formInline.nickName">
            <el-button slot="append" icon="el-icon-search" @click="search">查询</el-button>
          </el-input>
        </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table :data="tableData" border style="width: 100%" v-loading="loading" @current-change="handleCurrentChange">
          <el-table-column prop="nickName" label="昵称" width="100"></el-table-column>
          <el-table-column prop="" label="微信头像" width="70">
            <template slot-scope="scope">
              <img :src="scope.row.avatarUrl" width="30" height="30" />
            </template>
          </el-table-column>
          <!-- <el-table-column prop="nickName" label="微信昵称"></el-table-column> -->
          <el-table-column prop="balance" label="用户余额" width="105"></el-table-column>
          <el-table-column prop="grade.name" label="会员等级"></el-table-column>
          <el-table-column prop="pay_money" label="累积消费金额"></el-table-column>
          <el-table-column prop="gender" label="性别" width="50">
            <template slot-scope="scope">
              <span v-if="scope.row.gender == 1">男</span>
              <span v-else-if="scope.row.gender == 0">女</span>
              <span v-else-if="scope.row.gender == 2">未知</span>
            </template>
          </el-table-column>
          <el-table-column prop="create_time" label="注册时间" width="140"></el-table-column>
          <el-table-column label="操作">
                <template slot-scope="scope">
                  <el-button type="primary"
                    size="mini"
                    @click="selectUser(scope.row)">选择</el-button>
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
          :page-sizes="[2, 10, 20, 50, 100]"
          :page-size="pageSize"
          layout="total, prev, pager, next, jumper"
          :total="totalDataNumber"
        ></el-pagination>
      </div>
    </div>

    <!-- <div slot="footer" class="dialog-footer">
      <el-button size="small" @click="dialogFormVisible">取 消</el-button>
      <el-button size="small" type="primary" @click="addClerk">确 定</el-button>
    </div> -->
  </el-dialog>
</template>

<script>
import StoreApi from '@/api/store.js';
export default {
  data() {
    return {
      /*是否加载完成*/
      loading: true,
      /*当前是第几页*/
      curPage: 1,
      /*一页多少条*/
      pageSize: 20,
      /*一共多少条数据*/
      totalDataNumber: 0,
      formInline: {
        /*昵称*/
        grade_id: '',
        nickName: '',
        sex: ''
      },
      //会员等级列表
      gradeList: [],
      //会员列表
      tableData: [],
      //性别列表
      sex: ['女', '男', '未知'],
      formRules: {
        name: [
          {
            required: true,
            message: '请输入等级名称',
            trigger: 'blur'
          },
          {
            min: 3,
            max: 5,
            message: '长度在 3 到 5 个字符',
            trigger: 'blur'
          }
        ]
      },
      multipleSelection: [],
      /*左边长度*/
      formLabelWidth: '120px',
      /*是否显示*/
      dialogVisible: false
    };
  },
  props: ['open_add'],
  created() {
    this.dialogVisible = this.open_add;
    /*获取列表*/
    this.getTableList();
  },
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
    getTableList() {
      let self = this;
      StoreApi.popup({}, true)
        .then(data => {
          if (data.code == 1) {
            self.loading = false;
            self.tableData = data.data.list.data;
            self.totalDataNumber = data.data.list.total;
            self.gradeList = data.data.grade;
          } else {
            self.$message.error('错了哦，这是一条错误消息');
          }
        })
        .catch(error => {});
    },
    //查询
    search() {
      let self = this;
      let params = this.formInline;
      StoreApi.search(params, true)
        .then(data => {
          if (data.code == 1) {
            self.tableData = data.data.list.data;
          } else {
            self.$message.error('错了哦，这是一条错误消息');
          }
        })
        .catch(error => {});
    },

    //点击确定
    addClerk() {
      let self = this;
      let params = self.multipleSelection;
      this.$emit('closeDialog', {
        type: 'success',
        openDialog: false,
        params: params
      });
    },

    /*关闭弹窗*/
    dialogFormVisible(e) {
      if (e) {
        this.$emit('closeDialog', {
          type: 'success',
          openDialog: false
        });
      } else {
        this.$emit('closeDialog', {
          type: 'error',
          openDialog: false
        });
      }
    },

    /*监听单选按钮选中事件*/
    selectUser(val) {
      this.multipleSelection = val;
      this.addClerk();
    }
  }
};
</script>

<style></style>
