<template>
  <div class="attention">
    <div class="attention-left"><img :src="mobile_url" alt="" /></div>
    <div class="attention-right">
      <div class="alert-warning">
        <div class="alert-icon"><Icon :iconname="'#icon-gantanhao'"></Icon></div>
        <span class="alert-desc"><div>1.新用户首次进入小程序的时候才会显示如图所示的弹窗提示，只显示一次！</div></span>
      </div>
      <el-form ref="form" size="small" :model="form" label-width="100px">
        <el-form-item label="引导收藏" prop="status"><el-switch v-model="form.status"></el-switch></el-form-item>
        <!--提交-->
        <div class="common-button-wrapper"><el-button type="primary" @click="onSubmit" :loading="loading">提交</el-button></div>
      </el-form>
    </div>
  </div>
</template>

<script>
import mobile from '@/assets/img/guidcollection.png';
import CollectionApi from '@/api/collection.js';
export default {
  data() {
    return {
      /*手机图像*/
      mobile_url: mobile,
      form: {
        status: 0
      },
      Data: [],
      loading: false
    };
  },
  created() {
    /*获取数据*/
    this.getData();
  },
  methods: {
    /*获取当前数据*/
    getData() {
      let self = this;
      let Params = {};
      CollectionApi.getData(Params, true)
        .then(data => {
          self.loading = false;
          if(data.data.vars.values.status=='1'){
            self.form.status = true;
          }else{
            self.form.status = false;
          }
          self.form.app_id = data.data.app_id;
        })
        .catch(error => {});
    },
    onSubmit() {
      let self = this;
      let params={};
      if(self.form.status){
        params.status=1;
      }else{
        params.status=0;
      }
      self.loading = true;
      CollectionApi.saveData(params, true)
        .then(data => {
          self.loading = false;
          if (data.code == 1) {
            self.$message({
              message: '恭喜你，保存成功',
              type: 'success'
            });
            self.getData();
          } else {
            self.loading = false;
          }
        })
        .catch(error => {
          self.loading = false;
        });
    }
  }
};
</script>

<style>
.attention {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}
.attention-left {
  width: 375px;
  margin-right: 30px;
}
.attention-right .alert-warning {
  position: relative;
  padding: 16px 16px 16px 69px;
  border-radius: 6px;
  margin-bottom: 10px;
  color: #495060;
  line-height: 1.5;
  border: 1px solid #ffebcc;
  background-color: #fff5e6;
}
.attention-right .alert-warning .alert-icon {
  position: absolute;
  top: 10px;
  left: 24px;
}
.attention-right .alert-warning .alert-icon .svg-icon {
  color: #f90;
}
.attention .attention-left img {
  width: 100%;
  border: 1px solid #eeeeee;
}
</style>
