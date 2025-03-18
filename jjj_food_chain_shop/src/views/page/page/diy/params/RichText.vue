<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 富文本框
    -->
  <div>
    <div class="common-form">
      <span>{{ curItem.name }}</span>
    </div>
    <el-form size="small" :model="curItem" label-width="100px">
      <!--上下边距-->
      <el-form-item label="上下边距："><el-slider v-model="curItem.style.paddingTop" show-input></el-slider></el-form-item>
      <!--左右边距-->
      <el-form-item label="左右边距："><el-slider v-model="curItem.style.paddingLeft" show-input></el-slider></el-form-item>
      <!--背景颜色-->
      <el-form-item label="背景颜色：">
        <div class="d-s-c">
          <el-color-picker v-model="curItem.style.background"></el-color-picker>
          <el-button type="button" style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'btnColor', '#ffffff')">重置</el-button>
        </div>
      </el-form-item>
      <!--内容-->
      <el-form-item label="内容：">
        <Uediter :text="content" :config="ueditor.config" @contentChange="contentChangeFunc" ref="ue"></Uediter>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import Uediter from '@/components/UE.vue';
  export default {
    components:{
      /*编辑器*/
      Uediter
    },
    data() {
      return {
        ueditor: {
          text: '',
          config: {
            initialFrameWidth: 400,
            initialFrameHeight: 500,
          }
        },
        content:''
      };
    },
    props: ['curItem', 'selectedIndex', 'opts'],
    watch:{
    },
    created() {
      this.curItem.style.paddingTop = parseInt(this.curItem.style.paddingTop);
      this.curItem.style.paddingLeft = parseInt(this.curItem.style.paddingLeft);
      this.content=this.curItem.params.content;
    },
    methods: {
      contentChangeFunc(e){
        this.content=e;
        this.curItem.params.content=e;
      }
    }
  };
</script>

<style>

</style>
