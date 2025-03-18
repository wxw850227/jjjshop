<template>
  <!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：产品分类管理
    -->
  <div class="product">
    <el-tabs v-model="activeName" @tab-change="handleClick">
      <el-tab-pane label="普通分类" name="first">
        <div class="common-level-rail">
          <el-button size="small" type="primary" @click="addClick" icon="Plus"
            v-auth="'/product/store/category/Add'">添加分类</el-button>
        </div>
        <!--内容-->
        <div class="product-content">
          <div class="table-wrap">
            <el-table size="small" :data="tableData" row-key="category_id" default-expand-all
              :tree-props="{children: 'child'}" style="width: 100%" v-loading="loading">
              <el-table-column prop="name" label="分类名称" width="180"></el-table-column>
              <el-table-column prop="" label="图片" width="180">
                <template #default="scope">
                  <img v-if="scope.row.images" v-img-url="scope.row.images.file_path" alt="" width="50" />
                </template>
              </el-table-column>
              <el-table-column prop="sort" label="分类排序"></el-table-column>
              <el-table-column prop="sort" label="状态">
                <template #default="scope">
                  <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0"
                    @change="statusSet($event,scope.row.category_id)">
                  </el-switch>
                </template>
              </el-table-column>
              <el-table-column prop="create_time" label="添加时间"></el-table-column>
              <el-table-column fixed="right" label="操作" width="100">
                <template #default="scope">
                  <el-button @click="editClick(scope.row)" type="text" size="small"
                    v-auth="'/product/store/category/Edit'">编辑</el-button>
                  <el-button @click="deleteClick(scope.row)" type="text" size="small"
                    v-auth="'/product/store/category/Delete'">删除</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="特色分类" name="second">
        <!--内容-->
        <div class="product-content">
          <div class="table-wrap">
            <el-table size="small" :data="tableData" row-key="category_id" default-expand-all
              :tree-props="{children: 'child'}" style="width: 100%" v-loading="loading">
              <el-table-column prop="name" label="分类名称" width="180"></el-table-column>
              <el-table-column prop="" label="图片" width="180">
                <template #default="scope">
                  <img v-if="scope.row.images" v-img-url="scope.row.images.file_path" alt="" width="50" />
                </template>
              </el-table-column>
              <el-table-column prop="sort" label="分类排序"></el-table-column>
              <el-table-column prop="sort" label="状态">
                <template #default="scope">
                  <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0"
                    @change="statusSet($event,scope.row.category_id)">
                  </el-switch>
                </template>
              </el-table-column>
              <el-table-column prop="create_time" label="添加时间"></el-table-column>
              <el-table-column fixed="right" label="操作" width="100">
                <template #default="scope">
                  <el-button @click="editClick(scope.row)" type="text" size="small"
                    v-auth="'/product/store/category/Edit'">编辑</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
      </el-tab-pane>
    </el-tabs>
    <!--添加产品分类-->




    <!--添加-->
    <Add v-if="open_add" :open_add="open_add" :addform="categoryModel" @closeDialog="closeDialogFunc($event, 'add')">
    </Add>
    <!--修改-->
    <Edit v-if="open_edit" :open_edit="open_edit" :editform="categoryModel"
      @closeDialog="closeDialogFunc($event, 'edit')"></Edit>

  </div>
</template>

<script>
  import PorductApi from '@/api/product.js';
  import Add from './Add.vue';
  import Edit from './Edit.vue';
  export default {
    components: {
      Add,
      Edit
    },
    data() {
      return {
        /*是否加载完成*/
        loading: true,
        activeName: 'first',
        /*列表数据*/
        tableData: [],
        /*是否打开添加弹窗*/
        open_add: false,
        /*是否打开编辑弹窗*/
        open_edit: false,
        /*当前编辑的对象*/
        categoryModel: {
          catList: [],
          model: {}
        },
      };
    },
    created() {
      /*获取列表*/
      this.getData();
    },
    methods: {
      // hasImages(e) {
      //   if (e) {
      //     return e.file_path;
      //   } else {
      //     return '';
      //   }
      // },
      handleClick() {
        this.getData();
      },
      /*获取列表*/
      getData() {
        let self = this;
        self.loading = true;
        if (self.activeName == 'first') {
          PorductApi.storeCatList({}, true)
            .then(data => {
              self.loading = false;
              self.tableData = data.data.list;
              self.categoryModel.catList = self.tableData;
            })
            .catch(error => {
              self.loading = false;
            });
        } else {
          PorductApi.storeCatSP({}, true)
            .then(data => {
              self.loading = false;
              self.tableData = data.data.list;
              self.categoryModel.catList = self.tableData;
            })
            .catch(error => {
              self.loading = false;
            });
        }

      },
      /*打开添加*/
      addClick() {
        this.open_add = true;
      },

      /*打开编辑*/
      editClick(item) {
        this.categoryModel.model = item;
        this.open_edit = true;
      },

      statusSet(e, id) {
        let self = this;
        PorductApi.storeCatSet({
          category_id: id,
          status: e
        }).then(data => {
          ElMessage({
            message: data.msg,
            type: 'success'
          });
        });
      },

      /*关闭弹窗*/
      closeDialogFunc(e, f) {
        if (f == 'add') {
          this.open_add = e.openDialog;
          if (e.type == 'success') {
            this.getData();
          }
        }
        if (f == 'edit') {
          this.open_edit = e.openDialog;
          if (e.type == 'success') {
            this.getData();
          }
        }
      },
      /*删除分类*/
      deleteClick(row) {
        let self = this;
        ElMessageBox.confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          type: 'warning'
        }).then(() => {
          PorductApi.storeCatDel({
            category_id: row.category_id
          }).then(data => {
            ElMessage({
              message: '删除成功',
              type: 'success'
            });
            self.getData();
          });
        });
      },
    }
  };
</script>

<style></style>
