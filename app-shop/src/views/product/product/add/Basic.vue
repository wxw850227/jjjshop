<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品添加-基础信息
    -->
  <div class="basic-setting-content pl16 pr16">
    <!--基本信息-->
    <div class="common-form">基本信息</div>
    <el-form-item label="产品名称：" :rules="[{required: true,message: ' '}]" prop="model.product_name">
      <el-input v-model="form.model.product_name" class="max-w460"></el-input>
    </el-form-item>
    <el-form-item label="产品编码：">
      <el-input v-model="form.model.product_no" class="max-w460"></el-input>
    </el-form-item>
    <el-form-item label="所属分类：" :rules="[{required: true,message: ' '}]" prop="model.category_id">
      <el-select v-model="form.model.category_id">
        <template v-for="cat in form.catgory">
          <el-option :value="cat.category_id" :key="cat.category_id" :label="cat.name"></el-option>
          <template v-if="cat.child !== undefined" v-for="two in cat.child">
            <el-option :value="two.category_id" :key="two.category_id" :label="two.name" style="padding-left: 30px;"></el-option>
            <template v-if="two.child !== undefined" v-for="three in two.child">
              <el-option :value="three.category_id" :key="three.category_id" :label="three.name" style="padding-left: 60px;"></el-option>
            </template>
          </template>
        </template>
      </el-select>
    </el-form-item>
    <el-form-item label="产品图片：" :rules="[{required: true,message: '请上传产品图片'}]" prop="model.image">
      <div class="draggable-list">
        <draggable class="wrapper" v-model="form.model.image">
          <transition-group>
            <div class="item" v-for="item in form.model.image" :key="item.file_path">
              <img v-img-url="item.file_path" />
              <a href="javascript:void(0);" class="delete-btn" @click.stop='deleteImg(item)'><i class="el-icon-close"></i></a>
            </div>
          </transition-group>
        </draggable>
        <div class="item img-select" @click="openProductUpload">
          <i class="el-icon-plus"></i>
        </div>
      </div>
    </el-form-item>
    <el-form-item label="产品卖点：">
      <el-input v-model="form.model.selling_point" class="max-w460"></el-input>
    </el-form-item>

    <!--规格设置-->
    <div class="common-form mt50">规格/库存</div>

    <Spec :form="form"></Spec>

    <!--减库存方式-->
    <el-form-item label="库存计算方式：">
      <el-radio-group v-model="form.model.deduct_stock_type">
        <el-radio :label="10">下单减库存</el-radio>
        <el-radio :label="20">付款减库存</el-radio>
      </el-radio-group>
    </el-form-item>
    <!--商品详情-->
    <el-form-item label="商品详情：">
      <div class="edit_container">
        <Uediter :text="ueditor.text" :config="ueditor.config" ref="ue"></Uediter>
      </div>
    </el-form-item>
    <!--商品图片组件-->
    <Upload v-if="isProductUpload" :config="{total:9}" :isupload="isProductUpload" @returnImgs="returnProductImgsFunc">上传图片</Upload>
  </div>
</template>

<script>
  import Spec from './Spec.vue';
  import Uediter from '@/components/UE.vue';
  import Upload from '@/components/file/Upload';
  import draggable from 'vuedraggable';
  export default {
    components: {
      /*规格信息*/
      Spec,
      /*编辑器*/
      Uediter,
      Upload,
      draggable
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
        isProductUpload: false,
      }
    },
    props: ['form'],
    created() {
      this.ueditor.text = this.form.model.content;
    },
    methods: {
      openProductUpload: function() {
        this.isProductUpload = true;
      },
      getContent: function() {
        return this.$refs.ue.getUEContent();
      },
      /*上传产品图片*/
      returnProductImgsFunc(e) {
        if(e!=null){
          let imgs=this.form.model.image.concat(e);
          this.$set(this.form.model,'image',imgs);
        }
        this.isProductUpload = false;
      },
      /*删除产品图片*/
      deleteImg(e) {
        for (var i = 0; i < this.form.model.image.length; i++) {
          if (e.file_id == this.form.model.image[i].file_id) {
            this.form.model.image.splice(i, 1);
          }
        }
      }
    }
  }
</script>

<style>
  .edit_container {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    line-height: 20px;
    color: #2c3e50;
  }

  .ql-editor {
    height: 400px;
  }

  .draggable-list {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
  }
  .draggable-list .wrapper>span{
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;}

  .draggable-list .item {
    position: relative;
    width: 110px;
    height: 110px;
    margin-top: 10px;
    margin-right: 10px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #DDDDDD;
  }

  .draggable-list .delete-btn {
    position: absolute;
    top: 0;
    right: 0;
    width: 16px;
    height: 16px;
    background: red;
    line-height: 16px;
    font-size: 16px;
    color: #FFFFFF;
    display: none;
  }

  .draggable-list .item:hover .delete-btn {
    display: block;
  }

  .draggable-list .item img {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    max-height: 100%;
    max-width: 100%;
  }

  .draggable-list .img-select {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px dashed #DDDDDD;
    font-size: 30px;
  }

  .draggable-list .img-select i {
    color: #409EFF;
  }
</style>
