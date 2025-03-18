<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-24
    	描述：登录页面
    -->
  <el-form :model="ruleForm2" :rules="rules2" ref="ruleForm2" label-position="left" label-width="0px" class="demo-ruleForm login-container">
    <h3 class="title">Saas运营管理系统</h3>
    <el-form-item prop="account">
      <el-input type="text" v-model="ruleForm2.account" auto-complete="off" placeholder="账号"></el-input>
    </el-form-item>
    <el-form-item prop="checkPass">
      <el-input type="password" v-model="ruleForm2.checkPass" auto-complete="off" placeholder="密码"></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" style="width:100%;" @click.native.prevent="handleSubmit2" :loading="logining">登录</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
  import UserApi from '@/api/user.js';
  import { setCookie,setSessionStorage } from '@/utils/base.js';
  export default {
    data() {
      return {
        logining: false,
        ruleForm2: {
          account: '',
          checkPass: ''
        },
        rules2: {
          account: [{
              required: true,
              message: '请输入账号',
              trigger: 'blur'
            }
          ],
          checkPass: [{
              required: true,
              message: '请输入密码',
              trigger: 'blur'
            }
          ]
        },
        checked: true
      };
    },
    methods: {
      handleReset2() {
        this.$refs.ruleForm2.resetFields();
      },
      handleSubmit2(ev) {
        var _this = this;
        this.$refs.ruleForm2.validate(valid => {
          if (valid) {
            this.logining = true;
            var loginParams = {
              username: this.ruleForm2.account,
              password: this.ruleForm2.checkPass
            };
            UserApi.login(loginParams, true)
              .then(res => {
                this.logining = false;
                setCookie('userinfo', res.data);
                setCookie('isLogin',true);
                this.$router.push({
                  path: '/'
                });
              })
              .catch(error => {
                this.logining = false;
              });
          }
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
  .login-container {
    box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    margin: 180px auto;
    width: 350px;
    padding: 35px 35px 15px 35px;
    background: #323a4a;
    border: 1px solid #384050;
    box-shadow: 0 0 12px 0 rgba(0, 0, 0, .4);

    .title {
      margin: 0px auto 30px auto;
      text-align: center;
      color: #FFFFFF;
      font-size: 16px;
    }

    .remember {
      margin: 0px 0px 35px 0px;
    }
  }
</style>
