<template>
  <!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：设置-文件上传设置
    -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="200px">
      <!--文件上传设置-->
      <div class="common-form">文件上传设置</div>

      <el-form-item label="默认上传方式">
        <div>
          <el-radio v-model="form.radio" label="local" @change="getRadio">本地 (不推荐)</el-radio>
          <el-radio v-model="form.radio" label="qiniu" @change="getRadio">七牛云存储</el-radio>
          <el-radio v-model="form.radio" label="aliyun" @change="getRadio">阿里云OSS</el-radio>
          <el-radio v-model="form.radio" label="qcloud" @change="getRadio">腾讯云COS</el-radio>
        </div>
      </el-form-item>
      <!--七牛云存储-->
      <div v-if="form.radio == 'qiniu'">
        <el-form-item label="存储空间名称 Bucket" :rules="[{ required: true, message: '请输入存储空间名称 Bucket' }]" prop="engine.qiniu.bucket">
          <el-input v-model="form.engine.qiniu.bucket" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="ACCESS_KEY AK" :rules="[{ required: true, message: '请输入ACCESS_KEY AK' }]" prop="engine.qiniu.access_key">
          <el-input v-model="form.engine.qiniu.access_key" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="SECRET_KEY SK" :rules="[{ required: true, message: '请输入SECRET_KEY SK' }]" prop="engine.qiniu.secret_key">
          <el-input v-model="form.engine.qiniu.secret_key" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="空间域名 Domain" :rules="[{ required: true, message: '请输入空间域名 Domain' }]" prop="engine.qiniu.domain">
          <el-input v-model="form.engine.qiniu.domain" class="max-w460"></el-input>
          <div class="tips">请补全http:// 或 https://，例如：http://static.cloud.com</div>
        </el-form-item>
      </div>
      <!--阿里云OSS-->
      <div v-if="form.radio == 'aliyun'">
        <el-form-item label="存储空间名称 Bucket" :rules="[{ required: true, message: '请输入存储空间名称 Bucket' }]" prop="engine.aliyun.bucket">
          <el-input v-model="form.engine.aliyun.bucket" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="AccessKeyId" :rules="[{ required: true, message: '请输入AccessKeyId' }]" prop="engine.aliyun.access_key_id">
          <el-input v-model="form.engine.aliyun.access_key_id" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="AccessKeySecret" :rules="[{ required: true, message: '请输入AccessKeySecret' }]" prop="engine.aliyun.access_key_secret">
          <el-input v-model="form.engine.aliyun.access_key_secret" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="空间域名 Domain" :rules="[{ required: true, message: '请输入空间域名 Domain' }]" prop="engine.aliyun.domain">
          <el-input v-model="form.engine.aliyun.domain" class="max-w460"></el-input>
          <div class="tips">请补全http:// 或 https://，例如：http://static.cloud.com</div>
        </el-form-item>
      </div>
      <!--腾讯云COS-->
      <div v-if="form.radio == 'qcloud'">
        <el-form-item label="存储空间名称 Bucket" :rules="[{required: true,message: '请输入存储空间名称 Bucket'}]" prop="engine.qcloud.bucket">
          <el-input v-model="form.engine.qcloud.bucket" class="max-w460"></el-input>
         </el-form-item>
        <el-form-item label="所属地域 Region" :rules="[{required: true,message: '请输入所属地域 Region'}]" prop="engine.qcloud.region">
          <el-input v-model="form.engine.qcloud.region" class="max-w460"></el-input>
          <div class="tips">请填写地域简称，例如：ap-beijing、ap-hongkong、eu-frankfurt</div>
        </el-form-item>
        <el-form-item label="SecretId" :rules="[{required: true,message: '请输入SecretId'}]" prop="engine.qcloud.secret_id">
          <el-input v-model="form.engine.qcloud.secret_id" class="max-w460"></el-input>
         </el-form-item>
        <el-form-item label="SecretKey" :rules="[{required: true,message: '请输入SecretKey'}]" prop="engine.qcloud.secret_key">
          <el-input v-model="form.engine.qcloud.secret_key" class="max-w460"></el-input>
        </el-form-item>
        <el-form-item label="空间域名 Domain" :rules="[{required: true,message: '请输入空间域名 Domain'}]" prop="engine.qcloud.domain">
          <el-input v-model="form.engine.qcloud.domain" class="max-w460"></el-input>
          <div class="tips">请补全http:// 或 https://，例如：http://static.cloud.com</div>
        </el-form-item>
      </div>

      <!--提交-->
      <div class="common-button-wrapper"><el-button size="small" type="primary" @click="onSubmit" :loading="loading">提交</el-button></div>
    </el-form>
  </div>
</template>

<script>
import SettingApi from '@/api/setting.js';

export default {
  data() {
    return {
      /*切换菜单*/
      // activeIndex: '1',
      /*form表单数据*/
      form: {
        radio: 'local',
        engine: {
          qiniu: {
            // domain: '',
            // access_key: '',
            // secret_key: '',
            // bucket: '',
          },
          aliyun: {
            // domain: '',
            // access_key_id: '',
            // access_key_secret: '',
            // bucket: '',
          },
          qcloud: {
            // domain: '',
            // region: '',
            // secret_id: '',
            // secret_key: '',
            // bucket: '',
          }
        }
      },
      loading: false
    };
  },
  created() {
    this.getData();
  },

  methods: {
    getData() {
      let self = this;
      SettingApi.storageDetail({}, true)
        .then(data => {
          let vars = data.data.vars.values;
          self.form.radio = vars.default;
          self.form.engine.qiniu = vars.engine.qiniu;
          self.form.engine.aliyun = vars.engine.aliyun;
          self.form.engine.qcloud = vars.engine.qcloud;
        })
        .catch(error => {});
    },
    //提交表单
    onSubmit() {
      let self = this;
      self.$refs.form.validate((valid) => {
        if (valid) {
          self.loading = true;
          let params = this.form;
          SettingApi.editStorage(params, true)
            .then(data => {
              self.loading = false;
              self.$message({
                message: '恭喜你，上传设置成功',
                type: 'success'
              });
              // self.$router.push('/setting/Storage');
            })
            .catch(error => {
              self.loading = false;
            });
        }
     });

    },
    //监听单选按钮
    getRadio(val) {}
  }
};
</script>

<style>
.tips {
  color: #ccc;
}
</style>
