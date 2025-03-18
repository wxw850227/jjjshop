<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品编辑-基础信息
    -->
  <div class="basic-setting-content pl16 pr16">
    <!--基本信息-->
    <div class="common-form">基本信息</div>
    <el-form-item label="商品名称：" :rules="[{ required: true, message: '请填写商品名称' }]" prop="model.product_name">
      <el-input v-model="form.model.product_name" class="max-w460"></el-input>
    </el-form-item>
    <el-form-item label="所属分类：" :rules="[{ required: true, message: '请选择商品分类' }]" prop="model.category_id">
      <el-select v-model="form.model.category_id">
        <template>
          <el-option  v-for="cat in formData.category" v-if="formData.category.length>0" :value="cat.category_id" :key="cat.category_id" :label="cat.name"></el-option>
        </template>
      </el-select>
    </el-form-item>
    <el-form-item label="商品图片：" :rules="[{ required: true, message: '请上传商品图片' }]" prop="model.image">
      <div class="draggable-list">
        <draggable class="wrapper" v-model="form.model.image">
          <transition-group>
            <div class="item" v-for="(item, index) in form.model.image" :key="item.file_path">
              <img v-img-url="item.file_path" />
              <a href="javascript:void(0);" class="delete-btn" @click.stop="deleteImg(index)"><el-icon><Close /></el-icon></a>
            </div>
          </transition-group>
        </draggable>
        <div class="item img-select" @click="openProductUpload"><el-icon><Plus /></el-icon></div>
      </div>
    </el-form-item>
    <el-form-item label="商品卖点：">
      <el-input type="textarea" v-model="form.model.selling_point" class="max-w460"></el-input>
    </el-form-item>

    <!--商品图片组件-->
    <Upload v-if="isProductUpload" :config="{ total: 9 }" :isupload="isProductUpload" @returnImgs="returnProductImgsFunc">上传图片</Upload>
  </div>
</template>

<script>

import Upload from '@/components/file/Upload.vue';
import draggable from 'vuedraggable';
export default {
  components: {
    Upload,
    draggable
  },
  data() {
    return {
      formData:{},
      isProductUpload: false
    };
  },
  inject: ['form'],
  created() {
    this.formData = this.form;
    console.log(this.formData.model.category)
  },
  methods: {

    /*打开上传图片*/
    openProductUpload: function() {
      this.isProductUpload = true;
    },

    /*上传商品图片*/
    returnProductImgsFunc(e) {
      if (e != null) {
        let imgs = this.form.model.image.concat(e);
        this.form.model.image = imgs;
      }
      this.isProductUpload = false;
    },

    /*删除商品图片*/
    deleteImg(index) {
      this.form.model.image.splice(index, 1);
    },
  }
};
</script>

<style></style>
