<template>
  <!--
          作者：luoyiming
          时间：2019-10-24
          描述：登录页面
      -->
  <div class="login-bg" :style="'background-image:url(' + bgimg_url + ');'">
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-position="left" label-width="0px" class="demo-ruleForm login-container">
      <h3 class="title">三勾商城系统</h3>
      <!--用户名-->
      <el-form-item prop="account"><el-input type="text" v-model="ruleForm.account" auto-complete="off" placeholder="账号"></el-input></el-form-item>
      <!--密码-->
      <el-form-item prop="checkPass"><el-input type="password" v-model="ruleForm.checkPass" auto-complete="off" placeholder="密码"></el-input></el-form-item>
      <!--登录-->
      <el-form-item>
        <el-button type="primary" style="width:100%;" @click.native.prevent="SubmitFunc" :loading="logining">登录</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import bgimg from '@/assets/img/login_bg.jpg';
import UserApi from '@/api/user.js';
import { setCookie, setSessionStorage } from '@/utils/base.js';
export default {
  data() {
    return {
      /*背景图片*/
      bgimg_url: bgimg,
      /*是否加载完整*/
      logining: false,
      /*表单对象*/
      ruleForm: {
         /*用户名*/
        account: '',
        /*密码*/
        checkPass: ''
      },
      /*验证规则*/
      rules: {
        /*用户名*/
        account: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
         /*密码*/
        checkPass: [{ required: true, message: '请输入密码', trigger: 'blur' }]
      }
    };
  },
  methods: {

    /*登录方法*/
    SubmitFunc(ev) {
      var _this = this;
      this.$refs.ruleForm.validate(valid => {
        if (valid) {
          this.logining = true;
          var Params = {
            username: this.ruleForm.account,
            password: this.ruleForm.checkPass ,
          };
          /*调用登录接口*/
          UserApi.login(Params, true)
            .then(res => {
              this.logining = false;
              if(res.code==1){
                /*保存个人信息*/
                setCookie('userinfo', res.data);
                /*设置一个登录状态*/
                setCookie('isLogin', true);
                /*跳转到首页*/
                this.$router.push({ path: '/' });
              }else{
                this.$message({
                  message: '登录失败',
                  type: 'error'
                });
              }
            })
            .catch(error => {
              //接口调用方法统一处理
              this.logining = false;
            });
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.login-bg {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-repeat: no-repeat;
  background-position: center;
  background-size: 100%;
}

.login-container {
  box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.1), 0 1px 0px 0 rgba(0, 0, 0, 0.04);
  -webkit-border-radius: 5px;
  border-radius: 5px;
  -moz-border-radius: 5px;
  background-clip: padding-box;
  position: fixed;
  width: 350px;
  left: 50%;
  top: 50%;
  margin-left: -175px;
  margin-top: -175px;
  padding: 35px 35px 15px 35px;
  background: #fff;
  border: 1px solid #eaeaea;
  .title {
    margin: 0px auto 40px auto;
    text-align: center;
    color: #505458;
  }
  .remember {
    margin: 0px 0px 35px 0px;
  }
}
</style>
