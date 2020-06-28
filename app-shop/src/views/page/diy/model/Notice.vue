<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 搜索栏
    -->
  <div>
    <div @click.stop="$parent.$parent.onEditer(index)">
      <div class="drag optional" :class="{selected:index === selectedIndex}">
        <div class="diy-notice" :style="{padding: item.style.paddingTop + 'px' + ' 10px', background: item.style.background}">
          <div class="notice-icon">
            <img v-img-url="item.params.icon">
          </div>
          <div class="notice-text text-ellipsis flex-1">
            <span :style="{color: item.style.textColor}">{{item.params.text}}</span>
          </div>
        </div>
        <div class="btn-edit-del">
          <div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div>
        </div>
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
.diy-notice{ display: flex; justify-content: flex-start; align-items: center;}
.diy-notice .notice-icon{ width: 32px; height: 32px;}
.diy-notice .notice-text{ margin-left: 10px; overflow: hidden;}
</style>
