<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 视频
    -->
  <div>
    <div class="common-form">
      <span>{{ curItem.name }}</span>
    </div>
    <el-form size="small" :model="curItem" label-width="100px">
      <!--上下边距-->
      <el-form-item label="上下边距：">
        <el-slider v-model.number="curItem.style.paddingTop" show-input></el-slider>
      </el-form-item>
      <!--视频高度-->
      <el-form-item label="视频高度：">
        <el-slider v-model.number="curItem.style.height" :min="50" :max="500" show-input></el-slider>
        <div>
          滑块可用左右方向键精确调整
        </div>
      </el-form-item>
      <!--视频封面-->
      <el-form-item label="视频封面：">
        <div class="diy-video-cover">
          <img v-img-url="curItem.params.poster" alt="" @click="$parent.onEditorSelectImage(curItem.params, 'poster')">
        </div>
      </el-form-item>
      <!--视频地址-->
      <el-form-item label="视频地址：">
        <el-input v-model="curItem.params.videoUrl"></el-input>
      </el-form-item>
      <!--自动播放-->
      <el-form-item label="自动播放：">
        <el-radio-group v-model="curItem.params.autoplay">
            <el-radio :label="0">否</el-radio>
            <el-radio :label="1">是</el-radio>
          </el-radio-group>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        paddingTop:0,
        height:0
      };
    },
    props: ['curItem', 'selectedIndex'],
    created() {
      console.log(this.curItem);
    },
    watch:{

    },
    methods: {

    }
  };
</script>

<style>
.diy-video-cover{ width: 200px;}
.diy-video-cover img{ width: 100%;}
</style>
