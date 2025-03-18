<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-25
    	描述：商品-添加
    -->
  <el-dialog :title="title" v-model="dialogVisible" @close='dialogFormVisible' :close-on-click-modal="false"
    :close-on-press-escape="false">
    <!--添加消息模板-->
    <div class="common-level-rail">
      <el-button size="small" link  type="primary" @click="addClick">添加字段</el-button>
    </div>
    <el-form size="small">
      <div class="table-wrap">
        <el-table border :data="fieldData" v-loading="loading">
          <el-table-column label="字段名称" width="120px">
            <template #default="scope">
              <el-input size="small" prop="field_name" v-model="scope.row.field_name"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="字段英文名" width="130px">
            <template #default="scope">
              <el-input size="small" prop="field_ename" v-model="scope.row.field_ename"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="字段默认值">
            <template #default="scope">
              <el-input size="small" prop="filed_value" v-model="scope.row.filed_value"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="是否变量" width="70px">
            <template #default="scope">
              <el-checkbox prop="is_var" :checked="scope.row.is_var === 1" @change="checked=>checkRow(checked, scope.row)"></el-checkbox>
            </template>
          </el-table-column>
          <el-table-column label="排序" width="70px">
            <template #default="scope">
              <el-input size="small" prop="sort" v-model="scope.row.sort"></el-input>
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" width="70px">
            <template #default="scope">
              <el-button @click="deleteClick(scope.$index)" link  type="primary" size="small">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-form>
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="dialogFormVisible">取 消</el-button>
        <el-button type="primary" @click="saveField" :loading="loading">确 定</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script>
  import MessageApi from '@/api/message.js';
  export default {
    data() {
      return {
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        title: '字段管理',
        fieldData: [],
        deleteIds: [],
        loading: true,
      };
    },
    props: ['open_field', 'form'],
    created() {
      this.dialogVisible = this.open_field;
      this.title = this.title + '(' + this.form.message_name + ')';
      /*获取字段列表*/
      this.getFieldList();
    },
    methods: {
      /*获取字段列表*/
      getFieldList: function() {
        let self = this;
        MessageApi.fieldList({
            message_id: self.form.message_id
          }, true)
          .then(data => {
            self.loading = false;
            self.fieldData = data.data.list;
          })
          .catch(error => {
            self.loading = false;
          });
      },
      /*修改插件*/
      saveField: function() {
        let self = this;
        MessageApi.saveField({
            message_id: self.form.message_id,
            fieldData: self.fieldData,
            deleteIds: self.deleteIds
          }, true)
          .then(data => {
            ElMessage({
              message: '恭喜你，修改成功',
              type: 'success'
            });
            self.dialogFormVisible(true);
          })
          .catch(error => {

          });
      },
      /*添加字段*/
      addClick: function() {
        this.fieldData.push({
          message_field_id: 0,
          message_id: this.form.message_id,
          field_name: '',
          field_ename: '',
          filed_value: '',
          sort: 100
        });
      },
      deleteClick: function($index) {
        let field = this.fieldData[$index];
        if (field.message_field_id > 0) {
          this.deleteIds.push(field.message_field_id);
        }
        this.fieldData.splice($index, 1);
      },
      checkRow: function(checked, row) {
        row.is_var = checked ? 1 : 0;
      },
      /*关闭弹窗*/
      dialogFormVisible(e) {
        if (e) {
          this.$emit('closeDialog', {
            type: 'success',
            openDialog: false
          })
        } else {
          this.$emit('closeDialog', {
            type: 'error',
            openDialog: false
          })
        }
      }
    }
  };
</script>

<style></style>
