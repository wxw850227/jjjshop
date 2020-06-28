<template>
  <div class="error-404">
    <div class="title">
      404
    </div>
    <div class="des">哎，页面<span class="red">出错了</span>？！</div>
    <div class="menu">
      你可以尝试<a href="javascript:void(0)" @click="Refresh()">刷新页面</a>，或者<a href="/">跳转至首页</a>
    </div>
  </div>
</template>

<script>
  export default{
    data(){
      return{}
    },
    methods:{

      /*刷新页面*/
      Refresh(){
        window.location.reload();
      }

    }
  }
</script>

<style>
.error-404{ width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column;}
.error-404 .title{ font-size: 200px; line-height: 200px;}
.error-404 .des{ margin-top: 30px; font-size: 20px;}
.error-404 .menu{ margin-top: 10px; font-size: 14px;}
</style>
