<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品编辑-规格/库存-多规格
    -->
  <div>
    <!--规格类别-->
    <Type></Type>

    <!--规定表格显示-->
    <Table></Table>
  </div>
</template>

<script>
import Type from './many/Type.vue';
import Table from './many/Table.vue';
export default {
  components: {
    /*规格类别属性*/
    Type,
    /*规格表格*/
    Table
  }
};
</script>

<style></style>
