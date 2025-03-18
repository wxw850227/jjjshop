<template>
  <div class="login-bg" :style="'background-image:url(' + bgimg_url + ');'">
    <div>
      <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-position="left" label-width="0px"
        class="demo-ruleForm login-container d-b-c">
        <div class="log_img">
          <img :src="log_url">
        </div>
        <div class="flex-1 login-box">
          <h3 class="title" style="margin-bottom: 20px;">{{shop_name}}</h3>
          <!--用户名-->
          <el-form-item prop="account">
            <div class="left-img-input"><img class="l-img" src="/src/assets/img/user.png">
              <el-input class="l-input" type="text" v-model="ruleForm.account" auto-complete="off" placeholder="账号">
              </el-input>
            </div>
            <!-- <el-input type="text" v-model="ruleForm.account" auto-complete="off" placeholder="账号"></el-input> -->
          </el-form-item>
          <!--密码-->
          <el-form-item prop="checkPass">
            <div class="left-img-input"><img class="l-img" src="/src/assets/img/password.png">
              <el-input type="password" class="l-input" v-model="ruleForm.checkPass" auto-complete="off"
                placeholder="密码">
              </el-input>
            </div>
            <!-- <el-input type="password" v-model="ruleForm.checkPass" auto-complete="off" placeholder="密码"></el-input> -->
          </el-form-item>
          <el-form-item prop="verifycode" style="line-height:0px;">
            <div class="d-b-c">
              <div class="left-img-input" style="width: auto;">
                <el-input v-model="ruleForm.verifycode" ref="verifycode" placeholder="验证码" class="l-input"
                  style="width:230px;"></el-input>
              </div>
              <div class="identifybox" style="height: 41px; flex-shrink: 0;" @click="refreshCode">
                <sidentify :identifyCode="identifyCode"></sidentify>
              </div>
            </div>
          </el-form-item>
          <!--登录-->
          <el-form-item>
            <el-button type="primary" style="width:100%;height: 51px;font-size: 18px;"
              @click.native.prevent="SubmitFunc" :loading="logining">登录
            </el-button>
          </el-form-item>
        </div>

      </el-form>
    </div>

  </div>
</template>

<script>
  import sidentify from '@/components/sidentify.vue';
  import IndexApi from '@/api/index.js';
  import bgimg from '@/assets/img/login_bg.png';
  import logimg from '@/assets/img/login_logo.png';
  import UserApi from '@/api/user.js';
  import { useUserStore } from '@/store';
   const { afterLogin } = useUserStore();
  export default {

    components: {
      sidentify
    },

    data() {
      // 验证码自定义验证规则
      const validateVerifycode = (rule, value, callback) => {
        if (value === "") {
          this.refreshCode();
          callback(new Error('请输入验证码'))
        } else if (value !== this.identifyCode) {
          console.log('验证码:', value);
          this.refreshCode();
          callback(new Error('验证码不正确!'))
        } else {
          callback()
        }
      }
      return {
        loginForm: {

        },
        identifyCodes: "1234567890", //验证码的数字库
        identifyCode: "", // 验证码组件传值
        /*是否正在加载*/
        loading: true,
        /*商城名称*/
        shop_name: '',
        /*背景图片*/
        bgimg_url: '',
        log_url: '',
        /*是否正在提交*/
        logining: false,
        /*表单对象*/
        ruleForm: {
          /*用户名*/
          account: 'admin',
          /*密码*/
          checkPass: '123456',
          verifycode: ''
        },
        /*验证规则*/
        rules: {
          /*用户名*/
          account: [{
            required: true,
            message: '请输入用户名',
            trigger: 'blur'
          }],
          /*密码*/
          checkPass: [{
            required: true,
            message: '请输入密码',
            trigger: 'blur'
          }],
          verifycode: [{
            required: true,
            trigger: 'blur',
            validator: validateVerifycode
          }]
        },
        /*基础配置*/
        baseData: {},

      };
    },
    created() {
      this.refreshCode();
      this.getData();
    },
    mounted() {
      this.identifyCode = '';
      this.makeCode(this.identifyCodes, 4);
    },
    methods: {
      //验证码----start
      randomNum(min, max) {
        return Math.floor(Math.random() * (max - min) + min);
      },
      refreshCode() {
        this.identifyCode = "";
        this.makeCode(this.identifyCodes, 4);
      },
      makeCode(o, l) {
        for (let i = 0; i < l; i++) {
          this.identifyCode += this.identifyCodes[
            this.randomNum(0, this.identifyCodes.length)
          ];
        }
        // console.log("this.identifyCode:", this.identifyCode);
      },
      /*获取基础配置*/
      getData() {
        let self = this;
        IndexApi.base(true)
          .then(res => {
            self.loading = false;
            // self.shop_name = res.data.settings.shop_name;
            const { data: { settings: { shop_bg_img, shop_name,shop_logo_img } }} = res;
            self.shop_name = shop_name;
            if (shop_bg_img) {
              self.bgimg_url = shop_bg_img;
            } else {
              self.bgimg_url = bgimg;
            }
            if (shop_logo_img) {
              self.log_url = shop_logo_img;
            } else {
              self.log_url = logimg;
            }
          })
          .catch(error => {
            self.loading = false;
          });
      },

      /*登录方法*/
      SubmitFunc(ev) {
        var _this =this;
        this.$refs.ruleForm.validate(valid => {
          if (valid) {
            this.logining = true;
            var Params = {
              username: this.ruleForm.account,
              password: this.ruleForm.checkPass
            };
            /*调用登录接口*/
            UserApi.login(Params,true)
            .then(async (data) => {
              await afterLogin(data);
              _this.logining = false; 
              _this.$router.push({
                path: '/home'
              })
            })
              .catch(error => {
                console.log("error",error)
                //接口调用方法统一处理
                _this.logining = false;
              });
          }
        });
      }
    }
  };
</script>

<style lang="scss">
  .login-bg {
    width: 100%;
    height: 100%;
    background: no-repeat;
    background-size: cover;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
  }

  .login-container {
    box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.1), 0 1px 0px 0 rgba(0, 0, 0, 0.04);
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    position: fixed;
    width: 961px;
    height: 408px;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    background-color: #FFFFFF;

    .title {
      margin: 0px auto 40px auto;
      text-align: center;
      font-size: 28px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
    }

    .remember {
      margin: 0px 0px 35px 0px;
    }
  }

  .log_img {
    img {
      width: 514px;
      height: 408px;
    }
  }

  .login-box {
    padding: 45px 39px 31px 39px;
    height: 408px;
    box-sizing: border-box;
  }

  .left-img-input {
    width: 370px;
    height: 44px;
    line-height: 44px;
    background: #FFFFFF;
    border: 1px solid #EEEEEE;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 14px;

    .l-img {
      width: 20px;
      height: 20px;
      margin-right: 10px;
      flex-shrink: 0;
    }

    .l-input {
      flex: 1;
      border: none;
      background: none;
      font-size: 14px;
      color: #666666;

    }

    .el-input__inner {
      border: none;
      padding: 0;
    }
  }
</style>
