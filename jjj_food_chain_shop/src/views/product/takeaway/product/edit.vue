<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品添加
    -->
  <div class="product-add" v-loading="loading">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="180px" v-if="!loading">
      <!--基础信息-->
      <Basic></Basic>
      <!--规格设置-->
      <Spec></Spec>
      <!-- 属性设置-->
      <Attr></Attr>
      <!-- 加料设置-->
      <Ingredients></Ingredients>
      <!--商品详情-->
      <Content></Content>
      <!--高级设置-->
      <Buyset></Buyset>
      <!--提交-->
      <div class="common-button-wrapper">
        <el-button size="small" type="info" @click="cancelFunc">取消</el-button>
        <el-button size="small" type="primary" @click="onSubmit" :loading="loading">修改</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  import Basic from './part/Basic.vue';
  import Attr from './part/Attr.vue';
  import Ingredients from './part/Ingredients.vue';
  import Spec from './part/Spec.vue';
  import Content from './part/Content.vue';
  import Buyset from './part/Buyset.vue';
  import {
    formatModel
  } from '@/utils/base.js';
  export default {

    components: {
      /*基础信息*/
      Basic,
      /*规格信息*/
      Spec,
      /* 属性信息*/
      Attr,
      /*加料设置*/
      Ingredients,
      /*商品详情*/
      Content,
      /*高级设置*/
      Buyset
    },
    data() {

      return {
        /* 审核状态*/
        old_audit: 20,
        /*商品ID*/
        product_id: 0,
        /*判断是编辑*/
        scene: 'edit',
        /*是否正在加载*/
        loading: true,
        /*是否正在提交保存*/
        save_loading: false,
        /*form表单数据*/
        form: {
          model: {},
          /*商品分类*/
          category: [],
          /*会员等级*/
          gradeList: [],
          feed: [],
          special: [],
          /*规格数据*/
          specData: null,
          /*是否锁住*/
          isSpecLocked: false,
          /*分销基础设置*/
          basicSetting: {},
          /*分销佣金设置*/
          agentSetting: {}
        },
        /*模型数据*/
        model: {
          /*商品名称*/
          product_name: '',
          /*商品分类*/
          category_id: null,
          /*商品图片*/
          image: [],
          /*商品卖点*/
          selling_point: '',
          /*规格类别,默认10单规格，20多规格*/
          spec_type: 10,
          /*库存计算方式,默认20付款减库存，10下单减库存*/
          deduct_stock_type: 20,
          /*单规格*/
          sku: [{
            product_price: '',
            stock_num: '',
            product_weight: '',
            cost_price: ''
          }],
          product_attr: [],
          product_feed: [],
          /* 最小购买量 */
          min_buy: 1,
          /* 商品单位 */
          product_unit: '',
          /*商品详情内容*/
          content: '',
          /*商品状态*/
          product_status: 10,
          /*初始销量*/
          sales_initial: 0,
          /*商品排序，默认100*/
          product_sort: 100,
          /*限购数量*/
          limit_num: 0,
          special_id: 0,
        }
      };
    },
    provide: function() {
      return {
        form: this.form
      };
    },
    created() {
      /*获取列表*/
      this.product_id = this.$route.query.product_id;
      this.scene = this.$route.query.scene;
      this.getData();
    },
    methods: {
      /**
       * 获取基础数据
       */
      getData: function() {
        let self = this;
        PorductApi.takeGetEditData({
              product_id: self.product_id,
              scene: self.scene
            },
            true
          )
          .then(res => {
            self.loading = false;
            Object.assign(self.form, res.data);
            self.form.model.product_status = res.data.model.product_status.value;
            console.log(self.form)
          })
          .catch(error => {
            self.loading = false;
          });
      },

      /*提交*/
      onSubmit: function() {
        let self = this;
        self.$refs.form.validate(valid => {
          let params = formatModel(self.model, self.form.model);
          if (valid) {
            let params = formatModel(self.model, self.form.model);
            params.scene = self.scene;
            params.image = self.ImgKeepId(params.image);
            params.product_id = self.product_id;
            params.sku = self.form.model.sku;
            self.save_loading = true;
            PorductApi.takeEditProduct({
                product_id: self.product_id,
                params: JSON.stringify(params)
              }, true)
              .then(data => {
                self.save_loading = false;
                ElMessage({
                  message: '保存成功',
                  type: 'success'
                });
                self.cancelFunc();
              })
              .catch(error => {
                self.save_loading = false;
              });
          }
        });
      },

      /*图片数值只保留id*/
      ImgKeepId(list) {
        let arr = [];
        for (let i = 0, length = list.length; i < length; i++) {
          let obj = {
            image_id: list[i].image_id,
            file_id: list[i].file_id
          };
          arr.push(obj);
        }
        return arr;
      },

      /*取消*/
      cancelFunc() {
        this.$router.back(-1);
      }
    }
  };
</script>

<style lang="scss" scoped>
  .basic-setting-content {}

  .product-add {
    padding-bottom: 50px;
  }

  .mb50 {
    margin-bottom: 50px;
  }
</style>
