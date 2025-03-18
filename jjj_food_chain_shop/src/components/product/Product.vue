<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-25
    	描述：组件-选择商品
    -->
  <el-dialog title="选择商品" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false" width="900px">
    <div class="common-seach-wrap">
      <el-form :inline="true" size="small" :model="formInline" class="demo-form-inline">
        <el-form-item label="商品分类">
          <el-select v-model="formInline.category_id" placeholder="请选择商品分类">
            <el-option :value="0" :label="'全部'"></el-option>
            <template v-for="cat in cateList"  :key="cat.category_id">
              <el-option :value="cat.category_id" :label="cat.name"></el-option>
              <template v-for="two in cat.child" :key="two.category_id" >
                <template v-if="cat.child !== undefined" >
                  <el-option :value="two.category_id" :label="two.name" style="padding-left: 30px;"></el-option>
                  <template v-for="three in two.child" :key="three.category_id" >
                    <template v-if="two.child !== undefined">
                      <el-option :value="three.category_id" :label="three.name" style="padding-left: 60px;"></el-option>
                    </template>
                  </template>
                </template>
              </template>
            </template>
          </el-select>
        </el-form-item>
        <el-form-item label="商品名称">
          <el-input placeholder="请输入商品名称" v-model="formInline.search">
            <!--  <el-select v-model="formInline.status" slot="prepend" placeholder="请选择商品状态" style="width: 80px;">
              <el-option label="全部" value="-1"></el-option>
                <el-option v-for="(item,index) in status" :key="item.id" :label="item.name" :value="item.id"></el-option>
            </el-select>-->
             <template #append>
            <el-button icon="Search" @click="getData">
              查询
            </el-button>
            </template>
          </el-input>
        </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table size="small" :data="productList" border style="width: 100%" highlight-current-row v-loading="loading"
          @selection-change="tableCurrentChange">
          <el-table-column width="70" label="商品图片">
            <template #default="scope">
              <img :src="scope.row.image && scope.row.image[0] && scope.row.image[0].file_path" :width="30" :height="30" />
            </template>
          </el-table-column>
          <el-table-column prop="product_name" label="商品名称"></el-table-column>
          <el-table-column prop="category.name" width="100" label="商品分类"></el-table-column>
          <el-table-column prop="create_time" width="140" label="添加时间"></el-table-column>
          <el-table-column type="selection" :selectable="selectableFunc" width="44" v-if="islist"></el-table-column>
          <el-table-column width="80" label="单选" v-if="!islist">
            <template #default="scope">
              <el-button size="mini" v-if="scope.row.noChoose" @click="SingleFunc(scope.row)">选择</el-button>
              <el-button size="mini" v-else disabled>已选</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <!--分页-->
      <div class="pagination">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background
          :current-page="curPage" :page-sizes="[2, 10, 20, 50, 100]" :page-size="pageSize"
          layout="total, prev, pager, next, jumper" :total="totalDataNumber"></el-pagination>
      </div>
    </div>
    <template #footer>
      <div class="dialog-footer">
        <el-button size="small" @click="dialogVisible=false">取 消</el-button>
        <el-button size="small" type="primary" @click="addClerk" v-if="islist">确 定</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script>
  import PorductApi from '@/api/product.js';
  export default {
    data() {
      return {
        /*是否加载完成*/
        loading: true,
        /*当前是第几页*/
        curPage: 1,
        /*一页多少条*/
        pageSize: 20,
        /*一共多少条数据*/
        totalDataNumber: 0,
        formInline: {
          category_id: 0,
          search: ''
        },
        //商品分类列表
        cateList: [],
        //商品列表
        productList: [],
        //类别列表
        status: [{
            id: 10,
            name: '上架'
          },
          {
            id: 20,
            name: '下架'
          }
        ],
        multipleSelection: [],
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        /*结果类别*/
        type: 'error',
        /*传出去的参数*/
        params: null
      };
    },
    props: ['isproduct', 'excludeIds', 'islist', 'spec_type', 'product_type'],
    watch: {
      isproduct: function(n, o) {
        if (n != o) {
          if (n) {
            this.dialogVisible = n;
            this.type = 'error';
            this.params = null;
            this.getData();
          }
        }
      }
    },
    created() {

    },
    methods: {

      /*是否可以勾选*/
      selectableFunc(e) {
        if (typeof e.noChoose !== 'boolean') {
          return true;
        }
        return e.noChoose;
      },

      /*选择第几页*/
      handleCurrentChange(val) {
        this.curPage = val;
        this.getData();
      },

      /*每页多少条*/
      handleSizeChange(val) {
        this.curPage = 1;
        this.pageSize = val;
        this.getData();
      },

      /*获取商品列表*/
      getData() {
        let self = this;
        let params = self.formInline;
        params.page = self.curPage;
        params.list_rows = self.pageSize;
        params.spec_type = self.spec_type; /* 10单规格 */
        let callback = function(res) {
          if (res.code == 1) {
            self.loading = false;
            self.cateList = res.data.category;
            /*判断是否需要去重*/
            if (self.excludeIds && typeof(self.excludeIds) != 'undefined' && self.excludeIds.length > 0) {
              res.data.list.data.forEach(item => {
                if (self.excludeIds.indexOf(item.product_id) > -1) {
                  item.noChoose = false;
                } else {
                  item.noChoose = true;
                }
              });
            } else {
              if (!self.islist) {
                res.data.list.data.forEach(item => {
                  item.noChoose = true;
                });
              }
            }
            self.productList = res.data.list.data;
            self.totalDataNumber = res.data.list.total;
          }
        }
        if (self.product_type == 0) {
          PorductApi.takeProductList(params, true)
            .then(res => {
              callback(res)
            })
        } else {
          PorductApi.storeProductList(params, true)
            .then(res => {
              callback(res)
            })
        }

      },

      /*单选*/
      SingleFunc(row) {
        this.multipleSelection = [row];
        this.addClerk();
      },

      //点击确定
      addClerk() {
        let self = this;
        let params = null;
        let type = 'success';
        if (self.multipleSelection.length < 1) {
          self.$message({
            message: '请至少选择一件产品商品！',
            type: 'error'
          });
          return;
        }
        if (self.islist && typeof(self.islist) != 'undefined') {
          self.multipleSelection.forEach(item => {
            item.image = item.image[0].file_path;
          });
          params = self.multipleSelection;
        } else {
          params = self.multipleSelection[0];
          params.image = params.image[0].file_path;
        }
        self.params = params;
        self.type = 'success';
        self.dialogVisible = false;
      },

      /*关闭弹窗*/
      dialogFormVisible() {
        this.formInline.category_id = 0;
        this.formInline.search = '';
        this.$emit('closeDialog', {
          type: this.type,
          openDialog: false,
          params: this.params
        });
      },

      /*监听复选按钮选中事件*/
      tableCurrentChange(val) {
        this.multipleSelection = val;
      }
    }
  };
</script>

<style>
  .no-list .el-checkbox {
    display: none;
  }
</style>