<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 图片
    -->
    <div @click.stop="$parent.$parent.onEditer(index)">
      <div class="drag optional" :class="{selected:index === selectedIndex}">
        <div class="diy-imageSingle" :style="{ paddingBottom: item.style.paddingTop + 'px', background: item.style.background}">
          <div class="item-image" :key="index" v-for="(imageSingle, index) in item.data" :style="{padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px 0'}">
            <img v-img-url="imageSingle.imgUrl">
          </div>
        </div>
        <div class="btn-edit-del">
          <div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div>
        </div>
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
.diy-imageSingle img{ max-width: 100%;}
</style>
