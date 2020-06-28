<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 搜索栏
    -->
  <div>
    <div @click.stop="$parent.$parent.onEditer(index)" class="drag optional" :class="{selected: index === selectedIndex}">
      <div class="diy-guide" :style="{padding: item.style.paddingTop +'px '+'0', background:item.style.background }">
        <p class="line" :style="{borderTopWidth: item.style.lineHeight +'px',borderTopColor:item.style.lineColor,borderTopStyle: item.style.lineStyle }">
        </p>
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

</style>
