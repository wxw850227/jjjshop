<template>
 <!--
      作者 WuYuseng
      时间：2019-10-26
      描述：设置-消息设置-短信通知（阿里云短信）
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="200px">
      <!--短信通知（阿里云短信）-->
      <div class="common-form">短信通知（阿里云短信）</div>

      <el-form-item label="AccessKe">
        <el-input v-model="form.AccessKeyId" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="AccessKeySecret">
        <el-input v-model="form.AccessKeySecret" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="短信签名">
        <el-input v-model="form.sign" class="max-w460"></el-input>
      </el-form-item>

      <div class="common-form">新付款订单提醒</div>
      <el-form-item label="是否开启短信提醒">
        <el-radio-group v-model="form.is_enable">
          <el-radio :label="1">开启</el-radio>
          <el-radio :label="0">关闭</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="模板ID Template Code">
        <el-input v-model="form.template_code" class="max-w460"></el-input>
        <div class="tips">例如：SMS_139800030</div>
        <div class="tips">模板内容：您有一条新订单，订单号为：${order_no}，请注意查看。</div>
      </el-form-item>
      <el-form-item label="接收手机号">
        <el-input v-model="form.accept_phone" class="max-w460"></el-input>
        <div class="tips">注：如需填写多个手机号，可用英文逗号 , 隔开</div>
        <div class="tips">接收测试：<el-link type="primary" :underline="false" @click="sendOut">点击发送</el-link>
        </div>
      </el-form-item>

      <!--提交-->
      <div class="common-button-wrapper">
          <el-button type="primary" @click="onSubmit">提交</el-button>
      </div>
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
          AccessKeyId: '',
          AccessKeySecret: '',
          sign: '',
          is_enable: '',
          template_code: '',
          accept_phone: '',
          msg_type: 'order_pay'
        },


      };
    },
    created() {
      this.getData()
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
        let params = this.form;
        SettingApi.editSms(params, true)
          .then(data => {
            self.$message({
              message: '恭喜你，短信通知设置成功',
              type: 'success'
            });
            self.$router.push('/setting/Sms');

          })
          .catch(error => {

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
          .catch(error => {

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
