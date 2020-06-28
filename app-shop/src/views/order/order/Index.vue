<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：订单列表
  -->
  <div class="user">
    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="订单号"><el-input size="small" v-model="searchForm.order_no" placeholder="请输入订单号"></el-input></el-form-item>
        <el-form-item label="起始时间">
          <div class="block">
            <span class="demonstration"></span>
            <el-date-picker
              size="small"
              v-model="searchForm.create_time"
              type="daterange"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
            ></el-date-picker>
          </div>
        </el-form-item>
        <el-form-item><el-button size="small" type="primary" icon="el-icon-search" @click="onSubmit">查询</el-button></el-form-item>
      </el-form>
    </div>
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="全部订单" name="all"></el-tab-pane>
          <el-tab-pane :label="'未付款(' + order_count.payment + ')'" name="payment"></el-tab-pane>
          <el-tab-pane :label="'待发货(' + order_count.delivery + ')'" name="delivery"></el-tab-pane>
          <el-tab-pane :label="'待收货(' + order_count.received + ')'" name="received"></el-tab-pane>
          <el-tab-pane label="待评价" name="comment"></el-tab-pane>
          <el-tab-pane label="已完成" name="complete"></el-tab-pane>
        </el-tabs>
        <el-table size="small" :data="tableData.data" border :span-method="arraySpanMethod" style="width: 100%" v-loading="loading">
          <el-table-column prop="order_no" label="订单信息" width="600">
            <template slot-scope="scope">
              <div class="order-code" v-if="scope.row.is_top_row">
                <span class="c_main">订单号：{{ scope.row.order_no }}</span>
                <span class="pl16">下单时间：{{ scope.row.create_time }}</span>
              </div>
              <template v-else>
                <div class="product-info" v-for="(item, index) in scope.row.product" :key="index">
                  <div class="pic"><img v-img-url="item.image.file_path" alt="" /></div>
                  <div class="info">
                    <div class="name gray3">{{ item.product_name }}</div>
                    <div class="gray9" v-if="item.product_attr">{{ item.product_attr }}</div>
                  </div>
                  <div class="d-c-c d-c">
                    <div class="orange">￥ {{ item.product_price }}</div>
                    <div class="gray3">x{{ item.total_num }}</div>
                  </div>
                </div>
              </template>
            </template>
          </el-table-column>
          <el-table-column prop="pay_price" label="实付款(单位:元)">
            <template slot-scope="scope" v-if="!scope.row.is_top_row">
              <div class="orange">{{ scope.row.pay_price }}</div>
              <p class="gray9">(含运费：{{ scope.row.express_price }})</p>
            </template>
          </el-table-column>
          <el-table-column prop="" label="买家">
            <template slot-scope="scope" v-if="!scope.row.is_top_row">
              <div>{{ scope.row.user.nickName }}</div>
              <div class="gray9">用户ID：({{ scope.row.user.user_id }})</div>
            </template>
          </el-table-column>
          <el-table-column prop="state_text" label="交易状态">
            <template slot-scope="scope" v-if="!scope.row.is_top_row">
              {{ scope.row.state_text }}
            </template>
          </el-table-column>
          <el-table-column prop="is_comment" label="评价" width="120">
            <template slot-scope="scope" v-if="!scope.row.is_top_row">
              <span v-if="scope.row.is_comment == 0">未评价</span>
              <span v-else="">已评价</span>
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" width="130">
            <template slot-scope="scope" v-if="!scope.row.is_top_row">
              <el-button @click="addClick(scope.row)" type="text" size="small" v-auth="'/order/order/detail'">订单详情</el-button>
              <el-button
                v-if="scope.row.pay_status.value == 20 && scope.row.delivery_status.value == 10 && scope.row.order_status.value != 20 && scope.row.order_status.value != 21"
                @click="addClick(scope.row)"
                type="text"
                size="small"
              >
                去发货
              </el-button>
              <el-button v-if="scope.row.order_status.value == 21" @click="addClick(scope.row)" type="text" size="small">去审核</el-button>
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
import OrderApi from '@/api/order.js';
export default {
  data() {
    return {
      type: 'all',
      /*切换菜单*/
      activeName: 'all',
      /*是否加载完成*/
      loading: true,
      /*列表数据*/
      tableData: {},
      /*一页多少条*/
      pageSize: 20,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*横向表单数据模型*/
      searchForm: {
        order_no: '',
        create_time: ''
      },
      /*时间*/
      create_time: '',
      /*统计*/
      order_count: {
        payment: 0,
        delivery: 0,
        received: 0
      }
    };
  },
  created() {
    /*获取列表*/
    this.getData();
  },
  methods: {
    /*跨多列*/
    arraySpanMethod(row) {
      if (row.rowIndex % 2 == 0) {
        if (row.columnIndex === 0) {
          return [1, 6];
        }
      }
    },

    /*选择第几页*/
    handleCurrentChange(val) {
      let self = this;
      self.curPage = val;
      self.loading = true;
      self.getData();
    },

    /*每页多少条*/
    handleSizeChange(val) {
      this.curPage = 1;
      this.pageSize = val;
      this.getData();
    },

    /*切换菜单*/
    handleClick(tab, event) {
      let self = this;
      self.curPage = 1;
      self.loading = true;
      self.type = tab.name;
      self.getData();
    },
    /*获取列表*/
    getData() {
      let self = this;
      let Params = {};
      Params.type = self.type;
      Params.page = self.curPage;
      Params.list_rows = self.pageSize;
      OrderApi.orderlist(Params, true)
        .then(res => {
          self.loading = false;
          let list = [];
          for (let i = 0; i < res.data.list.data.length; i++) {
            let item = res.data.list.data[i];
            let topitem = {
              order_no: item.order_no,
              create_time: item.create_time,
              is_top_row: true
            };
            list.push(topitem);
            list.push(item);
          }

          self.tableData.data = list;
          self.totalDataNumber = res.data.list.total;
          self.order_count = res.data.order_count.order_count;
        })
        .catch(error => {});
    },
    /*打开添加*/
    addClick(row) {
      let self = this;
      let params = row.order_id;
      this.$router.push({
        path: '/order/order/Detail',
        query: {
          order_id: params
        }
      });
    },
    /*搜索查询*/
    onSubmit() {
      let self = this;
      let Params = this.searchForm;
      Params.name = self.name;
      Params.page = self.curPage;
      Params.list_rows = self.pageSize;
      self.loading = true;
      OrderApi.orderlist(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {});
    }
  }
};
</script>
<style >
.product-info {
  padding: 10px 0;
  border-top: 1px solid #eeeeee;
}
.table-wrap .product-info:first-of-type {
  border-top: none;
}
.table-wrap .el-table__body tbody .el-table__row:nth-child(odd){ background: #f5f7fa;}
</style>
