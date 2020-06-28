<template>
  <!--
        作者：luoyiming
        时间：2019-10-24
        描述：数据概况
    -->
  <div class="statistics">
    <div class="common-form">数据概况</div>
    <div class="search_time tc">
      <div class="block">
        <el-form size="small" :inline="true" :model="form" class="demo-form-inline">
          <el-form-item>
            <el-date-picker
              v-model="form.value2"
              type="daterange"
              align="right"
              unlink-panels
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :picker-options="pickerOptions"
            ></el-date-picker>
          </el-form-item>
          <el-form-item><el-button size="small" icon="el-icon-search" type="primary" @click="onSubmit()">查询</el-button></el-form-item>
        </el-form>
      </div>
    </div>

    <el-row class="survey-wrap mt16 border-b" :gutter="30" v-loading="loading">
      <el-col :span="4">
        <div class="grid-content">
          <h2 class="c_main">{{ survey.user_total }}</h2>
          <p class="">用户数量</p>
        </div>
      </el-col>
      <el-col :span="4">
        <div class="grid-content">
          <h2 class="c_main">{{ survey.order_total }}</h2>
          <p>付款订单数</p>
        </div>
      </el-col>
      <el-col :span="4">
        <div class="grid-content">
          <h2 class="c_main">{{ survey.product_total }}</h2>
          <p>商品总量</p>
        </div>
      </el-col>
      <el-col :span="4">
        <div class="grid-content">
          <h2 class="c_main">{{ survey.consume_users }}</h2>
          <p>消费人数</p>
        </div>
      </el-col>
      <el-col :span="4">
        <div class="grid-content">
          <h2 class="c_main">{{ survey.order_total_money }}</h2>
          <p>付款订单总额</p>
        </div>
      </el-col>
    </el-row>

    <el-row class="mt16" :gutter="30">
      <el-col :span="12">
        <div class="common-form">用户消费榜</div>
        <el-table :data="userExpendRanking" style="width: 100%">
          <el-table-column type="index" label="排名">
            <template slot-scope="scope">
              <span class="ranking_num">{{ scope.$index + 1 }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="nickName" label="用户昵称"></el-table-column>
          <el-table-column prop="expend_money" label="消费金额">
            <template slot-scope="scope">
              <div class="fb orange">{{ scope.row.expend_money }}</div>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
      <el-col :span="12">
        <div class="common-form">商品销售榜</div>
        <el-table :data="productExpendRanking" style="width: 100%">
          <el-table-column type="index" label="排名" width="50">
            <template slot-scope="scope">
              <span class="ranking_num">{{ scope.$index + 1 }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="product_name" label="商品	">
            <template slot-scope="scope">
              <div class="text-ellipsis">{{ scope.row.product_name }}</div>
            </template>
          </el-table-column>
          <el-table-column prop="total_sales_num" label="销量" width="100"></el-table-column>
          <el-table-column prop="sales_volume" label="销售额" width="100">
            <template slot-scope="scope">
              <div class="fb orange">{{ scope.row.sales_volume }}</div>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import StatisticsApi from '@/api/statistics.js';
export default {
  data() {
    return {
      form: {
        value2: ''
      },
      loading: true,
      /*统计信息*/
      survey: [],
      /*消费排行榜*/
      userExpendRanking: [],
      /*商品排行榜*/
      productExpendRanking: [],
      pickerOptions: {
        shortcuts: [
          {
            text: '最近一周',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          }
        ]
      }
    };
  },
  created() {
    /*获取列表*/
    this.getData();
  },
  methods: {
    /*获取列表*/
    getData() {
      let self = this;
      StatisticsApi.getStatistics({}, true)
        .then(data => {
          self.loading = false;
          self.survey = data.data.survey;
          self.userExpendRanking = data.data.userExpendRanking;
          self.productExpendRanking = data.data.productRanking;
        })
        .catch(error => {});
    },
    /*查询*/
    onSubmit() {
      let self = this;
      let times = this.form.value2;
      if (times.length == 0) {
        this.$message({
          showClose: true,
          message: '请选择查询时间',
          type: 'warning'
        });
        return false;
      }
      self.loading = true;
      StatisticsApi.getSurvey(
        {
          times: times
        },
        true
      )
        .then(data => {
          self.loading = false;
          this.survey.user_total = data.data.user_total;
          this.survey.order_total = data.data.order_total;
          this.survey.product_total = data.data.product_total;
          this.survey.consume_users = data.data.consume_users;
          this.survey.order_total_money = data.data.order_total_money;
          this.survey.recharge_total = data.data.recharge_total;
        })
        .catch(error => {
          self.loading = false;
        });
    }
  }
};
</script>

<style lang="scss">
.survey-wrap .grid-content {
  padding: 20px 0;
  text-align: center;
}
.statistics .ranking_num {
  display: inline-block;
  width: 20px;
  height: 20px;
  line-height: 20px;
  text-align: center;
  border-radius: 50%;
  background: #ff1106;
  color: #ffffff;
}
</style>
