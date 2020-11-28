<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 头条快报
    -->
    <div @click.stop="$parent.$parent.onEditer(index)">
      <div class="drag optional" :class="{selected:index === selectedIndex}">
        <div class="diy-special">
          <div class="special-left">
            <img v-img-url="item.style.image" alt="">
          </div>
          <div class="special-content" :class="'display_' + item.style.display">
            <ul class="special-content-list">
              <li class="content-item text-ellipsis" v-for="(item,index) in (item.params.source == 'choice' ? item.data : item.defaultData)" :key="index">
                <span>{{ item.article_title }}</span>
              </li>
            </ul>
          </div>
          <div class="special-more">
            <i class="el-icon-arrow-right"></i>
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
.diy-special{ padding: 4px; display: flex; justify-content: space-between; align-items: center;}
.diy-special .special-left{ width: 105px; height: 27px;}
.diy-special .special-left img{ width: 100%; height: 100%;}
.diy-special .special-more{ font-size: 18px;}
.diy-special .special-content{ flex: 1; margin: 0 10px; overflow: hidden;}
.diy-special .content-item{ padding: 4px 0;}
.diy-special .special-content.display_1{ height: 22px;}
.diy-special .special-content.display_2{ height: 44px;}
</style>
