<template>
  <!--
          作者：luoyiming
          时间：2019-10-25
          描述：订单详情
      -->
  <div class="user" v-loading="loading">
    <!--内容-->
    <div class="product-content">
      <!--基本信息-->
      <div class="common-form mt30">基本信息</div>
      <div class="table-wrap">
        <el-row>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">订单号：</span>
              {{ detail.order_no }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">买家：</span>
              {{ detail.user.nickName }}
              <span>用户ID：({{ detail.user.user_id }})</span>
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">订单金额 (元)：</span>
              {{ detail.order_price }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">运费金额 (元)：</span>
              {{ detail.express_price }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">实付款金额 (元)：</span>
              <span class="fb orange">{{ detail.pay_price }}</span>
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">支付方式：</span>
              {{ detail.pay_type.text }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">配送方式：</span>
              {{ detail.delivery_type.text }}
            </div>
          </el-col>
          <el-col :span="5">
            <div class="pb16">
              <span class="gray9">交易状态：</span>
              {{ detail.order_status.text }}
            </div>
          </el-col>
          <el-col :span="5" v-if="detail['pay_status']['value'] == 10 && detail['order_status']['value'] == 10 && detail['order_source'] == 10">
            <el-button v-if="" type="text" @click="editClick(detail)" size="small">修改价格</el-button>
          </el-col>
        </el-row>
      </div>
      <!--编辑-->
      <Add v-if="open_edit" :open_edit="open_edit" :order="userModel" @close="closeDialogFunc($event, 'edit')"></Add>

      <!--商品信息-->
      <div class="common-form mt16">商品信息</div>
      <div class="table-wrap">
        <el-table size="small" :data="detail.product" border style="width: 100%">
          <el-table-column prop="product_name" label="商品" width="400">
            <template slot-scope="scope">
              <div class="product-info">
                <div class="pic"><img v-img-url="scope.row.image.file_path" /></div>
                <div class="info">
                  <div class="name">{{ scope.row.product_name }}</div>
                  <div class="gray9" v-if="scope.row.product_attr!=''">{{scope.row.product_attr}}</div>
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
              <p class="orange fb">￥ {{ scope.row.total_price }}</p>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <!--收货信息-->
      <div v-if="detail.delivery_type.value == 10">
        <div class="common-form mt16">收货信息</div>
        <div class="table-wrap">
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
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">收货地址：</span>
                {{ detail.address.detail }}
              </div>
            </el-col>
          </el-row>
        </div>
      </div>

      <!--自提门店信息-->
      <template v-if="detail.delivery_type.value == 20">
        <div class="common-form mt16">自提用户信息</div>
        <div class="table-wrap" v-if="detail.extract">
          <el-row>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">联系人：</span>
                {{ detail.extract.linkman }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">联系电话：</span>
                {{ detail.extract.phone }}
              </div>
            </el-col>
          </el-row>
        </div>
        <div class="common-form mt16">自提信息</div>
        <div class="table-wrap" v-if="detail.extract">
          <el-row>
            <el-col :span="5">
              <div class="pb16"><span class="gray9">门店LOGO：</span></div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">门店ID：</span>
                {{ detail.extract_store.shop_id }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">门店名称：</span>
                {{ detail.extract_store.shop_name }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">联系人：</span>
                {{ detail.extract_store.linkman }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">联系电话：</span>
                {{ detail.extract_store.phone }}
              </div>
            </el-col>
            <el-col :span="15">
              <div class="pb16">
                <span class="gray9">门店地址：</span>
                {{ detail.extract_store.region.province }}- {{ detail.extract_store.region.city }}- {{ detail.extract_store.region.region }}- {{ detail.extract_store.address }}
              </div>
            </el-col>
          </el-row>
        </div>
      </template>

      <!--付款信息-->
      <div class="table-wrap" v-if="detail.pay_status.value == 20">
        <div class="common-form mt16">付款信息</div>
        <div class="table-wrap">
          <el-row>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">应付款金额：</span>
                {{ detail.pay_price }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">支付方式：</span>
                {{ detail.pay_type.text }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">支付流水号：</span>
                {{ detail.transaction_id }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">付款状态：</span>
                {{ detail.pay_status.text }}
              </div>
            </el-col>
            <el-col :span="5">
              <div class="pb16">
                <span class="gray9">付款时间：</span>
                {{ detail.pay_time }}
              </div>
            </el-col>
          </el-row>
        </div>
      </div>

      <!--  用户取消订单 -->
      <div v-if="detail.pay_status.value == 20 && detail.order_status.value == 21">
        <div class="common-form mt16">用户取消订单</div>
        <p>当前买家已付款并申请取消订单，请审核是否同意，如同意则自动退回付款金额（微信支付原路退款）并关闭订单。</p>
        <el-form size="small" ref="forms" :model="forms" label-width="100px">
          <el-form-item label="审核状态">
            <el-radio v-model="forms.is_cancel" :label="1">同意</el-radio>
            <el-radio v-model="forms.is_cancel" :label="2">拒绝</el-radio>
          </el-form-item>

          <el-form-item><el-button type="primary" @click="confirmCancel()">确认审核</el-button></el-form-item>
        </el-form>
      </div>

      <!--发货信息-->

      <div v-if="detail.pay_status.value == 20 && detail.delivery_type.value == 10 && [20, 21].indexOf(detail.order_status.value) === -1">
        <div v-if="detail.delivery_status.value == 10">
          <!-- 去发货 -->
          <div class="common-form mt16">去发货</div>
          <el-form size="small" ref="form" :model="form" label-width="100px">
            <el-form-item label="物流公司">
              <el-select v-model="form.express_id" placeholder="请选择快递公司">
                <el-option :label="item.express_name" v-for="(item, index) in expressList" :key="index" :value="item.express_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="物流单号"><el-input v-model="form.express_no" class="max-w460"></el-input></el-form-item>
            <el-form-item><el-button type="primary" @click="onSubmit()">确认发货</el-button></el-form-item>
          </el-form>
        </div>
        <div v-else="">
          <div class="common-form mt16">发货信息</div>
          <div class="table-wrap">
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
                  <span class="gray9">发货状态：</span>
                  {{ detail.delivery_status.text }}
                </div>
              </el-col>
              <el-col :span="5">
                <div class="pb16">
                  <span class="gray9">发货时间：</span>
                  {{ detail.delivery_time }}
                </div>
              </el-col>
            </el-row>
          </div>
        </div>
      </div>
      <!--门店自提核销-->
      <div v-if="detail.delivery_type.value == 20 && detail.pay_status.value == 20 && (detail.order_status.value != 21 || detail.order_status.value != 20)">
        <div class="common-form mt16">门店自提核销</div>
        <div v-if="detail.delivery_status.value == 10">
          <el-form size="small" ref="form1" :model="form1" label-width="100px">
            <el-form-item label="门店核销员">
              <el-select v-model="form1.order.extract_clerk_id" placeholder="点击选择">
                <el-option :label="item.real_name" v-for="(item, index) in shopClerkList" :key="item.clerk_id" :value="item.clerk_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="买家取货状态 "><el-radio v-model="form1.order.extract_status" :label="1">已取货</el-radio></el-form-item>
            <el-form-item><el-button type="primary" @click="onSubmit1(detail.order_id)">确认核销</el-button></el-form-item>
          </el-form>
        </div>
        <div v-else class="table-wrap">
          <template v-if="detail.extractClerk">
            <el-row>
              <el-col :span="5">
                <div class="pb16">
                  <span class="gray9">自提门店名称：</span>
                  {{ detail.extract_store.shop_name }}
                </div>
              </el-col>
              <el-col :span="5">
                <div class="pb16">
                  <span class="gray9">核销员：</span>
                  {{ detail.extractClerk.real_name }}
                </div>
              </el-col>
              <el-col :span="5">
                <div class="pb16">
                  <span class="gray9">核销状态：</span>
                  <template v-if="detail.delivery_status.value == 20">
                    已核销
                  </template>
                </div>
              </el-col>
              <el-col :span="5">
                <div class="pb16">
                  <span class="gray9">核销时间：</span>
                  {{ detail.delivery_time }}
                </div>
              </el-col>
            </el-row>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import OrderApi from '@/api/order.js';
import Add from './dialog/Add.vue';
import { deepClone } from '@/utils/base.js';
export default {
  components: {
    Add
  },
  data() {
    return {
      active: 0,
      /*是否加载完成*/
      loading: true,
      /*订单数据*/
      detail: {
        pay_status: [],
        pay_type: [],
        delivery_type: [],
        user: {},
        address: [],
        product: [],
        order_status: [],
        extract: [],
        extract_store: [],
        express: [],
        delivery_status: [],
        extractClerk: []
      },

      /*是否打开添加弹窗*/
      open_add: false,
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
      form1: {
        order: {
          extract_status: 1
        }
      },
      order: {},
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
      expressList: [],
      shopClerkList: [],
      /*是否打开编辑弹窗*/
      open_edit: false
    };
  },
  created() {
    /*获取列表*/
    this.getParams();
  },
  methods: {
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
          self.detail = data.data.detail;
          self.expressList = data.data.expressList;
          self.shopClerkList = data.data.shopClerkList;
        })
        .catch(error => {
          self.loading = false;
        });
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
          self.loading = false;
          self.$message({
            message: '恭喜你，发货成功',
            type: 'success'
          });
          self.getParams();
        })
        .catch(error => {
          self.loading = false;
        });
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
          self.loading = false;
          self.$message({
            message: '恭喜你，审核成功',
            type: 'success'
          });
          self.getParams();
        })
        .catch(error => {
          self.loading = false;
        });
    },
    /*核销*/
    onSubmit1(e) {
      let self = this;
      let form1 = self.form1;
      form1.order_id = e;
      OrderApi.extract(
        {
          form1
        },
        true
      )
        .then(data => {
          self.loading = false;
          self.$message({
            message: '恭喜你，操作成功',
            type: 'success'
          });
          self.getParams();
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*打开编辑*/
    editClick(item) {
      //                this.userModel = item;
      this.userModel = deepClone(item);
      this.open_edit = true;
    },
    /*关闭弹窗*/
    closeDialogFunc(e, f) {
      if (f == 'edit') {
        this.open_edit = e.openDialog;
        this.getParams();
      }
    }
  }
};
</script>
<style></style>
