<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品编辑-规格/库存-多规格-表格
    -->
  <div class="mt16">
    <el-form-item label="规格明细：" v-if="form.model.sku.length > 0">
      <!--多规格表格-->
      <div>
        <el-table size="mini" :data="form.model.sku" border style="width: 100%; margin-top: 20px">
          <el-table-column label="规格名称">
            <template #default="scope">
              <el-form-item label="" style="margin-bottom: 0;">
                <!-- <el-input size="small" prop="spec_name" v-model="scope.row.spec_name"></el-input> -->
                <el-autocomplete class="inline-input" v-model="scope.row.spec_name" :fetch-suggestions="querySearch"
                  placeholder="请输入内容"></el-autocomplete>
              </el-form-item>
            </template>
          </el-table-column>
          <el-table-column label="价格">
            <template #default="scope">
              <el-form-item label="" style="margin-bottom: 0;">
                <el-input type="number" size="small" prop="product_price" v-model="scope.row.product_price"></el-input>
              </el-form-item>
            </template>
          </el-table-column>
          <el-table-column label="包装费">
            <template #default="scope">
              <el-form-item label="" style="margin-bottom: 0;">
                <el-input type="number" size="small" prop="bag_price" v-model="scope.row.bag_price"></el-input>
              </el-form-item>
            </template>
          </el-table-column>
          <el-table-column label="库存">
            <template #default="scope">
              <el-form-item label="" style="margin-bottom: 0;">
                <el-input type="number" size="small" prop="stock_num" v-model="scope.row.stock_num"></el-input>
              </el-form-item>
            </template>
          </el-table-column>
<!--          <el-table-column label="成本价格">
            <template slot-scope="scope">
              <el-form-item label="" style="margin-bottom: 0;">
                <el-input type="number" size="small" prop="cost_price" v-model="scope.row.cost_price"></el-input>
              </el-form-item>
            </template>
          </el-table-column> -->
          <el-table-column label="">
            <template #default="scope">
              <el-form-item label="" style="margin-bottom: 0;">
                <el-button type="text" @click='deleteAttr(scope.$index)'>删除</el-button>
              </el-form-item>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-form-item>
  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  export default {
    components: {

    },
    data() {
      return {
        restaurants: [],
        formData: {},
        /*批量设置sku属性*/
        batchData: {
          product_price: '',
          line_price: '',
          stock_num: '',
          product_weight: ''
        },
        /*图片是否打开*/
        isupload: false,
        //上传图片选择的下标
        spec_index: -1
      };
    },
    inject: ['form'],
    created() {
      this.formData = this.form;
    },
    mounted() {
     

    },
    methods: {
      deleteAttr(i) {
        this.form.model.sku.splice(i, 1)
      },
      querySearch(queryString, cb) {
        let self = this;
        if (self.restaurants.length == 0) {
          self.formData.spec.forEach((item, index) => {
            self.restaurants.push({
              value: item.spec_name
            })
          })
        }
        var restaurants = this.restaurants;
        var results = queryString ? restaurants.filter(this.createFilter(queryString)) : restaurants;
        // 调用 callback 返回建议列表的数据
        cb(results);
      },
      createFilter(queryString) {
        return (restaurant) => {
          return (restaurant.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },
    }
  }
</script>

<style>
</style>
