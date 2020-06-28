<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：设置-商城设置
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" :rules="rules" label-width="150px">
      <!--商城设置-->
      <div class="common-form">商城设置</div>
      <el-form-item label="商城名称" prop="name"><el-input v-model="form.name" placeholder="商城名称" class="max-w460"></el-input></el-form-item>
      <div class="common-form">物流查询api</div>
      <el-form-item label="快递100 Customer" prop="customer">
        <el-input v-model="form.customer" placeholder="" class="max-w460"></el-input>
        <div class="tips">
          用于查询物流信息,
          <el-link :underline="false" href="https://www.kuaidi100.com/openapi/" target="_blank" type="primary">快递100申请</el-link>
        </div>
      </el-form-item>
      <el-form-item label="快递100 Key" prop="key"><el-input v-model="form.key" placeholder="" class="max-w460"></el-input></el-form-item>

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
      loading: false,
      /*form表单数据*/
      form: {
        name: '',
        customer: '',
        key: '',
        checkedCities: [
          // 10, 20
        ]
      },
      all_type: [],
      type: [],
      /*验证规则*/
      rules: {
        name: [{ required: true, message: '请输入商城名称', trigger: 'blur' }],
        customer: [{ required: true, message: '请输入物流查询api', trigger: 'blur' }],
        key: [{ required: true, message: '请输入快递100Key', trigger: 'blur' }],
      }
    };
  },
  created() {
    this.getParams();
  },

  methods: {
    /*获取参数*/
    getParams() {
      let self = this;
      SettingApi.storeDetail({}, true)
        .then(data => {
          if (data.code == 1) {
            self.form.checkedCities = data.data.vars.values.delivery_type;
            let vars = data.data.vars.values;
            self.form.name = vars.name;
            self.type = vars.delivery_type;
            self.form.customer = vars.kuaidi100.customer;
            self.form.key = vars.kuaidi100.key;
            self.all_type = data.data.all_type;
          } else {
            self.$message.error('错了哦，这是一条错误消息');
          }
        })
        .catch(error => {});
    },

    /*提交*/
    onSubmit() {
      let self = this;
      let params = this.form;
      let form = self.form;
      self.$refs.form.validate(valid => {
        if (valid) {
          self.loading = true;
          SettingApi.editStore(params, true)
            .then(data => {
              self.loading = false;
              if (data.code == 1) {
                self.$message({
                  message: '恭喜你，商城设置成功',
                  type: 'success'
                });
                self.$router.push('/setting/store');
              } else {
                self.$message.error('错了哦，这是一条错误消息');
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
