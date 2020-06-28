<template>
<!--
      作者 WuYuseng
      时间：2019-10-26
      描述：设置-物流公司-新增物流公司
  -->
  <div class="product-add">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="200px">
      <!--添加门店-->
      <div class="common-form">新增物流公司</div>
      <el-form-item label="物流公司名称 " :rules="[{required: true,message: '请输入物流公司名称'}]" prop="express_name">
        <el-input v-model="form.express_name" class="max-w460"></el-input>
        <div class="tips">请对照
          <el-link target="_blank" type="primary" :underline="false" @click="Company()">
            物流公司编码表
          </el-link>
          填写
        </div>
      </el-form-item>
      <el-form-item label="物流公司代码 " :rules="[{required: true,message: '请输入物流公司代码'}]" prop="express_code">
        <el-input v-model="form.express_code" class="max-w460"></el-input>
        <div class="tips">用于快递100API查询物流信息，务必填写正确</div>
      </el-form-item>
      <el-form-item label="排序" :rules="[{required: true,message: '请输入排序'}]" prop="sort">
        <el-input v-model="form.sort" type="number" class="max-w460"></el-input>
        <div class="tips">数字越小越靠前</div>
      </el-form-item>

      <!--提交-->
      <div class="common-button-wrapper">
        <el-button type="primary" @click="onSubmit" :loading="loading">提交</el-button>
      </div>


    </el-form>


  </div>

</template>

<script>
  import SettingApi from '@/api/setting.js';
  export default {
    data() {
      return {
        loading: false,
        /*form表单数据*/
        form: {
          express_name: '',
          express_code: '',
          sort: 1,
        },


      };
    },
    created() {},

    methods: {
      //提交表单
      onSubmit() {
        let self = this;
        let form = this.form;
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.loading = true;
            SettingApi.addExpress(form, true)
              .then(data => {
                self.loading = false;
                self.$message({
                  message: '恭喜你，添加成功',
                  type: 'success'
                });
                self.$router.push('/setting/express/index');
              })
              .catch(error => {
                self.loading = false;
              });
          }
        });
      },
      Company() {
        let self = this;
        self.$router.push('/setting/express/Company');
      }

    }

  };
</script>

<style>
  .tips {
    color: #ccc;
  }
</style>
