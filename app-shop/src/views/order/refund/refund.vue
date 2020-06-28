<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：售后管理
  -->
  <div class="user">
    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="订单号">
          <el-input v-model="formInline.order_no" placeholder="请输入订单号"></el-input>
        </el-form-item>
        <el-form-item label="售后类型">
          <el-select v-model="formInline.type" placeholder="请选择">
            <el-option label="全部" value="0"></el-option>
            <el-option label="退货退款" value="10"></el-option>
            <el-option label="换货" value="20"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="起始时间">
          <div class="block">
            <span class="demonstration"></span>
            <el-date-picker v-model="formInline.create_time" type="daterange" range-separator="至" start-placeholder="开始日期"
              end-placeholder="结束日期">
            </el-date-picker>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button icon="el-icon-search" type="primary" @click="onSubmit()">查询</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="全部售后" name="-1"></el-tab-pane>
          <el-tab-pane :label="'进行中('+(countArr.wait_count)+')'" name="0"></el-tab-pane>
          <el-tab-pane :label="'已拒绝'" name="10"></el-tab-pane>
          <el-tab-pane :label="'已完成'" name="20"></el-tab-pane>
          <el-tab-pane :label="'已取消'" name="30"></el-tab-pane>
        </el-tabs>
        <el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="order_no" label="订单号" width="130"></el-table-column>
          <el-table-column prop="orderproduct.product_name" width="400" label="商品信息">
            <template slot-scope="scope">
              <div class="d-s-c">
                <img v-img-url="scope.row.orderproduct.image.file_path" alt="" width="50px" />
                <p class="ml10">{{scope.row.orderproduct.product_name}}</p>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="create_time" label="售后申请时间" width="150" ></el-table-column>
          <el-table-column prop="orderproduct.product_price" label="单价">
            <template slot-scope="scope">
              <span class="orange">{{scope.row.orderproduct.product_price}}</span>
            </template>
          </el-table-column>
          <el-table-column prop="orderproduct.total_num" label="数量"></el-table-column>
          <el-table-column prop="orderproduct.total_pay_price" label="付款价"></el-table-column>
          <el-table-column prop="user.nickName" label="买家">
            <template slot-scope="scope">
              <span>{{scope.row.user.nickName}}</span><br>
              <span class="gray9">(用户ID:{{scope.row.user.user_id}})</span>
            </template>
          </el-table-column>
          <el-table-column prop="type.text" label="售后类型">
            <template slot-scope="scope">
              <span class="orange">{{scope.row.type.text}}</span>
            </template>
          </el-table-column>
          <el-table-column prop="" label="处理状态">
            <template slot-scope="scope">
              <div v-if="scope.row.status.value == 0">
                <!-- 审核状态-->
                <p>商家审核：
                  <span class="orange" v-if="scope.row.is_agree.value==0">{{scope.row.is_agree.text}}</span>
                  <span class="orange" v-if="scope.row.is_agree.value==10">{{scope.row.is_agree.text}}</span>
                  <span class="orange" v-if="scope.row.is_agree.value==20">{{scope.row.is_agree.text}}</span>
                </p>
                <!-- 发货状态-->
                <p v-if="scope.row.type.value==10 && scope.row.is_agree.value==10">用户发货：
                  <span class="orange" v-if="scope.row.is_user_send == 0">待发货</span>
                  <span class="orange" v-else>已发货</span>
                </p>
                <!-- 商家收货状态-->
                <p v-if="scope.row.type.value==10 && scope.row.is_agree.value==10 && scope.row.is_user_send==1 &&scope.row.is_receipt==0 ">
                  <span>商家收货：</span> <span class="orange">待收货</span></p>
              </div>
              <div v-if="scope.row.status.value==20">
                <span>{{scope.row.status.text}} </span>
              </div>
              <div v-if="scope.row.status.value==10">
                <span>{{scope.row.status.text}} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="orderMaster.create_time" label="下单时间"></el-table-column>
          <el-table-column fixed="right" label="操作" width="150">
            <template slot-scope="scope">
              <p>
              <el-button @click="Detail(scope.row.order_refund_id)" type="text" size="small">售后详情</el-button>
              </p>
              <p>
              <el-button v-if="scope.row.is_agree.value==0" @click="Detail(scope.row.order_refund_id)" type="text" size="small">去审核</el-button>
              </p>
              <p>
              <el-button v-if="scope.row.type.value==10 && scope.row.is_agree.value==10 && scope.row.is_receipt==0 && scope.row.is_user_send==1"
                @click="Detail(scope.row.order_refund_id)" type="text" size="small">确认收货并退款
              </el-button>
              </p>
            </template>
          </el-table-column>
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
  import OrderApi from '@/api/order.js';
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
          order_no: '',
          style_id: '',
          shop_id: '',
          create_time: '',
        },
        status: -1,
        countArr: [],
        activeName: '-1',
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
        this.getTableList();
      },

      /*每页多少条*/
      handleSizeChange(val) {
        this.curPage = 1;
        this.pageSize = val;
        this.getTableList();
      },
      /*切换菜单*/
      handleClick(tab, event) {
        let self = this;
        self.curPage = 1;
        self.loading = true;
        self.status = tab.name;
        self.getTableList();
      },
      /*获取列表*/
      getTableList(params = '') {
        let self = this;
        let Params = {};
        Params.status = self.status;
        Params.page = self.curPage;
        Params.list_rows = self.pageSize;
        if (params != '') {
          Params.order_no = params.order_no;
          Params.create_time =  params.create_time;
          Params.type =  params.type;
        }
        OrderApi.orderrefund(Params, true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
          self.exStyle = data.data.ex_style;
          self.shopList = data.data.shopList;
          self.countArr = data.data.countArr;
        }).catch(error => {

        });
      },

      /*搜索查询*/
      onSubmit() {
        let self = this;
        let Params = this.formInline;
        self.getTableList(Params);
      },

      /*详情*/
      Detail(e) {
        let self = this;
        let order_refund_id = e;
        this.$router.push({
          path: '/order/refund/Detail',
          query: {
            order_refund_id: order_refund_id
          }
        })
      }
    }
  };
</script>
<style></style>
