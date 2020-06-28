<template>
  <!--
      作者 WuYuseng
      时间：2019-10-26
      描述：设置-消息设置-短信设置
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="150px">
      <!--短信通知-->
      <div class="common-form">短信通知（阿里云短信）</div>
      <el-form-item label="AccessKey" :rules="[{ required: true, message: '请输入AccessKey' }]" prop="AccessKeyId">
        <el-input v-model="form.AccessKeyId" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="AccessKeySecret" :rules="[{ required: true, message: '请输入AccessKeySecret' }]" prop="AccessKeySecret">
        <el-input v-model="form.AccessKeySecret" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="短信签名" :rules="[{ required: true, message: '请输入短信签名' }]" prop="sign"><el-input v-model="form.sign" class="max-w460"></el-input></el-form-item>
      <div class="common-form">商家手机号设置（用于接收消息提醒）</div>
      <el-form-item label="接收手机号" :rules="[{ required: true, message: '请输入接收手机号' }]" prop="accept_phone">
        <el-input v-model="form.accept_phone" class="max-w460"></el-input>
      </el-form-item>

      <!--提交-->
      <div class="common-button-wrapper"><el-button type="primary" size="small" @click="onSubmit" :loading="loading">提交</el-button></div>
    </el-form>
    、
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
        AccessKeyId: '',
        AccessKeySecret: '',
        sign: '',
        accept_phone: ''
      },
      loading: false
    };
  },
  created() {
    this.getData();
  },

  methods: {
    getData() {
      let self = this;
      SettingApi.smsDetail({}, true)
        .then(data => {
          let vars = data.data.vars.values;
          self.form.AccessKeyId = vars.engine.aliyun.AccessKeyId;
          self.form.AccessKeySecret = vars.engine.aliyun.AccessKeySecret;
          self.form.sign = vars.engine.aliyun.sign;
          self.form.accept_phone = vars.engine.aliyun.accept_phone;
        })
        .catch(error => {});
    },
    //提交表单
    onSubmit() {
      let self = this;
      self.loading = true;
      let params = this.form;
      self.$refs.form.validate(valid => {
        if (valid) {
          SettingApi.editSms(params, true)
            .then(data => {
              self.loading = false;
              self.$message({
                message: '恭喜你，短信通知设置成功',
                type: 'success'
              });
            })
            .catch(error => {
              self.loading = false;
            });
        }
      });
    },
    
    //发送短信
    sendOut() {
      let self = this;
      let params = this.form;
      SettingApi.sendSms(params, true)
        .then(data => {
          self.$message({
            message: '恭喜你，短信发送成功',
            type: 'success'
          });
          self.$router.push('/setting/Sms');
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
