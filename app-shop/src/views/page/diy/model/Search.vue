<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 搜索栏
    -->
  <div class="drag optional" :class="{ selected: index === selectedIndex }" @click.stop="$parent.$parent.onEditer(index)">
    <div class="diy-search">
      <div class="inner" :class="'inner-'+item.style.searchStyle">
        <div class="search-input" :class="'search-input-'+item.style.textAlign">
          <i class="el-icon-search"></i>
          <span>{{ item.params.placeholder }}</span>
        </div>
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
    return {};
  },
  props: ['item', 'index', 'selectedIndex'],
  created() {
  },
  methods: {

  }
};
</script>

<style>
.diy-search{padding: 10px;background: #f1f1f2;}
.diy-search .inner{
    height: 30px;
    line-height: 30px;
    background: #fff;
    overflow: hidden;}
.diy-search .inner-3{}
.diy-search .inner-6{border-radius: 8px;}
.diy-search .inner-9{border-radius: 15px;}
.diy-search .inner .search-input{ padding: 0 10px; display: flex; align-items: center;}
.diy-search .search-input-3{ justify-content: flex-start;}
.diy-search .search-input-6{ justify-content: center;}
.diy-search .search-input-9{ justify-content: flex-end;}
</style>
