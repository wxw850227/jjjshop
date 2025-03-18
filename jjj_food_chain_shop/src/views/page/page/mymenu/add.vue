<template>
  <!--
      作者：luoyiming
      时间：2020-06-01
      描述：广告-添加
    -->
  <div class="product-add pb50">
    <!--添加广告-->
    <el-form size="small" :model="form" ref="form" :rules="rules" label-width="100px">
      <div class="common-form">添加菜单</div>
      <el-form-item label="菜单名称" prop="title">
        <el-input v-model="form.title" placeholder="请输入菜单名称" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="图标" prop="image_url">
        <div>
          <el-button type="primary" @click="openUpload">上传图片</el-button>
          <img :src="form.image_url" class="mt10" :width="50" :height="50" v-if="isImg" :isImg="isImg" />
          <div style="color: red;">图标大小为:25*25</div>
          <!--上传图片组件-->
          <Upload v-if="isupload" :isupload="isupload" @returnImgs="returnImgsFunc">上传图片</Upload>
        </div>
      </el-form-item>
      <el-form-item label="状态">
        <el-radio-group v-model="form.status">
          <el-radio :label="1">显示</el-radio>
          <el-radio :label="0">隐藏</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="排序">
        <el-input type="number" v-model="form.sort" placeholder="请输入数字" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="链接" prop="link_url">
        <el-row>
          <div class="url-box flex-1">
            <el-input v-model="form.link_url" type="text" class="max-w460" style="width: 380px;"></el-input>
              <span></span>
            <el-button type="primary" @click="changeLink()">选择链接
            </el-button>
            <div class="tips" id="tips" style="color: #000;">{{tips_Id}}</div>
            <el-input v-model="form.name" type="hidden" class="max-w460" style="width: 380px;"></el-input>
          </div>
          <div class="url-box ml10" style="float: right;">

          </div>
        </el-row>
      </el-form-item>

      <Setlink v-if="is_linkset" :is_linkset="is_linkset" @closeDialog="closeLinkset">选择链接</Setlink>
      <!--提交-->
      <div class="common-button-wrapper">
        <el-button size="small" type="info" @click="cancelFunc">取消</el-button>
        <el-button size="small" type="primary" @click="onSubmit" :loading="loading">提交</el-button>

      </div>
    </el-form>
  </div>
</template>

<script>
  import PageApi from '@/api/page.js';
  import Upload from '@/components/file/Upload.vue';
  import Setlink from '@/components/setlink/Setlink.vue';
  export default {
    components: {
      /*图片上传*/
      Upload: Upload,
      Setlink
    },
    data() {
      return {
        /*是否上传图片*/
        isupload: false,
        is_linkset: false,
        isImg: false,
        path: '',
        tips_Id: '',
        /*form表单数据*/
        form: {
          title: '',
          image_url: '',
          sort: 1,
          status: 1,
          link_url: '',
          name: '',
          sys_tag:''
        },
        loading: false,
        /*验证规则*/
        rules: {
          title: [{
            required: true,
            message: '请输入菜单名称',
            trigger: 'blur'
          }],
          image_url: [{
            required: true,
            message: '请上传图标',
            trigger: 'blur'
          }],
          link_url: [{
            required: true,
            message: '请选择链接地址',
            trigger: 'blur'
          }],

        }
      };
    },
    created() {},
    methods: {
      /*选择链接*/
      changeLink() {
        this.is_linkset = true;
      },

      /*获取链接并关闭弹窗*/
      closeLinkset(e) {
        //console.log(e);
        this.is_linkset = false;
        if (e != null) {
          this.form.link_url = e.url;
          this.form.name = '链接到' + ' ' + e.type + ' ' + e.name;
          this.form.sys_tag =  e.sys_tag || '';
          this.tips_Id = '链接到' + ' ' + e.type + ' ' + e.name;
        }
      },
      /*上传*/
      openUpload() {
        this.isupload = true;
      },

      /*获取图片*/
      returnImgsFunc(e) {
        let self = this;
        if (e != null) {
          this.form.image_url = e[0].file_path;
          this.path = e[0].file_path;
          this.isImg = true;
        }
        this.isupload = false;
      },

      /*添加广告*/
      onSubmit() {
        let self = this;
        let form = self.form;
        self.$refs.form.validate(valid => {
          if (valid) {
            self.loading = true;
            PageApi.addMenu(form, true)
              .then(data => {
                self.loading = false;
                ElMessage({
                  message: data.msg,
                  type: 'success'
                });
                self.$router.push('/page/page/mymenu/index');
              })
              .catch(error => {
                self.loading = false;
              });
          }
        });
      },

      /*取消添加，返回广告列表*/
      cancelFunc() {
        this.$router.push({
          path: '/page/page/mymenu/index'
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
