<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品管理-商品编辑-规格/库存-多规格-类别
    -->
  <div class="spec-many-type">
    <!--规格属性-->
    <div class="spec-wrap">
      <!--添加规格-->
      <div v-if="!form.isSpecLocked">
        <el-button size="small" class="el-icon-circle-plus" @click="onToggleAddGroupForm">添加规格 </el-button>
      </div>
    </div>
  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  export default {
    data() {
      return {
        /*显示添加规格组按钮*/
        showAddGroupBtn: true,
        /*显示添加规格组表单*/
        showAddGroupForm: false,
        /*新增规格组值*/
        addGroupFrom: {
          specName: '',
          specValue: ''
        },
        groupLoading: false
      };
    },
    inject: ['form'],
    created() {
      /*获取列表*/

    },
    methods: {

      /*显示/隐藏添加规则组 */
      onToggleAddGroupForm: function() {
        let self = this;
        if(!Array.isArray(self.form.model.sku)){
          self.form.model.sku=[];
        }
       self.form.model.sku.push(
       {
         spec_name:'',
         product_price:'',
         bag_price:'',
         stock_num:'',
         cost_price:0,
         is_full:0
       })
       console.log(self.form.model.sku)
      },

      /*删除规格组事件*/
      onDeleteGroup: function(index) {
        var self = this;
       ElMessageBox.confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
            type: 'warning'
          })
          .then(() => {
            // 删除指定规则组
            self.form.model.spec_many.spec_attr.splice(index, 1);
            // 构建规格组合列表
            self.buildSkulist();
          });
      },

      /*删除规格值值事件*/
      onDeleteValue: function(index, itemIndex) {
        let self = this;

        if (self.form.isSpecLocked) {
          ElMessage({
            message: '本商品正在参加活动，不能删除规格！',
            type: 'warning'
          });
          return;
        }

        ElMessageBox.confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
            type: 'warning'
          })
          .then(() => {
            // 删除指定规则组
            self.form.model.spec_many.spec_attr[index].spec_items.splice(itemIndex, 1);
            // 构建规格组合列表
            self.buildSkulist();
          });
      }
    }
  };
</script>

<style scoped="scoped">
  .spec-many-type {
    margin-left: 180px;
    margin-top: 16px;
    padding: 20px;
    border: 1px solid #e5ecf4;
    background: #f6f9fc;
  }

  .spec-wrap .spec-hd {
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    font-weight: bold;
  }

  .spec-wrap .spec-hd .el-icon-delete-solid {
    font-size: 16px;
    color: #999999;
  }

  .spec-wrap .min-spc {
    border: 1px solid #dfecf8;
  }

  .spec-wrap .spec-bd {
    padding: 5px;
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    border-top: 1px solid #dfecf8;
    background: #ffffff;
  }

  .spec-wrap .spec-bd .el-tag {
    color: #333333;
  }

  .spec-wrap .spec-bd .item {
    position: relative;
    padding: 5px;
  }

  .spec-wrap .spec-bd .item input {
    padding-right: 30px;
  }

  .spec-wrap .spec-hd a,
  .spec-wrap .spec-hd .svg-icon,
  .spec-wrap .spec-bd .item .svg-icon {
    display: block;
    width: 16px;
    height: 16px;
  }

  .spec-wrap .spec-bd .item a {
    position: absolute;
    top: 6px;
    right: 5px;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .add-spec .from-box {
    display: flex;
    justify-content: flex-start;
  }

  .add-spec .item {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    width: 200px;
    margin-right: 20px;
  }

  .add-spec .item .key {
    display: block;
    white-space: nowrap;
  }
</style>
