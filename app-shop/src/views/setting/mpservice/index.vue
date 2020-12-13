<template>
  <!--
      作者 luoyiming
      时间：2019-10-26
      描述：设置-客服设置
  -->
  <div>
    <!--form表单-->
    <el-form size="small" ref="formData" :model="formData" label-width="150px">
      <!--客服设置-->
      <div class="common-form">客服设置</div>
      <el-form-item label="QQ" :rules="[{required: true,message: '请输入QQ'}]">
        <el-input type="number" v-model="formData.qq" placeholder="请输入QQ" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="微信号" :rules="[{required: true,message: '请输入微信号'}]">
        <el-input v-model="formData.wechat" placeholder="请输入微信号" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="公众号二维码" :rules="[{required: true,message: '请输入上传公众号二维码'}]">
        <el-button @click="chooseImg">选择图片</el-button>
        <img class="mt10" v-img-url="formData.mp_image" width="200">
      </el-form-item>

      <!--提交-->
      <div class="common-button-wrapper">
        <el-button size="small" type="primary" @click="onSubmit" :disabled="loading">提交</el-button>
      </div>
    </el-form>

    <!--上传图片-->
    <Upload v-if="isupload" :isupload="isupload" :config="{ total: 3 }" @returnImgs="returnImgsFunc"></Upload>
  </div>
</template>

<script>

  import SettingApi from '@/api/setting.js';
  import Upload from '@/components/file/Upload';
  export default{
    components:{
      Upload
    },
    data(){
      return {
        /*是否正在加载*/
        loading:true,
        /*表单数据对象*/
        formData:{
          qq:'',
          wechat:'',
          mp_image:''
        },
        /*是否打开图片选择*/
        isupload:false
      }
    },
    created() {
      this.getParams();
    },
    methods:{

      /*获取配置数据*/
      getParams() {
        let self = this;
        SettingApi.getMpService({}, true).then(res => {
            if (res.code == 1) {
              self.formData=res.data.vars.values;
              self.loading=false;
            } else {
              console.log();
            }
          })
          .catch(error => {

          });
      },

      /*提交*/
      onSubmit() {
        let self = this;
        let params = this.formData;
        self.$refs.formData.validate((valid) => {
          if (valid) {
            self.loading = true;
            SettingApi.setMpService(params, true)
              .then(data => {
                self.loading = false;
                self.$message({
                  message: '恭喜你， 客服设置成功',
                  type: 'success'
                });
              })
              .catch(error => {
                self.loading = false;
              });
          }
        });

      },

      /*选择图片*/
      chooseImg(){
        this.isupload=true;
      },

      /*关闭选择图片*/
      returnImgsFunc(e){
        this.isupload=false;
        this.formData.mp_image=e[0].file_path;
      }

    }
  }
</script>

<style>
</style>
