<template>
  <!--
          作者：luoyiming
          时间：2019-10-25
          描述：用户列表
      -->
  <div class="user">
    <!--搜索表单-->
    <div class="common-seach-wrap">
      <div class="common-form">余额充值记录</div>
      <el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="用户昵称">
          <el-input v-model="formInline.search" placeholder="请输入昵称"></el-input>
        </el-form-item>
        <el-form-item label="充值方式">
          <el-select v-model="formInline.rechargeType" placeholder="请选择">
            <el-option label="全部" value="0"></el-option>
            <el-option v-for="(item,index) in rechargeType" :key="index" :label="item.name" :value="item.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="付款状态">
          <el-select v-model="formInline.pay_status" placeholder="请选择">
            <el-option label="全部" value="0"></el-option>
            <el-option v-for="(item,index) in pay_status" :key="index" :label="item.name" :value="item.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="起始日期">
          <div class="block">
            <span class="demonstration"></span>
            <el-date-picker v-model="formInline.value1" type="daterange" range-separator="至" start-placeholder="开始日期"
              end-placeholder="结束日期">
            </el-date-picker>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">查询</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="user_id" label="ID" width="80"></el-table-column>
          <el-table-column prop="user.nickName" label="昵称">
            <template slot-scope="scope">
              <p>{{scope.row.user.nickName}}</p>
              <p>(用户ID：{{scope.row.user.user_id}})</p>
            </template>
          </el-table-column>
          <el-table-column prop="user.nickName" label="微信头像">
            <template slot-scope="scope">
              <img :src="scope.row.user.avatarUrl" width="60" />
            </template>
          </el-table-column>
          <el-table-column prop="scene.text" label="订单号">
            <template slot-scope="scope">
              <span style="background-color: #3bb4f2;color: #fff">{{scope.row.scene.text}}</span>
            </template>
          </el-table-column>
          <el-table-column prop="money" label="充值方式	">
            <template slot-scope="scope">
              <p v-if="scope.row.money >0">+{{scope.row.money}}</p>
              <p v-else="">{{scope.row.money}}</p>
            </template>
          </el-table-column>
          <el-table-column prop="describe" label="套餐名称"></el-table-column>
          <el-table-column prop="remark" label="管理员备注">
            <template slot-scope="scope">
              <p v-if="scope.row.remark ==''">--</p>
              <p v-else="">{{scope.row.remark}}</p>
            </template>
          </el-table-column>
          <el-table-column prop="create_time" label="支付金额" width="140"></el-table-column>
          <el-table-column prop="create_time" label="赠送金额" width="140"></el-table-column>
          <el-table-column prop="create_time" label="	支付状态" width="140"></el-table-column>
          <el-table-column prop="create_time" label="	付款时间" width="140"></el-table-column>
          <el-table-column prop="create_time" label="	创建时间" width="140"></el-table-column>
        </el-table>
      </div>

      <!--分页-->
      <div class="pagination">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background :current-page="curPage"
          :page-size="pageSize" layout="total, prev, pager, next, jumper"
          :total="totalDataNumber">
        </el-pagination>
      </div>
    </div>


  </div>
</template>

<script>
  import UserApi from '@/api/user.js';
  export default {

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
          scene: '',
          value1: ''
        },
        /*支付状态*/
        pay_status: [],
        rechargeType: [],
        /*时间*/
        value1: '',
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
        UserApi.RechargeOrder(Params, true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
          self.pay_status = data.data.attributes.pay_status;
          self.rechargeType = data.data.attributes.rechargeType;
        }).catch(error => {

        });
      },

      /*搜索查询*/
      onSubmit() {
        let self = this;
        self.loading = true;
        let Params = self.formInline;
        self.getTableList();
      },


      /*关闭弹窗*/
      closeDialogFunc(e, f) {
        if (f == 'add') {
          this.open_add = e.openDialog;
          if (e.type == 'success') {
            this.getTableList();
          }
        }
        if (f == 'edit') {
          this.open_edit = e.openDialog;
          if (e.type == 'success') {
            this.getTableList();
          }
        }
      },


    }
  };
</script>
<style></style>
