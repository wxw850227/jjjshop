<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品编辑-规格/库存
    -->
  <div>
    <!--规格设置-->
    <div class="common-form mt50">商品加料</div>
    <!--多规格-->
    <div>
      <div class="mt16">
        <!-- <el-form-item label="属性明细："> -->
        <div class="p-0-30 mb18">
          <div class="d-s-c ">商品加料: <div class="blue ml30" @click="addIngredients">添加加料+</div>
          </div>
        </div>

        <!--多规格表格-->
        <div class="p-0-30 mb18" v-for="(item,index) in form.model.product_feed" :key="index"
          v-if="form.model.product_feed.length>0">
          <div class="d-c-c mb16">
            <div style="width: 100px;"><span class="red">*</span>加料名称：</div>
            <div class="flex-1"><span class="red">*</span>价格</div>
          </div>
          <div class="d-s-c">
            <div style="width: 100px;">
              <el-autocomplete class="inline-input" v-model="item.feed_name" placeholder="如:杯型"
                :fetch-suggestions="querySearch"></el-autocomplete>
            </div>
            <div class="d-s-c ml20">
              <el-input type="number" style="width: 100px;" size="medium" v-model="item.price" placeholder="">
              </el-input> <span class="ml10">元</span>
            </div>
          </div>
        </div>
        <!-- </el-form-item> -->
      </div>
    </div>

  </div>
</template>

<script>
  export default {
    data() {
      return {
        restaurants: [],
        formData: {
          feed: []
        },
      }
    },
    inject: {
      form: {
        default: () => {}
      },
    },
    methods: {
      addIngredients() {
        this.form.model.product_feed.push({
          feed_name: '',
          price: ''
        })
      },
      querySearch(queryString, cb) {
        let self = this;
        if (self.restaurants.length == 0) {
          self.form.feed.forEach((item, index) => {
            self.restaurants.push({
              value: item.feed_name
            })
          })
        }

        var restaurants = self.restaurants;
        var results = queryString ? restaurants.filter(self.createFilter(queryString)) : restaurants;
        // 调用 callback 返回建议列表的数据
        cb(results);
      },
      createFilter(queryString) {
        return (restaurant) => {
          return (restaurant.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },
    }
  };
</script>

<style>

</style>
