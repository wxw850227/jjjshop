<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 富文本框
    -->

    <div @click.stop="$parent.$parent.onEditer(index)">
      <div class="drag optional" :class="{selected: index === selectedIndex}">
        <div class="diy-richText" :style="{background: item.style.background, padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px'}"
          v-html="item.params.content">
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
.diy-richText video{ width: 100%;}
</style>
