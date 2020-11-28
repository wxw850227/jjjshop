<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品添加
    -->
  <div class="product-add" v-loading="loading">

    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="180px">
      <!--基础信息-->
      <Basic v-if="!loading" :form="form" ref="basic"></Basic>

      <!--高级设置-->
      <Buyset v-if="!loading" :form="form"></Buyset>

      <!--提交-->
      <div class="common-button-wrapper">
        <el-button type="primary" size="small" @click="onSubmit" :loading="save_loading">发布</el-button>
      </div>

    </el-form>
  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  import Basic from './part/Basic.vue';
  import Buyset from './part/Buyset.vue';
  import {
    deepMerger
  } from '@/utils/base.js';
  export default {
    components: {
      /*基础信息*/
      Basic,
      /*高级设置*/
      Buyset
    },
    data() {
      return {
        product_id: 0,
        scene: 'edit',
        /*切换菜单*/
        activeIndex: '1',
        loading: true,
        save_loading: false,
        /*form表单数据*/
        form: {
          model: {
            spec_type: 10,
            deduct_stock_type: 20,
            product_status: 10,
            sales_initial: '0',
            product_sort: 100,
            is_points_gift: 1,
            is_points_discount: 1,
            is_enable_grade: 1,
            is_ind_agent: 0,
            agent_money_type: '10',
            is_alone_grade: 0,
            sku: {},
            spec_many: {
              spec_attr: [],
              spec_list: []
            },
            content: '',
            image: [],
            category_id: '',
            delivery_id: ''
          },
          catgory: [],
          delivery: [],
          gradeList: [],
          specData: {},
          isSpecLocked: false
        },
      };
    },
    created() {

      /*获取列表*/
      this.product_id = this.$route.query.product_id;
      this.scene = this.$route.query.scene;
      this.getData();
    },
    methods: {
      /*切换菜单*/
      handleSelect(key, keyPath) {
        this.activeIndex = key;
      },

      /**
       * 获取基础数据
       */
      getData: function() {
        let self = this;
        PorductApi.getEditData({
            product_id: self.product_id,
            scene: self.scene
          }, true).then(data => {
            self.loading = false;
            Object.assign(self.form, data.data);
            if (data.data.specData) {
              self.$set(self.form.model, 'spec_many', data.data.specData);
            } else {
              self.$set(self.form.model, 'spec_many', {
                spec_attr: [],
                spec_list: []
              });
              self.form.model.sku = data.data.model.sku[0];
            }
            self.form.model.product_status = data.data.model.product_status.value;
          })
          .catch(error => {
            self.loading = false;
          });
      },
      /*提交*/
      onSubmit: function() {
        let self = this;
        self.form.model.content = self.$refs.basic.getContent();
        let params = self.form.model;
        params.scene = self.scene;
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.save_loading = true;
            PorductApi.editProduct({
              product_id: self.product_id,
              scene: self.scene,
              params: JSON.stringify(params)
            }, true).then(data => {
              self.save_loading = false;
              self.$message({
                message: '保存成功',
                type: 'success'
              });
              self.$router.push('/product/product/index');
            }).catch(error => {
              self.save_loading = false;
            });
          }
        });
      },

      /*保存为草稿*/
      Draft() {
        let self = this;
        self.form.model.product_status = 30;
        self.onSubmit();
      }
    }
  };
</script>

<style lang="scss" scoped>
  .basic-setting-content {}

  .product-add {
    padding-bottom: 50px;
  }
</style>
