<template>
  <!--
          作者：luoyiming
          时间：2019-10-24
          描述：后台系统头部
      -->
  <div class="common-header">
    <div class="breadcrumb">
      <!--标题显示-->
      <div class="baseInfo-left-base">
        <span class="name">{{ menu_title }}</span>
      </div>
      <!--右边操作-->
      <div class="header-navbar">
        <div style="padding-right: 10px;">
          <a href="http://demo2.jjjshop.net/shop">商业版演示入口</a>
        </div>
        <!--操作员账号-->
        <div class="header-navbar-icon">
          <span class="ml4"><Icon :iconname="'#icon-geren9'"></Icon></span>
          <span class="text ml4">{{ userinfo.user_name }}，欢迎您！</span>
        </div>
        <div class="header-navbar-icon"><span class="gray">|</span></div>
        <!--修改密码-->
        <a href="javascript:void(0);" class="header-navbar-icon link" @click="passwordFunc()"><span class="text">修改密码</span></a>
        <!--退出登录-->
        <a href="javascript:void(0);" class="header-navbar-icon login-out link" @click="login_out()">
          <Icon :iconname="'#icon-tuichu'"></Icon>
          <span class="text ml4">退出</span>
        </a>
      </div>
    </div>

    <!--修改密码弹窗-->
    <UpdatePassword v-if="is_password" @close="closeFunc"></UpdatePassword>

  </div>
</template>

<script>
import bus from '@/utils/eventBus.js';
import AuthApi from '@/api/auth.js';
import UserApi from '@/api/user.js';
import { setCookie, delCookie, getCookie, deleteSessionStorage } from '@/utils/base.js';
import UpdatePassword from './part/UpdatePassword.vue';
export default {
  components: {
    UpdatePassword
  },
  data() {
    return {
      /*菜单名称*/
      menu_title: '菜单',
      /*个人信息*/
      userinfo: {
        user_name:''
      },
      /*切换选中*/
      activeValue: 0,
      /*是否修改密码*/
      is_password: false
    };
  },
  created() {

    /*监听菜单传过来的值*/
    bus.$on('menuName', res => {
      this.menu_title = res;
    });

    /*判断cookie里是否有个人信息*/
    if(getCookie('userinfo')!=null){
      /*获取登录用户名*/
      this.userinfo.user_name = getCookie('userinfo');
    }else{
      /*cookie里没有个人信息，调用接口获取个人信息*/
      this.getUserInfo();
    }

    //发送给其它组件头部加载完成
    bus.$emit('headLoad', true);


  },
  beforeDestroy() {
    /*离开删除监听*/
    bus.$off('menuName');
  },
  mounted() {},
  methods: {

    /*获取用户信息*/
    getUserInfo() {
      let self = this;
      let Params = {};
      AuthApi.getUserInfo(Params, true)
        .then(res => {
          if(res.code==1){
            self.userinfo = res.data.user;
            /*保存个人信息*/
            setCookie('userinfo', res.data.user);
          }
        })
        .catch(error => {

        });
    },

    /*退出登录*/
    login_out() {
      this.$confirm('此操作将退出登录, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          UserApi.loginOut({}, true)
            .then(data => {
              /*删除登录状态*/
              delCookie('isLogin');
               /*删除用户菜单*/
              deleteSessionStorage('rolelist');
              /*删除用户权限*/
              deleteSessionStorage('authlist');
               /*返回登录页*/
              this.$router.replace({ path: '/login' });
              /*删除stores数据*/
              this.$store.commit('user/setState',{key:'roles',val:null});
              /*刷新页面*/
              location.reload();
            })
            .catch(error => {});
        })
        .catch((error) => {
          console.log(error);
        });
    },

    /*打开修改密码弹窗*/
    passwordFunc() {
      this.is_password = true;
    },

    /*关闭修改密码弹窗*/
    closeFunc() {
      this.is_password = false;
    }
  }
};
</script>

<style lang="scss" scoped="scoped">
.common-header .el-tabs__nav-wrap::after {
  display: none;
}
a.header-navbar-icon:hover .text{ text-decoration: underline; color:$blue;}
</style>
