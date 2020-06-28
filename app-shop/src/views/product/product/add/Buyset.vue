<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品添加-高级设置
    -->
    <div class="buy-set-content pl16 pr16">

      <!--其他设置-->
      <div class="common-form mt50">其他设置</div>
      <el-form-item label="运费模板：" :rules="[{required: true,message: ' '}]" prop="model.delivery_id">
        <el-select v-model="form.model.delivery_id">
            <el-option
              v-for="item in form.delivery"
              :value="item.delivery_id"
              :key="item.delivery_id"
              :label="item.name"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="商品状态：">
        <el-radio-group v-model="form.model.product_status">
          <el-radio :label="10">上架</el-radio>
          <el-radio :label="20">下架</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="初始销量：">
        <el-input v-model="form.model.sales_initial" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="商品排序：" :rules="[{required: true,message: ' '}]" prop="model.product_sort">
        <el-input v-model="form.model.product_sort" class="max-w460"></el-input>
      </el-form-item>

    </div>
</template>

<script>
  export default {
      data() {
        return {
          unit: '%'
        }
      },
      props:['form'],
      methods: {
        changeMoneyType: function(val){
          if(val == '10'){
            this.unit = '%';
          }else{
            this.unit = '元';
          }
        },
      }
    }
</script>

<style>
</style>
