<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-24
    	描述：后台系统首页
    -->
    <div class="home-container">
      <h1 class="home-title">
            Saas运营管理系统
      </h1>
      <p class="home-des">
        尊敬的 {{ userInfo && userInfo.userName}}  用户，欢迎使用商城管理员系统
      </p>

    </div>
</template>

<script>
  import { useUserStore } from "@/store";
  import UserApi from '@/api/user.js';
  const { userInfo } = useUserStore();
  export default {
    data() {
      return {
        userInfo,
      };
    },
    created() {
      /*获取数据*/
      this.getTableList();
    },
      methods: {
        /*获取数据*/
          getTableList() {
              UserApi.getVersion({}).then(data => {
                      this.loading = false;
                      this.version=data.data.version;
                  })
                  .catch(error => {
                    this.loading = false;
                  });
          },
      }
  }
</script>

<style lang="scss">
  .home-container {
    height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    align-content:center;
    flex-direction: column;
    // background: url(/static/imgs/home_bg.jpg) center center no-repeat;
  }
.home-container .home-title{
  padding: 20px;
  text-align: center;
 white-space: nowrap;
  text-align: center;
  font-size: 40px; color:#409EFF;}
.home-container .home-des{color: #888888;}

  .home-index{
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: horizontal;
      -webkit-box-direction: normal;
      -ms-flex-direction: row;
      flex-direction: row;
      -webkit-box-pack: justify;
      -ms-flex-pack: justify;
      justify-content: space-between;
      min-width: 1000px;
      overflow-x: auto;
  }
</style>
