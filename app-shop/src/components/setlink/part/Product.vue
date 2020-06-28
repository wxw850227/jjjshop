<template>
  <div class="marketing-box">
    <el-tabs v-model="activeTab">
      <el-tab-pane label="分类" name="first"></el-tab-pane>
      <el-tab-pane label="详情" name="second"></el-tab-pane>
    </el-tabs>

    <div class="product" v-if="activeTab=='first'">
      <!--内容-->
      <div class="product-content" v-loading="loading">
        <div class="table-wrap type-table">
          <el-tree :data="tableData" node-key="id" default-expand-all :props="defaultProps" :expand-on-click-node="false">
            <div class="custom-tree-node d-b-c ww100" slot-scope="{ node, data }">
              <span>{{ node.label }}</span>
              <span>
                <el-button type="primary" size="mini" v-if="node.data.category_id!=obj_id" @click="changeFunc(node.data)">选择</el-button>
                <el-button type="success" size="mini" v-if="node.data.category_id==obj_id" @click="changeFunc(node.data)">已选</el-button>
              </span>
            </div>
          </el-tree>
        </div>
      </div>
    </div>


    <div class="product-list" v-if="activeTab=='second'" v-loading="loading">
      <!--搜索表单-->
      <div class="common-seach-wrap">
        <el-form size="small" :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="商品名称">
            <el-input size="small" v-model="searchForm.product_name" placeholder="请输入商品名称"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
          </el-form-item>
        </el-form>
      </div>

      <!--内容-->
      <div class="product-content">
        <div class="table-wrap setlink-product-table">
          <el-table size="mini" :data="tableData" border style="width: 100%">
            <el-table-column prop="product_name" label="产品">
              <template slot-scope="scope">
                <div class="product-info">
                  <div class="pic">
                    <img v-img-url="scope.row.image[0].file_path" alt="" />
                  </div>
                  <div class="info">
                    <div class="name">{{scope.row.product_name}}</div>
                    <div class="price">￥{{scope.row.product_price}}</div>
                  </div>
                </div>
              </template>
            </el-table-column>
            <el-table-column label="操作" width="80">
              <template slot-scope="scope">
                <el-button type="primary" size="mini" v-if="scope.row.product_id!=obj_id" @click="changeFunc(scope.row)">选择
                </el-button>
                <el-button type="success" size="mini" v-if="scope.row.product_id==obj_id" @click="changeFunc(scope.row)">已选
                </el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </div>
      <!--分页-->
      <div class="pagination">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background :current-page="curPage"
          :page-size="pageSize" layout="total, prev, pager, next, jumper" :total="totalDataNumber">
        </el-pagination>
      </div>
    </div>

  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  export default {
    data() {
      return {
        /*tab切换选择中值*/
        activeTab: 'first',
        searchForm: {
          product_name: '',
        },
        loading: true,
        /*一页多少条*/
        pageSize: 5,
        /*一共多少条数据*/
        totalDataNumber: 0,
        /*当前是第几页*/
        curPage: 1,

        /*页面数据*/
        pages: [{
            value: 'pages/index/index',
            label: '首页',
            type: '页面',
          },
          {
            value: 'pages/product/list/list',
            label: '分类',
            type: '页面',
          },
          {
            value: 'pages/cart/cart',
            label: '购物车',
            type: '页面',
          }
        ],
        /*当前编辑的对象*/
        categoryModel: {
          catList: [],
          model: {}
        },
        /*产品数据表*/
        tableData: [],
        /*选中的对象*/
        objs: {
          name: '',
          url: ''
        },
        /*当前选中对象的主键*/
        obj_id: 0,
        /*默认值*/
        defaultProps: {
          children: 'child',
          label: 'name'
        }
      }
    },
    created() {
      /*获取列表*/
      this.getCate();
    },
    watch: {
      activeTab: function(n, o) {
        if (n != o) {
          this.activeTab = n;
          this.tableData = [];
          if (n == 'first') {
            this.getCate();
          }
          if (n == 'second') {
            this.getData();
          }
        }
      }
    },
    methods: {

      /*选择第几页*/
      handleCurrentChange(val) {
        let self = this;
        self.loading = true;
        self.curPage = val;
        self.getData();
      },

      /*每页多少条*/
      handleSizeChange(val) {
        this.pageSize = val;
        this.getData();
      },

      /*获取列表*/
      getData() {
        let self = this;
        self.loading = true;
        let Params = {};
        Params.page = self.curPage;
        Params.list_rows = self.pageSize;
        Params.name = self.name;
        PorductApi.productList(Params, true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
        }).catch(error => {
          self.loading = false;
        });
      },
      /*搜索查询*/
      onSubmit() {
        let self = this;
        self.loading = true;
        let Params = self.searchForm;
        Params.page = 1;
        Params.list_rows = self.pageSize;

        PorductApi.productList(Params, true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total
        }).catch(error => {
          self.loading = false;
        });
      },


      /*获取列表*/
      getCate() {
        let self = this;
        self.loading = true;
        PorductApi.catList({}, true)
          .then(data => {
            self.loading = false;
            self.tableData = data.data.list;
            self.categoryModel.catList = self.tableData;
          })
          .catch(error => {
            self.loading = false;
          });
      },
      /*选中的值*/
      changeFunc(e) {
        if (this.activeTab == 'first') {
          this.obj_id = e.category_id;
          this.objs.name = e.name;
          this.objs.url = 'pages/product/list/list?category_id=' + e.category_id;
          this.objs.type = '分类';
        }
        if (this.activeTab == 'second') {
          this.obj_id = e.product_id;
          this.objs.name = e.product_name;
          this.objs.url = 'pages/product/detail/detail?product_id=' + e.product_id;
          this.objs.type = '商品';
        }

        //从e中把name和ur赋值到下面的对象
        this.$emit('changeData', this.objs);
      },
      hasImages(e) {
        if (e) {
          return e.file_path;
        } else {
          return '';
        }
      },
    }
  }
</script>
<style>
  .table-wrap.setlink-product-table .product-info .pic {
    width: 40px;
    height: 40px;
  }

  .table-wrap.setlink-product-table .product-info .pic img {
    width: 40px;
    height: 40px;
  }

  .marketing-box .el-tree-node__content {
    height: 30px;
  }

  .marketing-box .el-tree-node__content {
    margin-bottom: 10px;
  }
</style>
