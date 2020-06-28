<template>
  <!--
          作者：luoyiming
          时间：2019-10-25
          描述：订单详情
      -->
  <div class="user">
    <el-steps :active="active" finish-status="success">
      <el-step title="下单时间"></el-step>
      <el-step title="付款"></el-step>
      <el-step title="发货"></el-step>
      <el-step title="收货"></el-step>
      <el-step title="完成"></el-step>
    </el-steps>
    <!--内容-->
    <div class="product-content">
      <!--基本信息-->
      <div class="common-form mt50">基本信息</div>
      <div class="table-wrap">
        <el-table :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="order_no" label="订单号"></el-table-column>
          <el-table-column prop="user.nickName" label="买家">
            <template slot-scope="scope">
              <div>{{ scope.row.user.nickName }}</div>
              <div class="gray9">用户ID：({{ scope.row.user.user_id }})</div>
            </template>
          </el-table-column>
          <el-table-column prop="order_price" label="订单金额 (元)">
            <template slot-scope="scope">
              <p>订单金额：￥{{ scope.row.order_price }}</p>
              <p>运费金额：+￥{{ scope.row.express_price }}</p>
              <p>实付款金额：￥{{ scope.row.pay_price }}</p>
            </template>
          </el-table-column>
          <el-table-column prop="pay_type.text" label="支付方式"></el-table-column>
          <el-table-column prop="delivery_type.text" label="配送方式"></el-table-column>
          <el-table-column prop="delivery_status.text" label="发货状态">
            <template slot-scope="scope">
              <p>付款状态：{{ scope.row.pay_status.text }}</p>
              <p>发货状态：{{ scope.row.delivery_status.text }}</p>
              <p>收货状态：{{ scope.row.receipt_status.text }}</p>
              <p>订单状态：{{ scope.row.order_status.text }}</p>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <!--商品信息-->
      <div class="common-form mt50">商品信息</div>
      <div class="table-wrap">
        <el-table :data="productList" border style="width: 100%" v-loading="loading">
          <el-table-column prop="product_name" label="商品" width="400">
            <template slot-scope="scope">
              <div class="product-info">
                <div class="pic"><img v-img-url="scope.row.image.file_path" /></div>
                <div class="info">
                  <div class="name">{{ scope.row.product_name }}</div>
                  <div class="price">￥ {{ scope.row.product_price }}</div>
                </div>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="product_no" label="商品编码"></el-table-column>
          <el-table-column prop="product_weight" label="重量 (Kg)"></el-table-column>
          <el-table-column prop="total_num" label="购买数量">
            <template slot-scope="scope">
              <p>x {{ scope.row.total_num }}</p>
            </template>
          </el-table-column>
          <el-table-column prop="total_price" label="商品总价(元)">
            <template slot-scope="scope">
              <p>￥ {{ scope.row.total_price }}</p>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <!--收货信息-->
      <div v-if="delivery_type == 10">
        <div class="common-form mt50">收货信息</div>
        <div class="table-wrap">
          <el-table :data="address" border style="width: 100%" v-loading="loading">
            <el-table-column prop="name" label="收货人" width="200"></el-table-column>
            <el-table-column prop="phone" label="收货电话" width="300"></el-table-column>
            <el-table-column prop="detail" label="收货地址"></el-table-column>
          </el-table>
        </div>
      </div>

      <!--自提门店信息-->
      <div v-if="delivery_type == 20">
        <div class="common-form mt50">自提门店信息</div>
        <div class="table-wrap">
          <el-table :data="extract_store" border style="width: 100%" v-loading="loading">
            <el-table-column prop="shop_id" label="门店ID" width="200"></el-table-column>
            <el-table-column prop="phone" label="门店logo" width="300">
              <template slot-scope="scope">
                <!--<img :src="scope.row.logo.file_path" width="50" height="50"/>-->
              </template>
            </el-table-column>
            <el-table-column prop="shop_name" label="门店名称" width="300"></el-table-column>
            <el-table-column prop="linkman" label="联系人" width="300"></el-table-column>
            <el-table-column prop="phone" label="联系电话" width="300"></el-table-column>
            <el-table-column prop="address" label="门店地址"></el-table-column>
          </el-table>
        </div>
      </div>

      <!--付款信息-->
      <div class="common-form mt50">付款信息</div>
      <div class="table-wrap">
        <el-table :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="pay_price" label="应付款金额" width="300">
            <template slot-scope="scope">
              <p>￥ {{ scope.row.pay_price }}</p>
            </template>
          </el-table-column>
          <el-table-column prop="pay_type.text" label="支付方式" width="300"></el-table-column>
          <el-table-column prop="transaction_id" label="支付流水号" width="300">
            <template slot-scope="scope">
              <p v-if="scope.row.transaction_id == ''">--</p>
              <p v-else="">{{ scope.row.transaction_id }}</p>
            </template>
          </el-table-column>
          <el-table-column prop="pay_status.text" label="付款状态"></el-table-column>
          <el-table-column prop="pay_time" label="付款时间">
            <template slot-scope="scope">
              <p v-if="scope.row.pay_time != 0">{{ scope.row.pay_time }}</p>
              <p v-else="">--</p>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <!--发货信息-->
      <div v-if="delivery_type == 10">
        <!--去发货-->
        <div v-if="tableData[0].delivery_status.value == 10 && tableData[0].pay_status.value == 20">
          <div class="common-form mt50">去发货</div>
          <el-form size="small" ref="form" :model="form" label-width="100px">
            <el-form-item label="物流单号"><el-input v-model="form.express_no" class="max-w460"></el-input></el-form-item>
            <el-form-item label="物流公司">
              <el-select v-model="form.express_id" placeholder="请选择快递公司">
                <el-option :label="item.express_name" v-for="(item, index) in expressList" :key="index" :value="item.express_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item><el-button type="primary" @click="onSubmit()">确认发货</el-button></el-form-item>
          </el-form>
        </div>
        <div v-else>
          <div class="common-form mt50">发货信息</div>
          <div class="table-wrap">
            <el-table :data="tableData" border style="width: 100%" v-loading="loading">
              <el-table-column prop="express.express_name" label="物流公司" width="300"></el-table-column>
              <el-table-column prop="express_no" label="物流单号" width="300"></el-table-column>
              <el-table-column prop="delivery_status.text" label="发货状态" width="300"></el-table-column>
              <el-table-column prop="delivery_time" label="发货时间"></el-table-column>
            </el-table>
          </div>
        </div>
      </div>
    </div>
    <!--门店自提核销-->
    <div v-if="delivery_type == 20">
      <div class="common-form mt50">门店自提核销</div>
      <div class="table-wrap">
        <el-table :data="extract_store" border style="width: 100%" v-loading="loading">
          <el-table-column prop="shop_name" label="自提门店名称" width="300"></el-table-column>
          <el-table-column prop="pay_type.text" label="核销员" width="300"></el-table-column>
          <el-table-column prop="delivery_status.text" label="核销状态" width="300"></el-table-column>
          <el-table-column prop="delivery_time" label="核销时间"></el-table-column>
        </el-table>
      </div>
    </div>
    <div v-if="tableData[0].pay_status.value == 20 && tableData[0].order_status.value == 21">
      <div class="common-form mt50">用户取消订单</div>
      <p>当前买家已付款并申请取消订单，请审核是否同意，如同意则自动退回付款金额（微信支付原路退款）并关闭订单。</p>
      <el-form size="small" ref="forms" :model="forms" label-width="100px">
        <el-form-item label="审核状态">
          <el-radio v-model="forms.is_cancel" :label="1">同意</el-radio>
          <el-radio v-model="forms.is_cancel" :label="2">拒绝</el-radio>
        </el-form-item>

        <el-form-item><el-button type="primary" @click="confirmCancel()">确认审核</el-button></el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import OrderApi from '@/api/order.js';
export default {
  data() {
    return {
      active: 0,
      /*是否加载完成*/
      loading: true,
      /*列表数据*/
      tableData: [],
      productList: [],
      address: [],
      extract_store: [],
      express: [],
      /*一页多少条*/
      pageSize: 20,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*发货*/
      form: {},
      forms: {
        is_cancel: 1
      },
      delivery_type: 0,
      /*配送方式*/
      exStyle: [],
      /*门店列表*/
      shopList: [],
      /*当前编辑的对象*/
      userModel: {},
      /*时间*/
      create_time: '',
      /*快递公司列表*/
      expressList: []
    };
  },
  created() {
    /*获取列表*/
    this.getParams();
  },
  methods: {
    /*选择第几页*/
    handleCurrentChange(val) {
      this.curPage = val;
    },
    next() {
      if (this.active++ > 4) this.active = 0;
    },
    /*获取参数*/
    getParams() {
      let self = this;
      // 取到路由带过来的参数
      const params = this.$route.query.order_id;
      OrderApi.orderdetail(
        {
          order_id: params
        },
        true
      )
        .then(data => {
          self.loading = false;
          self.delivery_type = data.data.detail.delivery_type.value;
          self.tableData = [data.data.detail];
          self.address = [data.data.detail.address];
          self.productList = data.data.detail.product;
          self.extract_store = [data.data.detail.extract_store];
          self.express = [data.data.detail.express];
          self.expressList = data.data.expressList;
        })
        .catch(error => {});
    },
    /*发货*/
    onSubmit() {
      let self = this;
      let param = self.form;
      let order_id = this.$route.query.order_id;
      OrderApi.delivery(
        {
          param: param,
          order_id: order_id
        },
        true
      )
        .then(data => {
          this.$message({
            message: '恭喜你，发货成功',
            type: 'success'
          });
          this.getParams();
        })
        .catch(error => {});
    },
    /*确认审核*/
    confirmCancel() {
      let self = this;
      let order_id = this.$route.query.order_id;
      let is_cancel = self.forms.is_cancel;
      OrderApi.confirm(
        {
          order_id: order_id,
          is_cancel: is_cancel
        },
        true
      )
        .then(data => {
          console.log(data);
          this.$message({
            message: '恭喜你，审核成功',
            type: 'success'
          });
        })
        .catch(error => {});
      this.getParams();
    }
  }
};
</script>
<style></style>
