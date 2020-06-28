<template>
  <!--
          作者：luoyiming
          时间：2019-10-24
          描述：商品管理
      -->
  <div class="product-list">

    <div class="common-seach-wrap">
       <!--产品状态切换-->
      <el-tabs size="samll" v-model="activeType" @tab-click="handleClick">
        <el-tab-pane label="全部" name="all"></el-tab-pane>
        <el-tab-pane label="出售中" name="sell"></el-tab-pane>
        <el-tab-pane label="库存紧张" name="stock"></el-tab-pane>
        <el-tab-pane label="已售罄" name="over"></el-tab-pane>
        <el-tab-pane label="已下架" name="lower"></el-tab-pane>
        <el-tab-pane label="草稿" name="draft"></el-tab-pane>
      </el-tabs>
       <!--搜索表单-->
      <el-form size="small" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="商品分类">
          <el-select size="small" v-model="searchForm.category_id" placeholder="所有分类">
            <el-option label="全部" value="0"></el-option>
            <el-option v-for="(item, index) in categoryList" :key="index" :label="item.name" :value="item.category_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="商品名称"><el-input size="small" v-model="searchForm.product_name" placeholder="请输入商品名称"></el-input></el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" icon="el-icon-search" @click="onSubmit">查询</el-button>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" icon="el-icon-plus" @click="addClick" v-auth="'/product/product/add'">添加产品</el-button>
        </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="product_name" label="产品" width="400px">
            <template slot-scope="scope">
              <div class="product-info">
                <div class="pic"><img v-img-url="scope.row.image[0].file_path" alt="" /></div>
                <div class="info">
                  <div class="name">{{ scope.row.product_name }}</div>
                  <div class="price">￥{{ scope.row.product_price }}</div>
                </div>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="category.name" label="分类"></el-table-column>
          <el-table-column prop="sales_actual" label="实际销量"></el-table-column>
          <el-table-column prop="product_stock" label="库存"></el-table-column>
          <el-table-column prop="product_status.text" label="状态"></el-table-column>
          <el-table-column prop="create_time" label="发布时间"></el-table-column>
          <el-table-column prop="product_sort" label="排序"></el-table-column>
          <el-table-column fixed="right" label="操作" width="160">
            <template slot-scope="scope">
              <el-button @click="editClick(scope.row)" type="text" size="small" v-auth="'/product/product/edit'">编辑</el-button>
              <el-button @click="delClick(scope.row)" type="text" size="small" v-auth="'/product/product/delete'">删除</el-button>
              <el-button @click="copyClick(scope.row)" type="text" size="small" v-auth="'/product/product/copy'">一键复制</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>

    <!--分页-->
    <div class="pagination">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        background
        :current-page="curPage"
        :page-size="pageSize"
        layout="total, prev, pager, next, jumper"
        :total="totalDataNumber"
      ></el-pagination>
    </div>
  </div>
</template>

<script>
import PorductApi from '@/api/product.js';
export default {
  components: {},
  data() {
    return {
      type: 'all',
      /*切换菜单*/
      activeType: 'all',
      /*切换选中值*/
      activeIndex: '0',
      /*是否加载完成*/
      loading: true,
      /*一页多少条*/
      pageSize: 10,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*搜索参数*/
      searchForm: {
        product_name: '',
        category_id: ''
      },
      /*列表数据*/
      tableData: [],
      /*产品类别*/
      categoryList: []
    };
  },
  created() {

    /*获取列表*/
    this.getData();

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

    /*切换菜单*/
    handleClick(tab, event) {
      let self = this;
      self.curPage = 1;
      self.loading = true;
      self.type = tab.name;
      self.getData();
    },

    /*获取列表*/
    getData() {
      let self = this;
      let Params = {};
      Params.page = self.curPage;
      Params.list_rows = self.pageSize;
      Params.type = self.type;
      PorductApi.productList(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.categoryList = data.data.catgory;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {
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

      PorductApi.productList(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.categoryList = data.data.catgory;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*打开添加*/
    addClick() {
      this.$router.push('/product/product/add');
    },

    /*打开编辑*/
    editClick(row) {
      this.$router.push({
        path: '/product/product/edit',
        query: {
          product_id: row.product_id,
          scene: 'edit'
        }
      });
    },

    /*打开复制*/
    copyClick(row) {
      this.$router.push({
        path: '/product/product/edit',
        query: {
          product_id: row.product_id,
          scene: 'copy'
        }
      });
    },

    /*删除*/
    delClick: function(row) {
      let self = this;
      self
        .$confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          PorductApi.delProduct({
            product_id: row.product_id
          }).then(res => {
            if(res.code==1){
              self.$message({
                message: '删除成功',
                type: 'success'
              });
              self.getData();
            }
          });
        });
    },


  }
};
</script>

<style></style>
