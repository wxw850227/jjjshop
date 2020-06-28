<template>
  <!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：设置-消息设置-短信设置
    -->
  <el-dialog :title="title" :visible.sync="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form ref="form" :model="settings" size="small">
      <div class="table-wrap">
        <div class="operation-wrap">
          <p> 配置说明：</p>
          <p> 1、短信模板里有的字段才勾选，如果没有请勿勾选。</p>
          <p> 2、模板变量替换成短信模板里的字段。</p>
        </div>
        <div>
          <el-form-item label="模板id：" prop="template_id" :rules="[{required: true,message: ' '}]">
            <el-input size="small" class="max-w460" v-model="settings.template_id" placeholder="请填写申请的短信模板code"></el-input>
          </el-form-item>
        </div>
        <el-table border ref="fieldTable" :data="fieldList" @selection-change="handleSelectionChange" v-loading="loading">
          <el-table-column type="selection" width="55">
          </el-table-column>
          <el-table-column label="字段名称">
            <template slot-scope="scope">
              <label v-text="scope.row.field_name"></label>
            </template>
          </el-table-column>
          <el-table-column label="模板变量名">
            <template slot-scope="scope">
              <el-input size="small" prop="field_new_ename" v-model="scope.row.field_new_ename"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="模板内容">
            <template slot-scope="scope">
              <el-input size="small" prop="field_value" :disabled="scope.row.is_var === 1" v-model="scope.row.filed_value"></el-input>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible">取 消</el-button>
      <el-button type="primary" @click="saveTemplate" :loading="loading">确 定</el-button>
    </div>
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
        loading: false,
        /*是否上传图片*/
        isupload: false,
        fieldList: [],
        title: '设置短信模板',
        checkList: [],
        /*设置*/
        settings: {}
      };
    },
    props: ['open_sms', 'messageModel'],
    created() {
      this.dialogVisible = this.open_sms;
      this.title = this.title + '(' + this.messageModel.message_name + ')';
      this.getData();
    },
    methods: {
      getData: function() {
        let self = this;
        self.loading = true;
        MessageApi.fieldList({
            message_id: self.messageModel.message_id,
            message_type: 'sms'
          }, true)
          .then(data => {
            data.data.list.forEach(function(field) {
              field['field_new_ename'] = field.field_ename;
            });
            self.fieldList = data.data.list
            //设置字段
            if (data.data.settings == null) {
              self.settings = {};
            } else {
              self.settings = data.data.settings;
            }
            self.loading = false;
            self.$nextTick(function() {
              self.initChecked();
            });
          })
          .catch(error => {

          });
      },
      /*添加用户*/
      saveTemplate() {
        let self = this;
        if (self.checkList.length == 0) {
          self.$message({
            message: '请选择消息字段',
            type: 'error'
          });
          return;
        }
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.loading = true;
            MessageApi.saveSettings({
              fieldList: self.checkList,
              message_id: self.messageModel.message_id,
              message_type: 'sms',
              template_id: self.settings.template_id
            }).then(data => {
              self.loading = false;
              self.$message({
                message: '保存成功',
                type: 'success'
              });
              self.dialogFormVisible(true);
            }).catch(error => {
              self.loading = false;
            });
          }
        });
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
      },
      handleSelectionChange(val) {
        this.checkList = val;
      },
      /*初始化选中*/
      initChecked: function() {
        let self = this;
        if (JSON.stringify(self.settings) == "{}") {
          return;
        }
        Object.keys(self.settings.var_data).forEach(function(key) {
          self.fieldList.forEach(function(field) {
            if (field.field_ename == key) {
              self.$refs.fieldTable.toggleRowSelection(field, true);
              field.field_new_ename = self.settings.var_data[key].field_name;
            }
          });
        });
      },
    }
  };
</script>
