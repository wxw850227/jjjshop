<template>
  <!--
      作者：luoyiming
      时间：2020-06-01
      描述：插件中心-文章-编辑
    -->
  <div class="product-add pb50" v-loading="loading">
    <!--编辑文章-->
     <el-form size="small" :model="form" ref="form" :rules="rules" label-width="100px">
       <div class="common-form">编辑文章</div>
       <el-form-item label="文章标题" prop="article_title"><el-input v-model="form.article_title" placeholder="请输入文章标题" class="max-w460"></el-input></el-form-item>
       <el-form-item label="描述" prop="dec"><el-input v-model="form.dec" placeholder="请输入文章描述" class="max-w460"></el-input></el-form-item>
       <el-form-item label="文章缩略图">
         <div>
           <el-button type="primary" @click="openUpload">上传图片</el-button>
           <img class="mt10" v-img-url="'image.file_path',form"  width="120px;" alt="" />
           <!--上传图片组件-->
           <Upload v-if="isupload" :isupload="isupload" @returnImgs="returnImgsFunc">上传图片</Upload>
         </div>
       </el-form-item>
       <el-form-item label="文章分类">
         <el-select v-model="form.category_id" placeholder="请选择">
           <el-option v-for="(item, index) in category" :key="index" :label="item.name" :value="item.category_id"></el-option>
         </el-select>
       </el-form-item>
       <el-form-item label="虚拟阅读量"><el-input type="number" v-model="form.virtual_views" placeholder="请输入数字" class="max-w460"></el-input></el-form-item>
       <el-form-item label="文章状态">
         <el-radio-group v-model="form.article_status">
           <el-radio :label="1">显示</el-radio>
           <el-radio :label="0">隐藏</el-radio>
         </el-radio-group>
       </el-form-item>
       <el-form-item label="文章内容">
         <div class="edit_container">
           <Uediter v-if="!loading" :text="ueditor.text" :config="ueditor.config" ref="ue"></Uediter>
         </div>
       </el-form-item>
       <el-form-item label="排序"><el-input type="number" v-model="form.article_sort" placeholder="请输入数字" class="max-w460"></el-input></el-form-item>
       <!--提交-->
       <div class="common-button-wrapper">
         <el-button size="small" type="info" @click="cancelFunc" :loading="loading">取消</el-button>
         <el-button size="small" type="primary" @click="onSubmit" :loading="loading">提交</el-button>
       </div>
     </el-form>
  </div>
</template>

<script>
import ArticleApi from '@/api/article.js';
import Uediter from '@/components/UE.vue';
import Upload from '@/components/file/Upload';
export default {
  components: {
    /*编辑器*/
    Uediter,
    /*图片上传*/
    Upload: Upload
  },
  data() {
    return {
      /*是否加载完成*/
      loading: true,
      /*是否上传图片*/
      isupload: false,
      /*富文本配置*/
      ueditor: {
        text: '',
        config: {
          initialFrameWidth: 400,
          initialFrameHeight: 500
        }
      },
      /*form表单数据*/
      form: {
        article_title: '',
        category_id: '',
        image_id: '',
        image:{},
        article_sort: 1,
        article_status: '0',
        virtual_views: 1,
        article_content: ''
      },
      /*新闻类别*/
      category: [],
      /*验证规则*/
      rules: {
        article_title: [{ required: true, message: '请输入文章标题', trigger: 'blur' }],
        dec: [{ required: true, message: '请输入文章描述', trigger: 'blur' }]
      }
    };
  },
  created() {

    this.getDetail();

  },

  methods: {

    /*上传*/
    openUpload() {
      this.isupload = true;
    },

    getContent: function() {
      return this.$refs.ue.getUEContent();
    },

    /*获取图片*/
    returnImgsFunc(e) {
      let self = this;
      if (e != null) {
        this.form.image_id = e[0].file_id;
        this.form.image.file_path = e[0].file_path;
      }
      this.isupload = false;
    },

    getDetail() {
      let self = this;
      // 取到路由带过来的参数
      const params = self.$route.query.article_id;
      ArticleApi.toEditArticle({article_id: params},true).then(res => {
          self.ueditor.text = res.data.model.article_content;
          self.form = res.data.model;
          if(!self.form.image){
            self.form.image={};
          }
          self.category = res.data.catgory;
          self.loading = false;
        })
        .catch(error => {});
    },

    /*修改文章*/
    onSubmit() {
      let self = this;
      let params = this.form;
      self.form.article_content = this.$refs.ue.getUEContent();
      // 取到路由带过来的参数
      ArticleApi.editArticle(params, true)
        .then(data => {
          self.$message({
            message: data.msg,
            type: 'success'
          });
          self.$router.push('/operate/article/index');
        })
        .catch(error => {});
    },

    /*取消添加，返回文章列表*/
    cancelFunc() {
      this.$router.push({
        path: '/operate/article/index'
      });
    }
  }
};
</script>

<style>
.edit_container {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

.ql-editor {
  height: 400px;
}
</style>
