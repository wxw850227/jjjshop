<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 搜索栏
    -->
    <div class="drag optional" @click.stop="$parent.$parent.onEditer(index)" :class="{selected: index === selectedIndex}">
      <div class="diy-banner">
        <div class="img-list">
          <img :key="index" v-for="(banner, index) in item.data" v-if="index <= 1" v-img-url="banner.imgUrl">
        </div>
        <div class="dots center" :class="item.style.btnShape">
          <span :key="index" v-for="(banner,index) in item.data" :style="{background:item.style.btnColor}"></span>
        </div>
      </div>
      <div class="btn-edit-del">
        <div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div>
      </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {

      };
    },
    props: ['item', 'index', 'selectedIndex'],
    methods: {

    }
  };
</script>

<style>
.diy-banner{ height: 180px; overflow: hidden;}
.diy-banner img{width: 100%; height: 180px; object-fit: fill;}
</style>
