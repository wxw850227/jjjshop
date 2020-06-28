<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-24
    	描述：后台系统首页
    -->
  <div class="home">
    <!-- <div class="common-form mt16">
      商城统计
    </div> -->
    <div class="operation-wrap">
      <el-row>
        <el-col :span="6">
          <div class="grid-content">
            <div class="pic">
              <Icon :iconname="'#icon-tubiaozhizuomoban-'"></Icon>
            </div>
            <div class="info">
              <h3>{{tableData.product_total}}</h3>
              <p>商品总量</p>
            </div>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="grid-content">
            <div class="pic">
              <Icon :iconname="'#icon-yonghu'"></Icon>
            </div>
            <div class="info">
              <h3>{{tableData.user_total}}</h3>
              <p>用户总量</p>
            </div>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="grid-content">
            <div class="pic">
              <Icon :iconname="'#icon-dingdan'"></Icon>
            </div>
            <div class="info">
              <h3>{{tableData.order_total}}</h3>
              <p>订单总量</p>
            </div>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="grid-content">
            <div class="pic">
              <Icon :iconname="'#icon-pingjia-tianchong'"></Icon>
            </div>
            <div class="info">
              <h3>{{tableData.comment_total}}</h3>
              <p>评价总量</p>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>

    <!--待办事项-->
    <div class="matters-wrap">
      <div class="common-form mt16">
        待办事项<span class="ml10 f14 gray" style="font-weight: normal;">请尽快处理，以免影响营业</span>
      </div>
      <el-row class="border-b">
        <el-col :span="6">
          <div class="matters">
            <div class="box">
              <div class="title">订单</div>
              <ul>
                <li>待处理订单：<span class="fb">{{right_away.order.disposal}}</span></li>
                <li>待审核售后订单：<span class="fb">{{right_away.order.refund}}</span></li>
              </ul>
            </div>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="matters">
            <div class="box">
              <div class="title">库存</div>
              <ul>
                <li>库存告急：<span class="fb">{{right_away.stock.product}}</span></li>
              </ul>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>

    <div class="home-index">
      <!--main-index-->
      <div class="main-index">
        <div class="common-form mt16">
          今日概况
        </div>

        <el-row class="border-b">
          <el-col :span="6">
            <div class="grid-content">
              <div class="pic">
                <Icon :iconname="'#icon-xiaoshou'"></Icon>
              </div>
              <div class="info">
                <h3>{{order_total_price.tday}}</h3>
                <p class="des">销售额(元)</p>
                <p class="yesterday">昨日：{{order_total_price.ytd}}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="pic">
                <Icon :iconname="'#icon-icon1'"></Icon>
              </div>
              <div class="info">
                <h3>{{order_total.tday}}</h3>
                <p class="des">支付订单数</p>
                <p class="yesterday">昨日：{{order_total.ytd}}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="pic">
                <Icon :iconname="'#icon-xinzengyonghu'"></Icon>
              </div>
              <div class="info">
                <h3>{{new_user_total.tday}}</h3>
                <p class="des">新增用户数</p>
                <p class="yesterday">昨日：{{new_user_total.ytd}}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="pic">
                <Icon :iconname="'#icon-piliangxiadan'"></Icon>
              </div>
              <div class="info">
                <h3>{{order_user_total.tday}}</h3>
                <p class="des">下单用户数</p>
                <p class="yesterday">昨日：{{order_user_total.ytd}}</p>
              </div>
            </div>
          </el-col>
        </el-row>

      </div>

      <!--side-->
      <div class="side"></div>
    </div>
  </div>
</template>

<script>
  import IndexApi from '@/api/index.js';
  export default {
    data() {
      return {
        /*是否加载完成*/
        loading: true,
        /*统计信息*/
        tableData: [],
        /*总销售额(元)*/
        order_total_price: [],
        /*总支付订单数*/
        order_total: [],
        /*新增用户数*/
        new_user_total: [],
        /*下单用户数*/
        order_user_total: [],
        /*待办事项*/
        right_away: {
          order: [],
          stock: [],
        },
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
        IndexApi.getCount(true).then(data => {
          self.loading = false;
          self.tableData = data.data.data.widget_card;
          self.order_total_price = data.data.data.widget_outline.order_total_price;
          self.order_total = data.data.data.widget_outline.order_total;
          self.new_user_total = data.data.data.widget_outline.new_user_total;
          self.order_user_total = data.data.data.widget_outline.order_user_total;
          self.right_away = data.data.data.right_away;
        }).catch(error => {

        });
      },
    }
  };
</script>

<style lang="scss" scoped>
  .operation-wrap {
    width: 100%;
    height: 164px;
    border-radius: 8px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 0 30px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    overflow: hidden;
    background: #3a8ee6;
    background-size: 100% 100%;
    color: #fff;
  }

  .operation-wrap .grid-content {
    height: 164px;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .operation-wrap .grid-content h3 {
    font-size: 36px;
    line-height: 40px;
  }

  .operation-wrap .grid-content .info {
    margin-left: 10px;
  }

  .operation-wrap .grid-content .svg-icon {
    color: #FFFFFF;
  }

  .home-index {
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    min-width: 1000px;
    overflow-x: auto;
  }

  .main-index {
    flex: 1;
  }

  .main-index .grid-content,
  .operation-wrap .grid-content {

    display: -ms-flexbox;
    display: flex;
    -webkit-box-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  .main-index .grid-content {
    height: 120px;
  }

  .main-index .grid-content .pic {
    margin-right: 10px;
  }

  .main-index .grid-content h3 {
    font-size: 20px;
    font-weight: normal;
  }

  .main-index .grid-content .yesterday {
    color: #ccc;
  }

  .main-index .grid-content .svg-icon {
    color: #3a8ee6;
  }

  .matters-wrap .matters {
    display: -ms-flexbox;
    display: flex;
    -webkit-box-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 120px;
  }

  .matters-wrap .matters .title {
    font-size: 14px;
    color: #fff;
    display: inline-block;
    height: 20px;
    line-height: 20px;
    width: 40px;
    text-align: center;
    border-radius: 99px;
    margin-bottom: 20px;
    background: #3a8ee6;
  }

  .matters-wrap .matters ul {
    color: #999999;
  }

  .matters-wrap .matters ul span {
    padding-right: 6px;
    color: #3a8ee6;
  }
</style>
