<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 设置页面
    -->
  <div>
    <div class="common-form">
      <span>{{ curItem.name }}</span>
    </div>
    <el-form size="small" :model="curItem" label-width="100px">
      <!--页面名称-->
      <el-form-item label="页面名称：">
        <el-input v-model="curItem.params.name"></el-input>
        <p class="gray">页面名称仅用于后台查找</p>
      </el-form-item>
      <!--页面标题-->
      <el-form-item label="页面标题：">
        <el-input v-model="curItem.params.title"></el-input>
        <p class="gray">小程序端顶部显示的标题</p>
      </el-form-item>
      <!--分享标题-->
      <el-form-item label="分享标题：">
        <el-input v-model="curItem.params.share_title"></el-input>
        <p class="gray">小程序端转发时显示的标题</p>
      </el-form-item>
      <!--文字颜色-->
      <el-form-item label="文字颜色：">
        <el-radio-group v-model="curItem.style.titleTextColor">
          <el-radio :label="'black'">黑色</el-radio>
          <el-radio :label="'white'">白色</el-radio>
        </el-radio-group>
      </el-form-item>
      <!-- 背景颜色 -->
      <el-form-item label="背景颜色：">
        <div class="d-s-c">
          <el-color-picker v-model="curItem.style.titleBackgroundColor"></el-color-picker>
          <el-button type="button" style="margin-left: 10px;" @click.stop="$parent.$parent.onEditorResetColor(curItem.style, 'btnColor', '#ffffff')">重置</el-button>
        </div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  export default {
    data() {
      return {

      };
    },
    props: ['curItem', 'selectedIndex', 'opts'],
    created() {
      console.log(this.curItem)
    },
    methods: {

    }
  };
</script>

<style>
</style>
