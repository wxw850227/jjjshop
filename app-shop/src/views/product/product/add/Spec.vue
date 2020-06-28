<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-26
    	描述：商品添加-基础信息
    -->
  <div>
    <el-form-item label="产品规格：">
      <el-radio-group v-model="form.model.spec_type">
        <el-radio :label="10">单规格</el-radio>
        <el-radio :label="20">多规格</el-radio>
      </el-radio-group>
    </el-form-item>

    <!--单规格-->
    <div v-if="form.model.spec_type == 10">
      <el-form-item label="产品价格：" :rules="[{ required: true, message: ' ' }]" prop="model.sku.product_price">
        <el-input v-model="form.model.sku.product_price" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="产品划线价："><el-input v-model="form.model.sku.line_price" class="max-w460"></el-input></el-form-item>
      <el-form-item label="库存数量：" :rules="[{ required: true, message: ' ' }]" prop="model.sku.stock_num">
        <el-input v-model="form.model.sku.stock_num" class="max-w460"></el-input>
      </el-form-item>
      <el-form-item label="商品重量(Kg)：" :rules="[{ required: true, message: ' ' }]" prop="model.sku.product_weight">
        <el-input v-model="form.model.sku.product_weight" class="max-w460"></el-input>
      </el-form-item>
    </div>

    <!--多规格-->
    <div v-if="form.model.spec_type == 20">
      <!--规格属性-->
      <div class="spec-wrap">
        <div :key="attr.group_name" v-for="(attr, index) in form.model.spec_many.spec_attr">
          <div class="spec-hd">
            <div class="input-box">
              <el-tag closable type="success" @close="onDeleteGroup(index)">{{ attr.group_name }}</el-tag>
            </div>
            <a href="javascript:void(0);" @click="onDeleteGroup(index)"><Icon :iconname="'#icon-shanchu'"></Icon></a>
          </div>
          <div class="spec-bd">
            <div class="item" v-for="(items, i) in attr.spec_items" :key="items.spec_value">
              <el-tag closable @close="onDeleteValue(index, i)">{{ items.spec_value }}</el-tag>
            </div>
            <div class="item">
              <el-input size="small" v-model="attr.tempValue" style="width: 160px;"></el-input>
            </div>
            <div class="item">
              <el-button size="small" @click="onSubmitAddValue(attr)" :loading="attr.loading">添加</el-button>
            </div>
          </div>
        </div>
        <div class="mb16 mt10">
          <el-button size="small" icon="el-icon-circle-plus-outline" v-show="showAddGroupBtn" @click="onToggleAddGroupForm">添加规格</el-button>
        </div>

        <div class="add-spec mb16" v-show="!showAddGroupBtn">
          <div class="from-box">
            <div class="item">
              <span class="key">规格名：</span>
              <el-input size="small" v-model="addGroupFrom.specName" placeholder="请输入规格名称"></el-input>
            </div>
            <div class="item">
              <span class="key">规格值：</span>
              <el-input size="small" v-model="addGroupFrom.specValue" placeholder="请输入规格值"></el-input>
            </div>
            <el-button type="primary" size="small" :loading="groupLoading" @click="onSubmitAddGroup" plain>确定</el-button>
            <el-button size="small" @click="onToggleAddGroupForm">取消</el-button>
          </div>
        </div>
      </div>

      <!--批量设置-->
      <el-form-item label="规格明细：">
        <div>
          批量设置
          <el-input size="small" v-model="batchData.product_price" placeholder="销售价" style="width: 160px;padding-left: 4px;"></el-input>
          <el-input size="small" v-model="batchData.line_price" placeholder="划线价" style="width: 160px;padding-left: 4px;"></el-input>
          <el-input size="small" v-model="batchData.stock_num" placeholder="库存" style="width: 160px;padding-left: 4px;"></el-input>
          <el-input size="small" v-model="batchData.product_weight" placeholder="重量" style="width: 160px;padding-left: 4px;"></el-input>
          <el-button size="small" @click="onSubmitBatchData">确定</el-button>
        </div>
        <!--多规格表格-->
        <div v-if="form.model.spec_many && form.model.spec_many.spec_list.length > 0">
          <el-table :data="form.model.spec_many.spec_list" :span-method="objectSpanMethod" border style="width: 100%; margin-top: 20px">
            <el-table-column width="180" :label="item.group_name" :key="item.group_name" v-for="(item, index) in form.model.spec_many.spec_attr">
              <template slot-scope="scope">
                {{ scope.row.rows[index].spec_value }}
              </template>
            </el-table-column>
            <el-table-column label="规格图片">
              <template slot-scope="scope">
                <img v-img-url="scope.row.spec_form.image_path" alt="" width="35" height="35" @click="chooseSpecImage(scope.$index)" />
              </template>
            </el-table-column>
            <el-table-column label="销售价">
              <template slot-scope="scope">
                <el-form-item
                  label=""
                  :rules="[{ required: true, message: ' ' }]"
                  :prop="'model.spec_many.spec_list.' + scope.$index + '.spec_form.product_price'"
                  style="margin-bottom: 0;"
                >
                  <el-input size="small" prop="product_price" v-model="scope.row.spec_form.product_price"></el-input>
                </el-form-item>
              </template>
            </el-table-column>
            <el-table-column label="划线价">
              <template slot-scope="scope">
                <el-form-item label="" style="margin-bottom: 0;"><el-input size="small" prop="line_price" v-model="scope.row.spec_form.line_price"></el-input></el-form-item>
              </template>
            </el-table-column>
            <el-table-column label="库存">
              <template slot-scope="scope">
                <el-form-item
                  label=""
                  :rules="[{ required: true, message: ' ' }]"
                  :prop="'model.spec_many.spec_list.' + scope.$index + '.spec_form.stock_num'"
                  style="margin-bottom: 0;"
                >
                  <el-input size="small" prop="stock_num" v-model="scope.row.spec_form.stock_num"></el-input>
                </el-form-item>
              </template>
            </el-table-column>
            <el-table-column label="重量(kg)">
              <template slot-scope="scope">
                <el-form-item
                  label=""
                  :rules="[{ required: true, message: ' ' }]"
                  :prop="'model.spec_many.spec_list.' + scope.$index + '.spec_form.product_weight'"
                  style="margin-bottom: 0;"
                >
                  <el-input size="small" prop="product_weight" v-model="scope.row.spec_form.product_weight"></el-input>
                </el-form-item>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </el-form-item>
    </div>
    <!--上传图片组件-->
    <Upload v-if="isupload" :isupload="isupload" @returnImgs="returnImgsFunc">上传图片</Upload>
  </div>
</template>

<script>
import PorductApi from '@/api/product.js';
import Upload from '@/components/file/Upload';
export default {
  components: {
    Upload
  },
  data() {
    return {
      // 显示添加规格组按钮
      showAddGroupBtn: true,
      // 显示添加规格组表单
      showAddGroupForm: false,
      // 新增规格组值
      addGroupFrom: {
        specName: '',
        specValue: ''
      },
      // 当前规格属性是否锁定
      isSpecLocked: false,
      // 批量设置sku属性
      batchData: {
        product_price: '',
        line_price: '',
        stock_num: '',
        product_weight: ''
      },
      groupLoading: false,
      isupload: false,
      //上传图片选择的下标
      spec_index: -1
    };
  },
  props: ['form'],
  created() {
    /*获取列表*/
    if (this.form.model.spec_many && this.form.model.spec_many.spec_list.length > 0) {
      this.buildSkulist();
    }
  },
  methods: {
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
      var spec_attr = this.form.model.spec_many.spec_attr;
      // 规格组合总数 (table行数)
      var totalRow = 1;
      //比如2个规格,只有第一列有多行
      if (columnIndex < spec_attr.length - 1) {
        let startRowIndex = columnIndex + 1;
        let endRowIndex = spec_attr.length - 1;
        for (var i = startRowIndex; i <= endRowIndex; i++) {
          totalRow *= spec_attr[i].spec_items.length;
        }
        if (rowIndex % totalRow === 0) {
          return {
            rowspan: totalRow,
            colspan: 1
          };
        } else {
          return {
            rowspan: 0,
            colspan: 0
          };
        }
      }
    },
    /**
     * 显示/隐藏添加规则组
     */
    onToggleAddGroupForm: function() {
      this.showAddGroupBtn = !this.showAddGroupBtn;
      this.showAddGroupForm = !this.showAddGroupForm;
    },

    /**
     * 表单提交：新增规格组
     * @returns {boolean}
     */
    onSubmitAddGroup: function() {
      var self = this;
      if (self.addGroupFrom.specName === '' || self.addGroupFrom.specValue === '') {
        self.$message('请填写规则名或规则值');
        return false;
      }
      // 添加到数据库
      self.groupLoading = true;
      let Params = {
        spec_name: self.addGroupFrom.specName,
        spec_value: self.addGroupFrom.specValue
      };
      PorductApi.addSpec(Params, true)
        .then(data => {
          self.groupLoading = false;
          // 记录规格数据
          self.form.model.spec_many.spec_attr.push({
            group_id: data.data['spec_id'],
            group_name: self.addGroupFrom.specName,
            spec_items: [
              {
                item_id: data.data['spec_value_id'],
                spec_value: self.addGroupFrom.specValue
              }
            ],
            tempValue: '',
            loading: false
          });
          // 清空输入内容
          self.addGroupFrom.specName = '';
          self.addGroupFrom.specValue = '';
          // 隐藏添加规则组
          self.onToggleAddGroupForm();
          // 构建规格组合列表
          self.buildSkulist();
        })
        .catch(error => {
          self.groupLoading = false;
        });
    },
    /**
     * 新增规格值
     * @param index
     */
    onSubmitAddValue: function(specAttr) {
      var self = this;
      if (!specAttr.hasOwnProperty('tempValue') || specAttr.tempValue === '') {
        self.$message('规格值不能为空');
        return false;
      }
      // 添加到数据库
      specAttr.loading = true;
      let Params = {
        spec_id: specAttr.group_id,
        spec_value: specAttr.tempValue
      };
      PorductApi.addSpecValue(Params, true)
        .then(data => {
          specAttr.loading = false;
          // 记录规格数据
          specAttr.spec_items.push({
            item_id: data.data['spec_value_id'],
            spec_value: specAttr.tempValue
          });
          // 清空输入内容
          specAttr.tempValue = '';
          // 构建规格组合列表
          self.buildSkulist();
        })
        .catch(error => {
          specAttr.loading = false;
        });
    },

    /**
     * 构建规格组合列表
     */
    buildSkulist: function() {
      var self = this;
      var spec_attr = self.form.model.spec_many.spec_attr;
      var specArr = [];
      for (var i = 0; i < spec_attr.length; i++) {
        specArr.push(spec_attr[i].spec_items);
      }
      var specListTemp = self.calcDescartes(specArr);

      var specList = [];
      for (var i = 0; i < specListTemp.length; i++) {
        var rows = [];
        var specSkuIdAttr = [];
        if (!Array.isArray(specListTemp[i])) {
          rows.push(specListTemp[i]);
        } else {
          rows = specListTemp[i];
        }
        for (var j = 0; j < rows.length; j++) {
          specSkuIdAttr.push(rows[j].item_id);
        }
        specList.push({
          spec_sku_id: specSkuIdAttr.join('_'),
          rows: rows,
          spec_form: {}
        });
      }

      // 合并旧sku数据
      if (self.form.model.spec_many.spec_list.length > 0 && specList.length > 0) {
        for (i = 0; i < specList.length; i++) {
          var overlap = self.form.model.spec_many.spec_list.filter(function(val) {
            return val.spec_sku_id === specList[i].spec_sku_id;
          });
          if (overlap.length > 0) specList[i].spec_form = overlap[0].spec_form;
        }
      }

      self.form.model.spec_many.spec_list = specList;
    },
    calcDescartes: function(array) {
      if (array.length < 2) return array[0] || [];
      return [].reduce.call(array, function(col, set) {
        var res = [];
        col.forEach(function(c) {
          set.forEach(function(s) {
            var t = [].concat(Array.isArray(c) ? c : [c]);
            t.push(s);
            res.push(t);
          });
        });
        return res;
      });
    },
    /**
     * 删除规则组事件
     * @param index
     */
    onDeleteGroup: function(index) {
      var self = this;
      self
        .$confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          // 删除指定规则组
          self.form.model.spec_many.spec_attr.splice(index, 1);
          // 构建规格组合列表
          self.buildSkulist();
        });
    },

    /**
     * 删除规则值事件
     * @param index
     * @param itemIndex
     */
    onDeleteValue: function(index, itemIndex) {
      var self = this;
      self
        .$confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          // 删除指定规则组
          self.form.model.spec_many.spec_attr[index].spec_items.splice(itemIndex, 1);
          // 构建规格组合列表
          self.buildSkulist();
        });
    },
    /**
     * 批量设置sku属性
     */
    onSubmitBatchData: function() {
      var self = this;
      self.form.model.spec_many.spec_list.forEach(function(value) {
        for (var key in self.batchData) {
          if (self.batchData.hasOwnProperty(key) && self.batchData[key]) {
            self.$set(value.spec_form, key, self.batchData[key]);
          }
        }
      });
    },
    chooseSpecImage: function(spec_index) {
      this.isupload = true;
      this.spec_index = spec_index;
    },
    returnImgsFunc(e) {
      this.isupload = false;
      this.$set(this.form.model.spec_many.spec_list[this.spec_index].spec_form, 'image_id', e[0]['file_id']);
      this.$set(this.form.model.spec_many.spec_list[this.spec_index].spec_form, 'image_path', e[0]['file_path']);
    }
  }
};
</script>

<style>
.spec-wrap {
  padding-left: 180px;
}
.spec-wrap .spec-hd {
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f1f5f7;
}
.spec-wrap .spec-bd {
  padding: 5px;
  display: flex;
  justify-content: flex-start;
  flex-wrap: wrap;
  border: 1px solid #f1f5f7;
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
