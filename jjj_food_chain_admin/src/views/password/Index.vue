<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-25
    	描述：密码-修改密码
    -->
  <div class="user">
    <!--内容-->
    <div class="product-content">
      <!--基本信息-->
      <div class="common-form">修改密码</div>
      <div class="table-wrap">
        <el-form :model="form" :rules="rules" ref="form" label-width="160px" class="demo-ruleForm">
          <el-form-item label="密码" prop="pass">
            <el-input type="password" v-model="form.pass" autocomplete="off" class="max-w460"></el-input>
          </el-form-item>
          <el-form-item label="确认密码" prop="checkPass">
            <el-input type="password" v-model="form.checkPass" autocomplete="off" class="max-w460"></el-input>
          </el-form-item>

          <el-form-item><el-button  type="primary" @click="submitForm('form')" :loading="loading">提交</el-button></el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import UserApi from '@/api/user.js';
export default {
  data() {

     let validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (this.form.checkPass !== '') {
            this.$refs.form.validateField('checkPass');
          }
          callback();
        }
      };
      let validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.form.pass) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
      };

    return {
      form: {
        pass: '',
        checkPass: '',
        name: ''
      },
      rules:{
        pass: [{ validator: validatePass, required:true, trigger: 'blur' }],
        checkPass: [{ validator: validatePass2, required:true, trigger: 'blur' }]
      },
      loading: false
    };
  },
  methods: {

    /*提交*/
    submitForm() {
      let self = this;
      let form = self.form;
      self.$refs.form.validate(valid => {
        if (valid) {
          self.loading = true;
          UserApi.editPassword(form, true)
            .then(data => {
              self.loading = false;
              self.form.pass='';
              self.form.checkPass='';
              ElMessage({
                type: 'success',
                message: data.msg
              });
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

<style></style>
