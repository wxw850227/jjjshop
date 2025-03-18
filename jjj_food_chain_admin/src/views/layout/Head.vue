<template>
  <div class="common-header">
    <div class="breadcrumb">
      <div class="baseInfo-left-base">
        <span class="name">{{ menu_title }}</span>
      </div>
      <div class="header-navbar">
        <div class="header-navbar-icon">
          <span class="ml4 icon iconfont icon-geren9"></span>
          <span class="text ml4">{{ userInfo && userInfo.userName }}，欢迎您！</span>
        </div>
        <div class="header-navbar-icon" @click.stop="exit">
          <span class="icon iconfont icon-tuichu"></span>
          <span class="text ml4">退出</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    reactive,
    toRefs,
    defineComponent
  } from 'vue';
  import {
    useUserStore
  } from '@/store';
  import UserApi from '@/api/user.js';
  const {
    userInfo,
    bus_on,
    afterLogout
  } = useUserStore();
  export default defineComponent({
    setup() {
      const state = reactive({
        menu_title: '首页',
        userInfo,
      });
      console.log("我是header")
      bus_on("MenuName", (res) => {
        state.menu_title = res;
      });
      return {
        ...toRefs(state),
        userInfo,
        afterLogout,
      };
    },
    methods: {
      exit() {
        let self = this;
        ElMessageBox.confirm(
            '此操作将退出登录, 是否继续?',
            '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning',
            }
          )
          .then(() => {
            UserApi.loginOut({}, true)
              .then(data => {
                self.logout();
              })
              .catch(error => {});
          })
          .catch(() => {
            ElMessage({
              type: 'info',
              message: '已取消退出',
            });
          });
      },
      async logout() {
        await this.afterLogout();
        this.$router.push('/login');
      },
    }
  });
</script>
<style lang="scss">
  .login-out .icon-tuichu {
    color: red;
  }

  .header-navbar-icon .icon-geren9 {
    font-size: 20px;
  }
</style>
