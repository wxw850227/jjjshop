<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 商品
    -->
  <div>
    <div class="common-form">
      <span>{{ curItem.name }}</span>
    </div>
    <el-form size="small" :model="curItem" label-width="100px">
      <el-form-item label="商品来源：">
        <el-radio-group v-model="curItem.params.source">
          <el-radio :label="'auto'">自动获取</el-radio>
          <el-radio :label="'choice'">手动选择</el-radio>
        </el-radio-group>
      </el-form-item>
      <!-- 自动获取 -->
      <template v-if="curItem.params.source == 'auto'">
        <!-- 商品分类 -->
        <el-form-item label="商品分类：">
          <el-select v-model="curItem.params.auto.category" placeholder="请选择">
            <el-option :value="0" label="全部分类"></el-option>
            <template v-if="CategoryList.length>0">
              <block v-for="(item,index) in CategoryList" :key="index">
                <el-option :key="item.category_id" :label="item.name" :value="item.category_id"></el-option>
                <template v-if="item.child">
                  <block v-for="(two,index2) in item.child" :key="index2">
                    <el-option  :key="two.category_id" :label="two.name" :value="two.category_id">
                      　{{ two.name }}
                    </el-option>
                  </block>
                </template>
              </block>
            </template>
          </el-select>
        </el-form-item>
        <!-- 商品排序 -->
        <el-form-item label="商品排序：">
          <el-radio-group v-model="curItem.params.auto.productSort">
            <el-radio :label="'all'">综合</el-radio>
            <el-radio :label="'sales'">销量</el-radio>
            <el-radio :label="'price'">价格</el-radio>
          </el-radio-group>
        </el-form-item>
        <!-- 显示数量 -->
        <el-form-item label="显示数量：">
          <el-input v-model="curItem.params.auto.showNum" class="w-auto"></el-input>
        </el-form-item>
      </template>
      <!-- 手动选择 -->
      <template v-if="curItem.params.source == 'choice'">
        <el-form-item label="商品列表：">
          <div class="choice-product-list">
            <div :key="index" v-for="(product, index) in curItem.data" class="item">
              <div class="delete-box">
                <i class="el-icon-error" @click="$parent.onEditorDeleleData(index, selectedIndex)"></i>
              </div>
              <img v-img-url="product.image" alt="" />
            </div>
            <div class="item plus-btn" @click.stop="$parent.openProduct()">
              <i class="el-icon-plus"></i>
              <p>选择产品</p>
            </div>
          </div>
        </el-form-item>
      </template>
      <!--组件样式-->
      <div class="p-10-0 mb16 f14 border-b">
        <span class="gray6">组件样式</span>
      </div>

      <!-- 背景颜色 -->
      <el-form-item label="背景颜色：">
        <div class="d-s-c">
          <el-color-picker v-model="curItem.style.background"></el-color-picker>
          <el-button type="button" style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'btnColor', '#ffffff')">重置</el-button>
        </div>
      </el-form-item>
      <!-- 商品排序 -->
      <el-form-item label="商品排序：">
        <el-radio-group v-model="curItem.style.display">
          <el-radio :label="'list'">列表平铺</el-radio>
          <el-radio :label="'slide'" :disabled="curItem.style.column == 1">横向滑动</el-radio>
        </el-radio-group>
      </el-form-item>
      <!-- 商品排序 -->
      <el-form-item label="分列数量：">
        <el-radio-group v-model="curItem.style.column">
          <el-radio :label="1" :disabled="curItem.style.display == 'slide'">单列</el-radio>
          <el-radio :label="2">两列</el-radio>
          <el-radio :label="3">三列</el-radio>
        </el-radio-group>
      </el-form-item>
      <!-- 商品排序 -->
      <el-form-item label="显示内容：">
        <el-checkbox v-model="productNameShow" @change="checked=>check(checked, 'productName')">商品名称</el-checkbox>
        <el-checkbox v-model="productPriceShow" @change="checked=>check(checked, 'productPrice')">商品价格</el-checkbox>
        <el-checkbox v-model="linePriceShow" @change="checked=>check(checked, 'linePrice')">划线价格</el-checkbox>
        <el-checkbox v-model="sellingPointShow" @change="checked=>check(checked, 'sellingPoint')" v-show="curItem.style.column == 1">商品卖点</el-checkbox>
        <el-checkbox v-model="productSalesShow" @change="checked=>check(checked, 'productSales')" v-show="curItem.style.column == 1">商品销量</el-checkbox>
      </el-form-item>

    </el-form>
  </div>
</template>

<script>
  import ProductApi from '@/api/product.js';
  export default {
    data() {
      return {
        CategoryList: [],
        productNameShow: false,
        productPriceShow: false,
        linePriceShow: false,
        sellingPointShow: false,
        productSalesShow: false,
      };
    },
    props: ['curItem', 'selectedIndex', 'opts'],
    created() {
      /*获取列表*/
      this.getData();
      this.productNameShow  = this.curItem.style.show.productName == 1? true: false;
      this.productPriceShow  = this.curItem.style.show.productPrice == 1? true: false;
      this.linePriceShow  = this.curItem.style.show.linePrice == 1? true: false;
      this.sellingPointShow  = this.curItem.style.show.sellingPoint == 1? true: false;
      this.productSalesShow  = this.curItem.style.show.productSales == 1? true: false;
    },
    methods: {
      getData() {
        let self = this;
        ProductApi.catList({
            page_id: self.page_id
          }, true).then(data => {
            self.CategoryList = data.data.list;
          })
          .catch(error => {
            self.loading = false;
          });
      },
      check(checked, name){
        let value = checked ? 1: 0;
        this.curItem.style.show[name] = value;
      },
    }
  };
</script>

<style>
  .choice-product-list {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
  }

  .choice-product-list .item {
    position: relative;
    width: 80px;
    height: 80px;
    margin-right: 10px;
    margin-bottom: 10px;
    border: 1px solid #DDDDDD;
  }

  .choice-product-list .item .delete-box {
    position: absolute;
    width: 20px;
    height: 20px;
    top: -10px;
    right: -10px;
    font-size: 20px;
    cursor: pointer;
    color: #999999;
  }

  .choice-product-list .item .delete-box:hover {
    color: rgb(255, 51, 0);
  }

  .choice-product-list .item.plus-btn {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .choice-product-list .item.plus-btn>i {
    font-size: 30px;
    color: #CCCCCC;
  }

  .choice-product-list img {
    width: 100%;
    height: 100%;
  }
</style>
