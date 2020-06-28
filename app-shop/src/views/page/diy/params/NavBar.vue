<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 搜索栏
    -->
  <div>
    <div class="common-form"><span>{{ curItem.name }}</span></div>
    <el-form size="small" :model="curItem" label-width="100px">
      <!--背景颜色-->
      <el-form-item label="背景颜色：">
        <div class="d-s-c">
            <el-color-picker v-model="curItem.style.background"></el-color-picker>
            <el-button type="button" style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'btnColor', '#ffffff')">
              重置
            </el-button>
        </div>
      </el-form-item>
      <!--每行数量-->
      <el-form-item label="每行数量：">
        <el-radio-group v-model="curItem.style.rowsNum">
            <el-radio :label="3">3个</el-radio>
            <el-radio :label="4">4个</el-radio>
            <el-radio :label="5">5个</el-radio>
          </el-radio-group>
      </el-form-item>
      <el-form-item label="图片：">
        <div class="param-img-item" :key="index" v-for="(navBar, index) in curItem.data">
          <div class="delete-box">
            <i class="el-icon-delete-solid" @click="$parent.onEditorDeleleData(index, selectedIndex)"></i>
          </div>
          <div class="icon">
            <img v-img-url="navBar.imgUrl" alt="" @click="$parent.onEditorSelectImage(navBar, 'imgUrl')">
          </div>
          <p class="tc gray9">建议尺寸100x100</p>
          <div class="url-box">
            <span class="key-name">文字内容：</span>
            <el-input v-model="navBar.text"></el-input>
          </div>
          <div class="url-box">
            <span class="key-name">颜色：</span>
            <div class="d-s-c">
                <el-color-picker v-model="navBar.color"></el-color-picker>
                <el-button type="button" style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'btnColor', '#ffffff')">
                  重置
                </el-button>
            </div>
          </div>
          <div class="d-s-c">
            <div class="url-box flex-1">
            <span class="key-name">链接名称：</span>
            <el-input v-model="navBar.name"></el-input>
          </div>
          <div class="url-box ml10">
            <el-button type="primary" @click="changeLink(index)">选择链接</el-button>
          </div>
          </div>
        </div>
        <div class="d-c-c">
          <el-button @click="$parent.onEditorAddData">添加一个</el-button>
        </div>
      </el-form-item>
    </el-form>
    <Setlink v-if="is_linkset" :is_linkset="is_linkset" @closeDialog="closeLinkset">选择链接</Setlink>
  </div>
</template>

<script>
    import Setlink from '@/components/setlink/Setlink';
  export default {
      components: {
          Setlink,
      },
    data() {
      return {
        /*是否选择链接*/
          is_linkset: false,
          index: null,
      };
    },
    props: ['curItem', 'selectedIndex', 'opts'],
    methods: {
      /*选择链接*/
        changeLink(index) {
            this.is_linkset = true;
            this.index = index;
        },
      /*获取链接并关闭弹窗*/
        closeLinkset(e) {
            this.is_linkset = false;
            this.curItem.data[this.index].linkUrl=e.url;
//                this.curItem.data[this.index].name = e.name;
            this.curItem.data[this.index].name = '链接到' + ' ' + e.type + ' ' + e.name;
        },
    }
  };
</script>

<style>

</style>
