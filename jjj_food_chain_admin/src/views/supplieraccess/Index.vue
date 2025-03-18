<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-24
    	描述：权限管理-菜单&权限
    -->
  <div class="product">
    <!--添加菜单&权限-->
    <div class="common-level-rail d-b-c">
      <el-button size="small" link  type="primary" @click="addClick" icon="Plus">添加菜单&权限</el-button>
      <el-form :inline="true" :model="formSearch" size="small">
        <el-form-item>
          <el-checkbox v-model="formSearch.is_menu" @change="changeIsMenuFunc">只显示菜单</el-checkbox>
          <el-checkbox v-model="formSearch.pack_up" @change="changePackUpFunc">收起</el-checkbox>
        </el-form-item>
      </el-form>
    </div>
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <div>
          <el-table
            size="small"
            :data="tableData"
            style="width: 100%;margin-bottom: 20px;"
            row-key="access_id"
            border
            default-expand-all
            ref="theTable"
            :tree-props="{ children: 'children' }"
            v-loading="loading"
          >
            <el-table-column prop="name" label="菜单名称">
              <template #default="scope">
                <span v-if="scope.row.path=='/plus'" class="fb red f18">
                  {{scope.row.name}}
                </span>
                <span v-else>
                  {{scope.row.name}}
                </span>
              </template>
            </el-table-column>
            <el-table-column prop="path" label="路径"></el-table-column>
            <el-table-column prop="is_route" label="类别" width="90">
              <template #default="scope">
                <span v-if="scope.row.is_route==1">页面</span>
                <span v-if="scope.row.is_route==0">按钮</span>
                <span v-if="scope.row.is_route==2">独立单页面</span>
              </template>
            </el-table-column>
            <el-table-column prop="is_show" label="是否显示" width="80">
              <template #default="scope">
                <el-switch
                  v-model="scope.row.is_show"
                  :active-value="1"
                  :inactive-value="0"
                  @change="isShowFunc(scope.row)"
                  active-color="#13ce66"
                  inactive-color="#cccccc"
                ></el-switch>
              </template>
            </el-table-column>
            <el-table-column prop="sort" label="排序" width="60"></el-table-column>
            <el-table-column prop="create_time" label="添加时间" width="140"></el-table-column>
            <el-table-column prop="name" label="操作" width="230">
              <template #default="scope">
                <el-button @click="addClick(scope.row,'copy')" link  type="primary" size="small" v-if="scope.row.path!='/plus'">一键复制</el-button>
                <el-button @click="addClick(scope.row,'child')" link  type="primary" size="small">添加子菜单</el-button>
                <el-button @click="editClick(scope.row)" link  type="primary" size="small">编辑</el-button>
                <el-button @click="deleteClick(scope.row)" link  type="primary" size="small" v-if="scope.row.path!='/plus'">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>

    <!--添加-->
    <Add v-if="open_add" :open_add="open_add" :add_type="add_type" :rawData="rawData" :selectModel="selectModel" @closeDialog="closeDialogFunc($event, 'add')"></Add>

    <!--编辑-->
    <Edit v-if="open_edit" :open_edit="open_edit" :rawData="rawData" :selectModel="selectModel" @closeDialog="closeDialogFunc($event, 'edit')"></Edit>

  </div>
</template>
<script>
import AccessApi from '@/api/access.js';
import Edit from './part/Edit.vue';
import Add from './part/Add.vue';
import { deepClone } from '@/utils/base.js';
export default {
  components: {
    /*编辑组件*/
    Edit: Edit,
    Add: Add
  },
  data() {
    return {
      /*是否正在加载*/
      loading: true,
      /*筛选搜索*/
      formSearch: {
        /*是否只显示菜单*/
        is_menu: false,
        /*是否收起*/
        pack_up: false
      },
      /*原始数据*/
      rawData: [],
      /*表格数据*/
      tableData: [],
      /*是否打开添加弹窗*/
      open_add: false,
      /*添加类型*/
      add_type:'',
      /*选中的对象*/
      selectModel:{},
      /*是否打开编辑弹窗*/
      open_edit: false,
      /*当前编辑的对象*/
      userModel: {},
    };
  },
  created() {
    /*获取列表*/
    this.getTableList();
  },
  methods: {
    /*列表是否只显示菜单*/
    changeIsMenuFunc(e) {
      let list = deepClone(this.rawData);
      if (e) {
        this.showScreen(list, 1);
        this.tableData = list;
      } else {
        this.tableData = list;
      }
    },

    /*是否显示开关*/
    isShowFunc(e) {
      let self = this;
      AccessApi.supplierstatus({ access_id: e.access_id, status: e.is_show }, true)
        .then(data => {
          if (data.code == 1) {
            ElMessage({
              message: data.msg,
              type: 'success'
            });
            self.getTableList();
          }
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*是否收起*/
    changePackUpFunc(e) {
      this.forArr(this.tableData, !e);
    },

    /*列表收起*/
    forArr(arr, isExpand) {
      arr.forEach(i => {
        this.$refs.theTable.toggleRowExpansion(i, isExpand);
        if (i.children) {
          this.forArr(i.children, isExpand);
        }
      });
    },

    /*切换显示类别*/
    changeShowFunc(e) {
      let list = deepClone(this.rawData);
      if (e == 'all') {
        this.tableData = list;
      } else {
        let type;
        if (e == 'show') {
          type = 1;
        }
        if (e == 'hide') {
          type = 0;
        }
        this.showScreen(list, type);
        this.tableData = list;
      }
    },

    /*显示筛选*/
    showScreen(list, type) {
      for (let i = 0; i < list.length; i++) {
        let item = list[i];
        if (typeof item.is_menu != 'undefined' && item.is_menu != type) {
          list.splice(i, 1);
          i--;
        } else {
          if (item.children.length > 0) {
            this.showScreen(item.children, type);
          }
        }
      }
    },

    /*获取列表*/
    getTableList() {
      let self = this;
      let Params = {};
      AccessApi.supplieraccessList(Params, true)
        .then(res => {
          self.loading = false;
          self.rawData = res.data;
          self.tableData = res.data;
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*打开添加*/
    addClick(e,type) {
      if(e&&typeof(e)!='undefined'){
        this.add_type=type;
        this.selectModel=deepClone(e);
      }else{
        this.parents_id=0;
      }
      this.open_add = true;
    },

    /*打开编辑*/
    editClick(item) {
      this.selectModel = item;
      this.open_edit = true;
    },

    closeDialogFunc(e, f) {
      if (f == 'add') {
        this.open_add = e.openDialog;
        if (e.type == 'success') {
          this.getTableList();
        }
      }
      if (f == 'edit') {
        this.open_edit = e.openDialog;
        if (e.type == 'success') {
          this.getTableList();
        }
      }
    },

    /*删除用户*/
    deleteClick(row) {
      let self = this;
      ElMessageBox.confirm('删除后不可恢复，确认删除该记录吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          self.loading = true;
          AccessApi.supplierdelAccess(
            {
              access_id: row.access_id
            },
            true
          )
            .then(data => {
              if (data.code == 1) {
                self.loading = false;
                ElMessage({
                  message: data.msg,
                  type: 'success'
                });
                self.getTableList();
              }
            })
            .catch(error => {
              self.loading = false;
            });
        })
        .catch(() => {});
    }
  }
};
</script>
