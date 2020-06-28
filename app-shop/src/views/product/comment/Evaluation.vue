<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-24
    	描述：商品评价
    -->
  <div class="product">
    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="评价">
          <el-select size="small" v-model="searchForm.score" placeholder="">
            <el-option label="全部" :value="0"></el-option>
            <el-option v-for="(item,index) in commentList" :key="index" :label="item.name" :value="item.val"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" icon="el-icon-search" @click="onSubmit">查询</el-button>
        </el-form-item>
      </el-form>
    </div>

    <template>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="全部评价" name="-1"></el-tab-pane>
        <el-tab-pane :label="'待审核('+num+')'" name="0"></el-tab-pane>
        <el-tab-pane label="审核通过" name="1"></el-tab-pane>
        <el-tab-pane label="审核未通过" name="2"></el-tab-pane>
      </el-tabs>
    </template>

    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table size="small" :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column label="商品" width="400px">
            <template slot-scope="scope">
              <div class="product-info">
                <div class="pic"><img :src="scope.row.product.image[0].file_path" alt="" /></div>
                <div class="info">
                  <div class="name">{{scope.row.product.product_name}}</div>
                  <div class="price">￥{{scope.row.product.product_price}}</div>
                </div>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="user.nickName" label="	用户"></el-table-column>
          <el-table-column prop="score" label="评分">
            <template slot-scope="scope">
              <span v-if="scope.row.score==10">好评</span>
              <span v-else-if="scope.row.score==20">中评</span>
              <span v-else-if="scope.row.score==30">差评</span>
            </template>
          </el-table-column>
          <el-table-column prop="content" label="评价内容">
            <template slot-scope="scope">
              <span class="green fb">{{scope.row.content}}</span>
            </template>
          </el-table-column>
          <el-table-column prop="is_picture" label="图片">
            <template slot-scope="scope">
              <span v-if="scope.row.is_picture==0">没有</span>
              <span v-else>有</span>
            </template>
          </el-table-column>
          <el-table-column prop="status" label="评价状态">
            <template slot-scope="scope">
              <span v-if="scope.row.status==0">待审核</span>
              <span v-if="scope.row.status==1">审核通过</span>
              <span v-if="scope.row.status==2">审核未通过</span>
            </template>
          </el-table-column>
          <el-table-column prop="sort" label="排序"></el-table-column>
          <el-table-column prop="create_time" label="评论时间"></el-table-column>
          <el-table-column fixed="right" label="操作" width="90">
            <template slot-scope="scope">
              <el-button @click="detailClick(scope.row)" type="text" size="small" v-auth="'/product/comment/detail'">详情</el-button>
              <el-button @click="delClick(scope.row)" type="text" size="small" v-auth="'/product/comment/delete'">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <!--分页-->
      <div class="pagination">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background :current-page="curPage"
          :page-size="pageSize" layout="total, prev, pager, next, jumper"
          :total="totalDataNumber">
        </el-pagination>
      </div>
    </div>

  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  export default {
    components: {

    },
    data() {
      return {
        loading: true,
        activeName: '-1',
        num: 0,
        /*列表数据*/
        tableData: [],
        /*一页多少条*/
        pageSize: 20,
        totalDataNumber: 0,
        /*当前是第几页*/
        curPage: 1,
        /*搜索参数*/
        searchForm: {
          score: 0
        },
        commentList: [{
            name: '好评',
            val: 10
          },
          {
            name: '中评',
            val: 20
          },
          {
            name: '差评',
            val: 30
          },
        ],
        status: -1,
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
        self.curPage = val;
        self.loading = true;
        let Params = self.searchForm;
        self.getData(Params);
      },

      /*每页多少条*/
      handleSizeChange(val) {
        this.curPage = 1;
        this.pageSize = val;
        this.getData();
      },
      /*获取列表*/
      getData(param = '') {
        let self = this;
        let Params = {};
        Params.status = self.status;
        Params.page = self.curPage;
        Params.list_rows = self.pageSize;
        if (param != '') {
          Params.score = param.score;
        }
        PorductApi.getCommentList(Params, true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total
          self.num = data.data.num;
        }).catch(error => {
          self.loading = false;
        });
      },

      /*打开编辑*/
      detailClick(row) {
        this.$router.push({
          path: '/product/comment/Detail',
          query: {
            comment_id: row.comment_id,
          }
        });
      },
      /*订单详情*/
      orderClick(row) {
        this.$router.push({
          path: '/product/comment/Order',
          query: {
            order_id: row.order_id,
          }
        });
      },
      /*删除*/
      delClick: function(row) {
        let self = this;
        self.$confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          type: 'warning'
        }).then(() => {
          PorductApi.delComment({
            comment_id: row.comment_id
          }).then(data => {
            self.$message({
              message: '删除成功',
              type: 'success'
            });
            self.getData();
          });
        });
      },
      /*搜索查询*/
      onSubmit() {
        let self = this;
        self.loading = true;
        let Params = self.searchForm;
        Params.page = self.curPage;
        Params.list_rows = self.pageSize;
        Params.status = self.status;
        PorductApi.getCommentList(Params, true).then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;

          self.totalDataNumber = data.data.list.total
        }).catch(error => {
          self.loading = false;
        });
      },
      /*切换选项卡*/
      handleClick(tab, event) {
        let self = this;
        self.curPage = 1;
        self.loading = true;
        self.status = tab.name;
        self.getData();
      },
    }
  };
</script>

<style></style>
