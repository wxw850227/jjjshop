<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 头条快报
    -->
  <div>
    <div class="common-form">
      <span>{{ curItem.name }}</span>
    </div>
    <el-form size="small" :model="curItem" label-width="100px">
      <!-- 文章分类 -->
      <el-form-item label="文章分类：">
        <el-select v-model="curItem.params.auto.category">
          <el-option label="全部分类" :value="0"></el-option>
            <el-option
              v-for="item in category"
              :key="item.category_id"
              :label="item.name"
              :value="item.category_id">
            </el-option>
        </el-select>
      </el-form-item>
      <!-- 显示数量 -->
      <el-form-item label="显示数量：">
        <el-input type="number" min="1" v-model="curItem.params.auto.showNum" style="width: auto;"></el-input>
        <div>
          文章数据请到
          <a href="#/plus/article/index" target="_blank">内容管理 - 文章列表</a>
          中管理
        </div>
      </el-form-item>
      <!--图片-->
      <el-form-item label="图片：">
        <div class="diy-special-cover">
          <img v-img-url="curItem.style.image" alt="" @click="$parent.onEditorSelectImage(curItem.style, 'image')">
          <div>建议尺寸140×38</div>
        </div>
      </el-form-item>
      <!--显示类型-->
      <el-form-item label="显示类型：">
        <el-radio-group v-model="curItem.style.display">
          <el-radio :label="1">单行</el-radio>
          <el-radio :label="2">两行</el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import ArticleApi from '@/api/article.js';
  export default {
    data() {
      return {
        category: [],
      };
    },
    props: ['curItem', 'selectedIndex', 'opts'],
    created() {
      /*获取文章栏目*/
      this.getData();
    },
    methods: {

      /*获取文章栏目*/
      getData() {
        let self = this;
        ArticleApi.getCategory({
            page_id: self.page_id
          }, true).then(res => {
            self.category = res.data.category;
          })
          .catch(error => {
            self.loading = false;
          });
      },
    }
  };
</script>

<style>
  .diy-special-cover img {
    width: 140px;
    height: 38px;
  }
</style>
