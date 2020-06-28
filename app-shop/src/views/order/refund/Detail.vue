<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：售后管理-详情
  -->
  <div class="user" v-loading="loading">
    <div class="product-content">
      <!--售后单信息-->
      <div class="common-form">售后单信息</div>
      <div class="refund-detail-content">
        <el-row>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">订单号：</span>
              {{ detail.order_master.order_no }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">买家：</span>
              {{ detail.user.nickName }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">售后类型：</span>
              {{ detail.type.text }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">处理状态：</span>
              {{ detail.status.text }}
            </div>
          </el-col>
          <el-col :span="4">
            <div class="pb16"><el-button size="mini" href="/" target="_blank" @click="addClick(detail)">订单详情</el-button></div>
          </el-col>
        </el-row>
      </div>
      <!--售后商品信息-->
      <div class="common-form">售后商品信息</div>
      <div class="refund-detail-content">
        <el-row>
          <el-col :span="5">
            <div class="pb16"><span class="gray9">商品编码：</span></div>
          </el-col>
          <el-col :span="19">
            <div class="pb16">
              <span class="gray9">商品名称：</span>
              {{ detail.orderproduct.product_name }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">重量(Kg)：</span>
              {{ detail.orderproduct.product_weight }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">单价：</span>
              {{ detail.orderproduct.line_price }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">购买数量：</span>
              {{ detail.orderproduct.total_num }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">付款价：</span>
              {{ detail.orderproduct.total_pay_price }}
            </div>
          </el-col>
        </el-row>
      </div>

      <!--用户申请原因-->
      <div>
        <div class="common-form">用户申请原因</div>
        <div class="apply-reason">{{ detail.apply_desc }}</div>
        <div v-if="detail.image.length > 0" class="pic" v-for="(item, index) in detail.image" :key="index"><img v-img-url="item.file_path" alt="" width="60" /></div>
      </div>

      <!--商家审核-->
      <div v-if="detail.is_agree.value == 0">
        <div class="common-form mt16">商家审核</div>
        <el-form size="small" ref="form" :model="form" label-width="80px">
          <el-form-item label="售后类型">
            <span class="orange">{{ detail.type.text }}</span>
          </el-form-item>
          <el-form-item label="审核状态 ">
            <el-radio-group v-model="form.is_agree">
              <el-radio :label="10">同意</el-radio>
              <el-radio :label="20">拒绝</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="退货地址 ">
            <el-select v-model="form.address_id" placeholder="请选择地址">
              <el-option v-for="(item, index) in address" :key="index" :label="item.detail" :value="item.address_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item><el-button type="primary" @click="onSubmit(detail.order_refund_id)">确认审核</el-button></el-form-item>
        </el-form>
      </div>

      <!-- 退货地址 -->
      <div v-if="detail.is_agree.value == 10">
        <div class="common-form mt16">退货地址</div>
        <el-row>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">收货人：</span>
              {{ detail.address.name }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">收货电话：</span>
              {{ detail.address.phone }}
            </div>
          </el-col>
          <el-col :span="14">
            <div class="pb16">
              <span class="gray9">收货地址：</span>
              {{ detail.address.detail }}
            </div>
          </el-col>
        </el-row>
      </div>

      <!-- 商家拒绝原因 -->
      <div v-if="detail.is_agree.value == 20">
        <div class="common-form">商家拒绝原因</div>
        <div class="apply-reason">{{ detail.refuse_desc }}</div>
      </div>

      <!-- 用户发货信息 -->
      <div v-if="detail.type.value == 10 && detail.is_agree.value == 10 && detail.is_user_send == 1">
        <div class="common-form mt16">用户发货信息</div>
        <el-row>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">物流公司：</span>
              {{ detail.express.express_name }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">物流单号：</span>
              {{ detail.express_no }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">用户发货状态：</span>
              已发货
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">发货时间：</span>
              {{ detail.send_time }}
            </div>
          </el-col>
          <el-col :span="4">
            <div class="pb16">
              <span class="gray9">商家收货状态：</span>
              <block v-if="detail.is_receipt == 1">已收货</block>
              <block v-else="">待收货</block>
            </div>
          </el-col>
        </el-row>
      </div>

      <!-- 确认收货并退款 -->
      <div v-if="detail.type.value == 10 && detail.is_agree.value == 10 && detail.is_user_send == 1 && detail.is_receipt == 0">
        <div class="common-form mt16">确认收货并退款</div>
        <el-form size="small" ref="money" :model="money" label-width="80px">
          <p>注：该操作将执行订单原路退款 并关闭当前售后单，请确认并填写退款的金额（不能大于订单实付款）</p>
          <el-form-item label="售后类型">
            <span class="orange">{{ detail.type.text }}</span>
          </el-form-item>
          <el-form-item label="退款金额">
            <el-input v-model="money.refund_money"></el-input>
            <p>请输入退款金额，最多{{ detail.orderproduct.total_pay_price }} 元</p>
          </el-form-item>
          <el-form-item><el-button type="primary" @click="refundMoney(detail.order_refund_id)">确认审核</el-button></el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import OrderApi from '@/api/order.js';
export default {
  data() {
    return {
      tableData: {},
      /*退货详情*/
      detail: {
        is_agree: {},
        type: {},
        status: {},
        order_master: {},
        user: {},
        orderproduct: {},
        express: {},
        address: {}
      },
      /*订单详情*/
      order: {},
      /*退货地址列表*/
      address: {},
      form: {
        is_agree: 10,
        address_id: '',
        refuse_desc: ''
      },
      money: {
        refund_money: 0
      },
      order_refund_id: 0,
      loading: true
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
      let order_refund_id = this.$route.query.order_refund_id;
      OrderApi.refundDetail(
        {
          order_refund_id: order_refund_id
        },
        true
      )
        .then(data => {
          self.loading = false;
          self.detail = data.data.detail;
          self.order = data.data.order;
          self.address = data.data.address;
        })
        .catch(error => {});
    },
    /*打开添加*/
    addClick(row) {
      let self = this;
      let params = row.order_id;
      this.$router.push({
        path: '/order/refund/Order',
        query: {
          order_id: params
        }
      });
    },
    /*审核*/
    onSubmit(e) {
      let self = this;
      let form = self.form;
      let order_refund_id = e;
      OrderApi.Approval(
        {
          is_agree: form.is_agree,
          address_id: form.address_id,
          refuse_desc: form.refuse_desc,
          order_refund_id: order_refund_id
        },
        true
      )
        .then(data => {
          self.$message({
            message: '恭喜你，操作成功',
            type: 'success'
          });
          this.getData();
        })
        .catch(error => {});
    },
    /*确认收货退款*/
    refundMoney(e) {
      let self = this;
      let form = self.money;
      OrderApi.receipt(
        {
          refund_money: form.refund_money,
          order_refund_id: e
        },
        true
      )
        .then(data => {
          self.$message({
            message: '恭喜你，操作成功',
            type: 'success'
          });
          this.getData();
        })
        .catch(error => {});
    }
  }
};
</script>
<style>
.apply-reason {
  padding: 16px;
  background: #fff4f4;
  border: 1px solid #f6e6e6;
}
</style>
