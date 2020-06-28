<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 客服
    -->
  <div>
    <div class="common-form">
      <span>{{ curItem.name }}</span>
    </div>
    <el-form size="small" :model="curItem" label-width="100px">
      <!--底边距-->
      <el-form-item label="底边距："><el-slider v-model="curItem.style.bottom" show-input></el-slider></el-form-item>
      <!--右边距-->
      <el-form-item label="右边距："><el-slider v-model="curItem.style.right" show-input></el-slider></el-form-item>
      <!--不透明度-->
      <el-form-item label="不透明度："><el-slider v-model="curItem.style.opacity" show-input></el-slider></el-form-item>
      <!--客服类型-->
      <el-form-item label="客服类型：">
        <el-radio-group v-model="curItem.params.type">
            <el-radio :label="'chat'">在线聊天</el-radio>
            <el-radio :label="'phone'">拨打电话</el-radio>
          </el-radio-group>
      </el-form-item>
      <el-form-item label="电话号码：" v-show="curItem.params.type == 'phone'">
        <el-input v-model="curItem.params.phone_num"></el-input>
      </el-form-item>
      <!--客服图标-->
      <el-form-item label="客服图标：">
        <div class="diy-service-icon">
          <img v-img-url="curItem.params.image" alt="" @click="$parent.onEditorSelectImage(curItem.params, 'poster')">
        </div>
        <div>建议尺寸90×90</div>
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
    methods: {

    }
  };
</script>

<style>
.diy-service-icon, .diy-service-icon img{ width: 40px; height: 40px;}
</style>
