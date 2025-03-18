<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：应用-基础设置
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="200px">
      <div class="common-form">通行证设置</div>
      <el-form-item label="通行证类型" class="flex items-center text-sm">
        <el-radio v-model="form.passport_type" :label="10" size="large">微信开放平台</el-radio>
        <el-col class="gray">
          目前仅支持微信开放平台，未来会支持手机号、用户名。如未注册或未绑定微信开放平台，请前往
          <a href="https://open.weixin.qq.com" target="_blank" class="blue">微信开放平台</a>
        </el-col>
      </el-form-item>
      <!--提交-->
      <div class="common-button-wrapper"><el-button type="primary" @click="onSubmit">提交</el-button></div>
    </el-form>
  </div>
</template>

<script>
import AppSettingApi from '@/api/appsetting.js';

export default {
  data() {
    return {
      form: {
        passport_open: 0,
        passport_type: 10
      }
    };
  },
  created() {
    this.getData();
  },

  methods: {
    /*获取数据*/
    getData() {
      let self = this;
      AppSettingApi.appDetail({}, true)
        .then(data => {
          self.form.passport_open = data.data.data.passport_open;
          self.form.passport_type = data.data.data.passport_type;
        })
        .catch(error => {});
    },

    //提交表单
    onSubmit() {
      let self = this;
      let params = this.form;
      AppSettingApi.editApp(params, true)
        .then(data => {
          ElMessage({
            message: '恭喜你，设置成功',
            type: 'success'
          });
          self.$router.push('/appsetting/app/index');
        })
        .catch(error => {});
    },
    //监听复选框选中
    handleCheckedCitiesChange(val) {}
  }
};
</script>

<style>
</style>
