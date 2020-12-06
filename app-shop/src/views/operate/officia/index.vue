<template>
  <!--
          作者：luoyiming
          时间：2019-10-24
          描述：插件中心-设置关注公众号
      -->
  <div class="attention pb50">
    <div class="attention-left"><img :src="mobile_url" alt="mobile" /></div>
    <div class="attention-right">
      <div class="alert-warning">
        <div class="alert-icon"><Icon :iconname="'#icon-gantanhao'"></Icon></div>
        <span class="alert-desc">
          <div>
            1.使用该功能前，需前往小程序后台，在“设置”-&gt;“接口设置”-&gt;“公众号关注组件”中设置要展示的公众号。注：设置的公众号需与小程序主体一致。
            <a href="https://mp.weixin.qq.com" target="_blank">去配置</a>
          </div>
          <div>2.若没有配置公众号，功能将不生效。</div>
          <div class="attention-tips">
            3.在一个小程序的生命周期内，只有从以下场景进入小程序，才具有展示引导关注公众号的能力:
            <p>a）当小程序从扫小程序码打开时。</p>
            <p>b）当从其他小程序返回小程序时，若小程序之前未被销毁，则该组件保持上一次打开小程序时的状态。</p>
          </div>
        </span>
      </div>
      <el-form ref="form" size="small" :model="form" :rules="rules" label-width="100px">
        <el-form-item label="公众号关注" prop="status"><el-switch v-model="form.status"></el-switch></el-form-item>
        <!--提交-->
        <div class="common-button-wrapper"><el-button type="primary" size="small" @click="onSubmit" :loading="loading">提交</el-button></div>
      </el-form>
    </div>
  </div>
</template>

<script>
import mobile from '@/assets/img/mobile_gzh.jpg';
import OfficiaApi from '@/api/officia.js';
export default {
  data() {
    return {
      /*手机图像*/
      mobile_url: mobile,
      form: {
        status: false
      },
      Data: [],
      loading: false,
      rules: {
        status: [{ required: true, message: '请输入活动名称', trigger: 'blur' }]
      }
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
      OfficiaApi.getData(Params, true)
        .then(data => {
          self.loading = false;
          if (data.data.vars.values.status == '1') {
            self.form.status = true;
          } else {
            self.form.status = false;
          }
          console.log(self.form);
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /**
     * 保存数据
     */
    onSubmit() {
      let self = this;
      let params = {};
      if (self.form.status) {
        params.status = 1;
      } else {
        params.status = 0;
      }
      self.loading = true;
      OfficiaApi.saveData(params, true)
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
  top: 20px;
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
