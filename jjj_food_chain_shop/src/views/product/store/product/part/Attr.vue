<template>
  <!--
    	描述：商品管理-商品编辑-规格/库存
    -->
  <div>
    <!--规格设置-->
    <div class="common-form mt50">商品属性</div>
    <!--多规格-->
    <div>
      <div class="mt16">
        <!-- <el-form-item label="属性明细："> -->
        <div class="p-0-30 mb18">
          <div class="d-s-c ">商品属性: <div class="blue ml30" @click="addAttr">添加属性+</div>
          </div>
        </div>

        <!--多规格表格-->
        <div class="mb18 p-0-30" v-for="(item,index) in form.model.product_attr" :key="index"
          v-if="form.model.product_attr.length>0">
          <div class="d-c-c mb16">
            <div style="width: 100px;"><span class="red">*</span>属性名称：</div>
            <div class="flex-1"><span class="red">*</span>属性值(至少填写两个)</div>
          </div>
          <div class="d-c-c">
            <div style="width: 100px;">
              <el-autocomplete class="inline-input" size="medium" v-model="item.attribute_name"
                :fetch-suggestions="querySearch" placeholder="如:温度"></el-autocomplete>
            </div>
            <div class="flex-1 d-s-c">
              <div v-for="(aitem,aindex) in 8" :key="aindex" class="ml20">
                <el-input style="width: 100px;" size="medium" v-model="item.attribute_value[aindex]" placeholder="请选择">
                </el-input>
              </div>
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
        formData: {},
      }
    },
    inject: ['form'],
    methods: {
      addAttr() {
        console.log(this.form.model.product_attr)
        this.form.model.product_attr.push({
          attribute_name: '',
          attribute_value: []
        })
      },
      querySearch(queryString, cb) {
        let self = this;
        if(self.restaurants.length==0){
          self.form.attribute.forEach((item, index) => {
            self.restaurants.push({
              value: item.attribute_name
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
  };
</script>

<style>

</style>
