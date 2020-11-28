<template>
  <!--
      作者：luoyiming
      时间：2020-06-01
      描述：插件中心-文章-添加
    -->
  <div class="product-add pb50">
   <!--添加文章-->
    <el-form size="small" :model="form" ref="form" :rules="rules" label-width="100px">
      <div class="common-form">添加文章</div>
      <el-form-item label="文章标题" prop="article_title"><el-input v-model="form.article_title" placeholder="请输入文章标题" class="max-w460"></el-input></el-form-item>
      <el-form-item label="描述" prop="dec"><el-input v-model="form.dec" placeholder="请输入文章描述" class="max-w460"></el-input></el-form-item>
      <el-form-item label="文章缩略图">
        <div>
          <el-button type="primary" @click="openUpload">上传图片</el-button>
          <img :src="path" class="mt10" width="120px;" v-if="isImg" :isImg="isImg" />
          <!--上传图片组件-->
          <Upload v-if="isupload" :isupload="isupload" @returnImgs="returnImgsFunc">上传图片</Upload>
        </div>
      </el-form-item>
      <el-form-item label="文章分类">
        <el-select v-model="form.category_id" placeholder="请选择">
          <el-option v-for="(item, index) in catgory" :key="index" :label="item.name" :value="item.category_id"></el-option>
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
          <Uediter v-model="form.article_content" :text="ueditor.text" :config="ueditor.config" ref="ue"></Uediter>
        </div>
      </el-form-item>
      <el-form-item label="排序"><el-input type="number" v-model="form.article_sort" placeholder="请输入数字" class="max-w460"></el-input></el-form-item>
      <!--提交-->
      <div class="common-button-wrapper">
        <el-button size="small" type="info" @click="cancelFunc">取消</el-button>
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
      /*是否上传图片*/
      isupload: false,
      isImg: false,
      /*富文本配置*/
      ueditor: {
        text: '',
        config: {
          initialFrameWidth: 400,
          initialFrameHeight: 500
        }
      },
      path: '',
      /*form表单数据*/
      form: {
        article_title: '',
        category_id: '',
        image_id: '',
        article_sort: 1,
        article_status: 1,
        virtual_views: 1,
        article_content: '',
        dec: ''
      },
      /*文章类别*/
      catgory: [],
      loading: false,
      /*验证规则*/
      rules: {
        article_title: [{ required: true, message: '请输入文章标题', trigger: 'blur' }],
        dec: [{ required: true, message: '请输入文章描述', trigger: 'blur' }]
      }
    };
  },
  created() {
    /*获取列表*/
    this.getCategoryList();
  },
  methods: {
    /*上传*/
    openUpload() {
      this.isupload = true;
    },

    /*获取图片*/
    returnImgsFunc(e) {
      let self = this;
      if (e != null) {
        this.form.image_id = e[0].file_id;
        this.path = e[0].file_path;
        this.isImg = true;
      }
      this.isupload = false;
    },

    /*获取文章类别*/
    getCategoryList() {
      let self = this;
      let Params = {};

      ArticleApi.toAddArticle(Params, true)
        .then(res => {
          self.catgory = res.data.catgory;
          if(self.catgory.length > 0){
            self.form.category_id = self.catgory[0].category_id;
          }
        })
        .catch(error => {

        });
    },

    /*添加文章*/
    onSubmit() {
      let self = this;
      let form = self.form;
      self.form.article_content = this.$refs.ue.getUEContent();
      self.$refs.form.validate(valid => {
        if (valid) {
          self.loading = true;
          ArticleApi.addArticle(form, true)
            .then(data => {
              self.loading = false;
              self.$message({
                message: data.msg,
                type: 'success'
              });
              self.$router.push('/operate/article/index');
            })
            .catch(error => {
              self.loading = false;
            });
        }
      });
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
