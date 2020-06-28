<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：应用
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="250px">
      <!--小程序设置-->
      <div class="common-form">小程序设置</div>

      <el-form-item label="AppID 小程序ID" :rules="[{ required: true, message: '请输入AppID 小程序ID' }]" prop="wxapp_id">
        <el-input v-model="form.wxapp_id" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="AppSecret 小程序密钥" :rules="[{ required: true, message: '请输入AppSecret 小程序密钥' }]" prop="wxapp_secret">
        <el-input v-model="form.wxapp_secret" type="password" class="max-w460"></el-input>
      </el-form-item>

      <div class="common-form">微信支付设置</div>

      <el-form-item label="微信支付商户号 MCHID">
        <el-input v-model="form.mchid" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="微信支付密钥 APIKEY ">
        <el-input v-model="form.apikey" class="max-w460"></el-input>
      </el-form-item>

      <el-form-item label="apiclient_cert.pem">
        <el-input type="textarea" :rows="4" placeholder="使用文本编辑器打开apiclient_cert.pem文件，将文件的全部内容复制进来" v-model="form.cert_pem" class="max-w460"></el-input>
        <div class="tips">使用文本编辑器打开apiclient_key.pem文件，将文件的全部内容复制进来</div>
      </el-form-item>
      <el-form-item label="apiclient_key.pem">
        <el-input type="textarea" :rows="4" placeholder="使用文本编辑器打开apiclient_cert.pem文件，将文件的全部内容复制进来" v-model="form.key_pem" class="max-w460"></el-input>
        <div class="tips">使用文本编辑器打开apiclient_key.pem文件，将文件的全部内容复制进来</div>
      </el-form-item>

      <!--提交-->
      <div class="common-button-wrapper"><el-button size="small" type="primary" @click="onSubmit">提交</el-button></div>
    </el-form>
  </div>
</template>

<script>
import AppSettingApi from '@/api/appsetting.js';

export default {
  data() {
    return {
      /*切换菜单*/
      // activeIndex: '1',
      /*form表单数据*/
      form: {}
    };
  },
  created() {
    this.getData();
  },

  methods: {
    getData() {
      let self = this;
      AppSettingApi.appwxDetail({}, true)
        .then(data => {
          if (data.data.data != null) {
            self.form = data.data.data;
          }
        })
        .catch(error => {});
    },
    //提交表单
    onSubmit() {
      let self = this;
      let params = this.form;
      AppSettingApi.editAppWx(params, true)
        .then(data => {
          self.$message({
            message: '恭喜你，设置成功',
            type: 'success'
          });
          self.$router.push('/appsettings/appwx');
        })
        .catch(error => {});
    }
  }
};
</script>

<style>
.tips {
  color: #ccc;
}
</style>
