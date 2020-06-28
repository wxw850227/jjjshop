<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 商品栏
    -->
  <div class="drag optional" :class="{ selected: index === selectedIndex }" @click.stop="$parent.$parent.onEditer(index)">
    <div class="diy-product" :style="{ background: item.style.background }">
      <div :class="['display__' + item.style.display]">
        <ul class="product-list" :class="['column__' + item.style.column]" :style="getUlwidth(item)">
          <li class="product-item" v-for="(product,index) in item.params.source == 'choice' ? item.data : item.defaultData" :key="index">
            <!-- 单列商品 -->
            <template v-if="item.style.column == 1">
              <div class="product-item-box">
                <!-- 商品图片 -->
                <div class="product-cover"><img v-img-url="product.image" /></div>
                <div class="product-info">
                  <!-- 商品名称 -->
                  <div v-if="item.style.show.productName" class="product-title">
                    <span>{{ product.product_name }}</span>
                  </div>
                  <!-- 商品卖点 -->
                  <div v-if="item.style.show.sellingPoint" class="selling-point gray9">
                    <span>{{ product.selling_point }}</span>
                  </div>
                  <!-- 商品销量 -->
                  <div v-if="item.style.show.productSales" class="already-sale">
                    <span>已售{{ product.product_sales }}件</span>
                  </div>
                  <!-- 商品价格 -->
                  <div class="price d-s-c">
                    <div v-if="item.style.show.productPrice" class="orange">
                      <span>¥</span>
                      <span class="f18 fb">{{ product.product_price }}</span>
                    </div>
                    <div class="ml10 gray9 text-d-line" v-if="item.style.show.linePrice && product.line_price > 0">
                      <span>¥</span>
                      <span>{{ product.line_price }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </template>

            <!-- 两列三列 -->
            <template v-else>
              <div class="product-cover"><img v-img-url="product.image" /></div>
              <div class="product-info p-0-10">
                <div v-if="item.style.show.productName" class="product-title">{{ product.product_name }}</div>
                <div class="price d-s-c f12">
                  <div v-if="item.style.show.productPrice" class="orange">
                    <span>¥</span>
                    <span class="">{{ product.product_price }}</span>
                  </div>
                  <div class="ml4 gray9 text-d-line" v-if="item.style.show.linePrice && product.line_price > 0">
                    ¥{{ product.line_price }}
                  </div>
                </div>
              </div>
            </template>
          </li>
        </ul>
      </div>
    </div>
    <div class="btn-edit-del">
      <div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div>
    </div>
  </div>
</template>

<script>
  import ProductApi from '@/api/product.js';
  export default {
    data() {
      return {
        /*商品列表*/
        tableData: {},
        /*分类id*/
        category_id: 0,
      };
    },
    created() {
      /*获取列表*/
      this.getData();
    },
    props: ['item', 'index', 'selectedIndex'],
    methods: {
      getData() {
        let self = this;
        ProductApi.productList({
            category_id: self.category_id
          }, true).then(data => {
            self.tableData = data.data.list.data;
          })
          .catch(error => {
            self.loading = false;
          });
      },

      /*计算宽度*/
      getUlwidth(item){
        if(item.style.display=='slide'){
           let total=0;
          if(item.params.source=='choice'){
             total=item.data.length;
          }else{
            total=item.defaultData.length;
          }
          let w=0;
          if(item.style.column==2){
            w=total*150;
          }else{
            w=total*100;
          }
           return 'width:'+w+'px;';
        }
      }
    }
  };
</script>

<style>
  .diy-product .product-list.column__1 .product-item {
    padding: 10px;
    border-bottom: 1px solid #dddddd;
    background: #FFFFFF;
  }

  .diy-product .product-list.column__1 .product-item-box {
    display: flex;
    justify-content: flex-start;
    align-items: stretch;
  }
  .diy-product .display__slide{
    overflow-x: auto;
  }
  .diy-product .display__slide .product-list{display: flex;
    justify-content: flex-start;}
  .diy-product .display__slide .product-list.column__2 .product-item{
    width: 150px;
  }
  .diy-product .display__slide .product-list.column__3 .product-item{
    width: 100px;
  }
.diy-product .display__slide .product-list .product-item{ margin-right: 10px;}
  .diy-product .product-list img {
    width: 100%;
  }

  .diy-product .product-list.column__1 .product-cover {
    width: 100px;
    height: 100px;
  }

  .diy-product .product-list.column__1 .product-info {
    margin-left: 10px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .diy-product .product-list .product-title {
    margin-top: 4px;
    height: 40px;
    line-height: 20px;
    display: -webkit-box;
    overflow: hidden;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;

  }

  .diy-product .display__list .column__2,
  .diy-product .display__list .column__3 {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .diy-product .product-list.column__2 .product-item {
    width:170px;
    margin-bottom: 10px;
    background: #FFFFFF;
  }
  .diy-product .product-list.column__2 .product-item .product-cover{
    width: 170px;
    height: 170px;
    overflow: hidden;
  }

  .diy-product .product-list.column__3 .product-item {
    width: 117px;
    margin-bottom: 10px;
    background: #FFFFFF;
  }
   .diy-product .product-list.column__3 .product-item .product-cover{
    width: 117px;
    height: 117px;
    overflow: hidden;
   }
   .diy-product .product-list.column__3 .product-item .product-cover img{
     width: 117px;
   }

  .diy-product .product-list.column__2 .product-title,
  .diy-product .product-list.column__3 .product-title {
    height: 40px; overflow: hidden;
  }
</style>
