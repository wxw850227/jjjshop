<template>
  <el-dialog title="选择商品" :visible.sync="dialogVisible" @close='dialogFormVisible' :close-on-click-modal="false"
    :close-on-press-escape="false" width="900px">

    <div class="common-seach-wrap">
      <el-form :inline="true" size="small" :model="formInline" class="demo-form-inline">
        <el-form-item label="商品分类">
          <el-select v-model="formInline.category_id" placeholder="请选择商品分类">

            <template v-for="cat in cateList">
              <el-option value="0"  label="全部"></el-option>
              <el-option :value="cat.category_id" :key="cat.category_id" :label="cat.name"></el-option>
              <template v-if="cat.child !== undefined" v-for="two in cat.child">
                <el-option :value="two.category_id" :key="two.category_id" :label="two.name" style="padding-left: 30px;"></el-option>
                <template v-if="two.child !== undefined" v-for="three in two.child">
                  <el-option :value="three.category_id" :key="three.category_id" :label="three.name" style="padding-left: 60px;"></el-option>
                </template>
              </template>
            </template>
          </el-select>
        </el-form-item>
        <el-form-item label="商品名称">
          <el-input placeholder="请输入商品名称" v-model="formInline.search" ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button icon="el-icon-search" @click="getData">查询</el-button>
          </el-form-item>
      </el-form>
    </div>

    <!--内容-->
    <div class="product-content" style="margin-top: 10px;">
      <div class="table-wrap">
        <el-table size="mini" :data="productList" border style="width: 100%" highlight-current-row v-loading="loading"
           @selection-change="tableCurrentChange">
          <el-table-column prop="image[0].image_id" width="50" label="商品图片">
            <template slot-scope="scope">
              <img :src="scope.row.image[0].file_path" width="30" height="30" />
            </template>
          </el-table-column>
          <el-table-column prop="product_name" label="商品名称"></el-table-column>
          <el-table-column prop="category.name" width="100" label="商品分类"></el-table-column>
          <el-table-column prop="create_time" width="140" label="添加时间"></el-table-column>
          <el-table-column
            type="selection"
            width="44">
          </el-table-column>
        </el-table>
      </div>

      <!--分页-->
      <div class="pagination">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background :current-page="curPage"
          :page-sizes="[2, 10, 20, 50, 100]" :page-size="pageSize" layout="total, prev, pager, next, jumper"
          :total="totalDataNumber">
        </el-pagination>
      </div>
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible">取 消</el-button>
      <el-button type="primary" @click="addClerk">确 定</el-button>
    </div>
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
        formInline: {},
        //商品分类列表
        cateList: [],
        //商品列表
        productList: [],
        //性别列表
        status: [{
            'id': 10,
            'name': '上架'
          },
          {
            'id': 20,
            'name': '下架'
          },
        ],
        formRules: {
          name: [{
              required: true,
              message: '请输入等级名称',
              trigger: 'blur'
            },
            {
              min: 3,
              max: 5,
              message: '长度在 3 到 5 个字符',
              trigger: 'blur'
            }
          ],
        },
        multipleSelection: [],
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false
      };
    },
    props: ['isproduct','islist'],
    created() {
      this.dialogVisible = this.isproduct;
      /*获取列表*/
      this.getData();
    },
    methods: {
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
      getData() {
        let self = this;
        let params = self.formInline;
        params.page=self.curPage;
        params.list_rows=self.pageSize;
        PorductApi.productLists(params, true).then(data => {
            if (data.code == 1) {
              self.loading = false;
              self.cateList = data.data.catgory;
              self.productList = data.data.list.data;
              self.totalDataNumber = data.data.list.total;

            } else {
              self.$message.error('错了哦，这是一条错误消息');
            }
          })
          .catch(error => {

          });
      },

      //点击确定
      addClerk() {
        let self = this;
        let params=null;
        let type = 'success';
        if (self.multipleSelection.length < 1) {
          self.$message({
            message: '请至少选择一件产品商品！',
            type: 'error'
          });
          return;
        }
        if(self.islist){
          params = self.multipleSelection;
        }else{
          params=self.multipleSelection[0];
          params.image = params.image[0].file_path;
        }

        this.$emit('closeDialog', {
          type: type,
          openDialog: false,
          params: params
        })

      },

      /*关闭弹窗*/
      dialogFormVisible(e) {
        this.$emit('closeDialog', {
          type: 'error',
          openDialog: false,
        })
      },

      /*监听复选按钮选中事件*/
      tableCurrentChange(val) {
        this.multipleSelection = val;
      }

    }
  };
</script>

<style></style>
