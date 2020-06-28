<template>
  <!--
    作者：luoyiming
    时间：2019-10-25
    描述：设置-交易设置
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" :rules="rules" label-width="150px">
      <!--交易设置-->
      <div class="common-form">订单流程设置</div>
      <el-form-item label="未支付订单"  prop="close_days">
        <el-input v-model="form.close_days" type="number" style="width: 200px;"></el-input>
        <span class="ml10">天后自动关闭</span>
        <p class="tips">订单下单未付款，n天后自动关闭，设置0天不自动关闭</p>
      </el-form-item>
      <el-form-item label="已发货订单" prop="receive_days">
        <el-input v-model="form.receive_days" type="number" style="width: 200px;"></el-input>
        <span class="ml10">天后自动确认收货</span>
        <p class="tips">如果在期间未确认收货，系统自动完成收货，设置0天不自动收货</p>
      </el-form-item>
      <el-form-item label="已完成订单" prop="refund_days">
        <el-input v-model="form.refund_days" type="number" style="width: 200px;"></el-input>
        <span class="ml10">天内允许申请售后</span>
        <p class="tips">订单完成后 ，用户在n天内可以发起售后申请，设置0天不允许申请售后</p>
      </el-form-item>

      <div class="common-form">运费设置</div>
      <el-form-item label="运费组合策略" :rules="[{ required: true, message: ' ' }]" prop="freight_rule">
        <div>
          <el-radio v-model="form.freight_rule" :label="10">叠加</el-radio>
          <p class="tips">订单中的商品有多个运费模板时，将每个商品的运费之和订为订单总运费</p>
        </div>
        <div class="mt10">
          <el-radio v-model="form.freight_rule" :label="20">已低运费结算</el-radio>
          <p class="tips">订单中的商品有多个运费模板时，取订单中运费最少的商品的运费计为订单总运费</p>
        </div>
        <div class="mt10">
          <el-radio v-model="form.freight_rule" :label="30">已高运费结算</el-radio>
          <p class="tips">订单中的商品有多个运费模板时，取订单中运费最多的商品的运费计为订单总运费</p>
        </div>
      </el-form-item>

      <!--提交-->
      <div class="common-button-wrapper"><el-button size="small" type="primary" @click="onSubmit" :loading="loading">提交</el-button></div>
    </el-form>
  </div>
</template>

<script>
import SettingApi from '@/api/setting.js';

export default {
  data() {
    return {
      /*切换菜单*/
      // activeIndex: '1',
      /*form表单数据*/
      form: {
        close_days: '',
        receive_days: '',
        refund_days: '',
        freight_rule: ''
      },
      loading: false,
      /*验证规则*/
      rules: {
        close_days: [{ required: true, message: '请输入未支付订单', trigger: 'blur' }],
        receive_days: [{ required: true, message: '请输入已发货订单', trigger: 'blur' }],
        refund_days: [{ required: true, message: '请输入已完成订单', trigger: 'blur' }],
      }
    };
  },
  created() {
    this.getParams();
  },

  methods: {
    getParams() {
      let self = this;
      SettingApi.tradeDetail({}, true)
        .then(data => {
          if (data.code == 1) {
            let vars = data.data.vars.values;
            self.form.close_days = vars.order.close_days;
            self.form.receive_days = vars.order.receive_days;
            self.form.refund_days = vars.order.refund_days;
            self.form.freight_rule = vars.freight_rule;
          }
        })
        .catch(error => {});
    },

    //监听复选框选中
    handleCheckedCitiesChange(val) {},

    /*设置*/
    onSubmit() {
      let self = this;
      let form = self.form;
      self.$refs.form.validate(valid => {
        if (valid) {
          self.loading = true;
          SettingApi.editTrade(form, true)
            .then(data => {
              self.loading = false;
              if (data.code == 1) {
                self.$message({
                  message: '恭喜你，交易设置成功',
                  type: 'success'
                });
                self.$router.push('/setting/Trade');
              } else {
                self.loading = false;
              }
            })
            .catch(error => {
              self.loading = false;
            });
        }
      });
    }
  }
};
</script>

<style>
.tips {
  color: #ccc;
}
</style>
