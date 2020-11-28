<template>
  <div class="article-box">

    <!--搜索表单-->
    <div class="common-seach-wrap">
      <el-form size="small" :inline="true" class="demo-form-inline">
        <el-form-item>
          <el-button size="small" icon="el-icon-notebook-2" @click="chooseList">选择文章列表</el-button>
        </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="article-content">
      <div class="table-wrap">
        <el-table size="mini" :data="tableData" border style="width: 100%">
          <el-table-column prop="article_title" label="文章标题"></el-table-column>
          <el-table-column prop="category.name" label="文章分类" width="80"></el-table-column>
          <el-table-column label="操作" width="80">
            <template slot-scope="scope">
              <el-button size="mini" @click="changeFunc(scope.row)">选择</el-button>
            </template>
          </el-table-column>
        </el-table>
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
  </div>
</template>

<script>
import ArticleApi from '@/api/article.js';
export default {
  data() {
    return {
      /*tab切换选择中值*/
      activeTab: 'second',
      /*一页多少条*/
      pageSize: 5,
      /*一共多少条数据*/
      totalDataNumber: 0,
      /*当前是第几页*/
      curPage: 1,
      /*是否加载完成*/
      loading: true,
      /*产品数据表*/
      tableData: [],
      /*选中的值*/
      activePage: {}
    };
  },
  created() {

    this.chooseList();

    /*获取列表*/
    this.getData();
  },
  watch: {},
  methods: {
    /*选择第几页*/
    handleCurrentChange(val) {
      let self = this;
      self.curPage = val;
      self.getData();
    },

    /*每页多少条*/
    handleSizeChange(val) {
      this.pageSize = val;
      this.curPage = 1;
      this.getData();
    },

    /*获取文章列表*/
    getData() {
      let self = this;
      self.loading = true;
      let Params = {};
      Params.page = self.curPage;
      Params.list_rows = self.pageSize;
      ArticleApi.articlelist(Params, true)
        .then(data => {
          self.loading = false;
          self.tableData = data.data.list.data;
          self.totalDataNumber = data.data.list.total;
        })
        .catch(error => {
          console.log(error);
        });
    },

    /*选中的值*/
    changeFunc(e) {
      let obj={};
      obj.name = e.article_title;
      obj.url = 'pages/article/detail/detail?article_id=' + e.article_id;
      obj.type = '文章';
      this.$emit('changeData',obj);
    },

    /*选择列表*/
    chooseList(){
      let obj={};
      obj.name = '文章列表';
      obj.url = 'pages/article/list/list';
      obj.type = '文章';
      this.$emit('changeData',obj);
    }

  }
};
</script>

<style></style>
