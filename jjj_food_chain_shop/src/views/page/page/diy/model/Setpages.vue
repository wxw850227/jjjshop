<template>

  <div class="phone-top" v-if="diyData.page" @click.stop="$parent.onEditer(-1)" :style="'background:' + diyData.page.style.titleBackgroundColor">
    <div class="status-bar">
      <span class="icon iconfont icon-wifi"  :style="'color:' + diyData.page.style.titleTextColor">
      </span>
      <span class="time" :style="'color:' + diyData.page.style.titleTextColor">19:00</span>
	  <span class="icon iconfont icon-xinhao"  :style="'color:' + diyData.page.style.titleTextColor">
	  </span>
	  <span class="ml4 icon iconfont icon-iconset0250"  :style="'color:' + diyData.page.style.titleTextColor">
	  </span>
    </div>
    <div class="navigation d-s-c" :style="'color:' + diyData.page.style.titleTextColor">
      <img :src="diyData.page.style.toplogo" alt="">
      <div class="phone-top-search-box">搜索商品</div>
      <div class="flex-1"></div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {};
    },
    props: {
      diyData: Object
    }
  };
</script>

<style>
  .phone-top-search-box {
    width: 80%;
    height: 30px;
    line-height: 30px;
    background-color: #FFFFFF;
    border-radius: 3px;
    margin-left: 10px;
    color: #999999;
  }
  .navigation>img{
    width: 30px;
    height: 30px;
  }
</style>
