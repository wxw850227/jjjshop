<template>
  <!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：产品分类-添加
    -->
  <el-dialog :title="title" :visible.sync="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form size="small">
      <div class="table-wrap">
        <div class="operation-wrap">
          <p> 配置说明：</p>
          <p> 1、公众号模板里有的字段才勾选，如果没有请勿勾选。</p>
          <p> 2、模板变量替换成公众号模板里的字段。</p>
        </div>
        <div>
          <el-form-item label="模板id：">
            <el-input size="small" class="max-w460" v-model="template_id" placeholder="请填写申请的公众号模板消息id"></el-input>
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
              <el-input size="small" prop="filed_new_value" :disabled="scope.row.is_var === 1" v-model="scope.row.filed_new_value"></el-input>
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
        title: '设置公众号消息模板',
        checkList: [],
        /*设置*/
        settings: {},
        template_id: ''
      };
    },
    props: ['open_mp', 'messageModel'],
    created() {
      this.dialogVisible = this.open_mp;
      this.title = this.title + '(' + this.messageModel.message_name + ')';
      this.getData();
    },
    methods: {
      getData: function() {
        let self = this;
        self.loading = true;
        MessageApi.fieldList({
            message_id: self.messageModel.message_id,
            message_type: 'mp'
          }, true)
          .then(data => {
            data.data.list.forEach(function(field) {
              field['field_new_ename'] = field.field_ename;
              field['filed_new_value'] = field.filed_value;
            });
            self.fieldList = data.data.list
            //设置字段
            if(data.data.settings == null || data.data.settings.length == 0){
              self.settings = {};
              self.template_id = '';
            }else{
               self.settings = data.data.settings;
               self.template_id = data.data.settings['template_id'];
            }
            self.loading = false;
            self.$nextTick(function() {
              self.initChecked();
            });
          })
          .catch(error => {

          });
      },
      /*保存*/
      saveTemplate() {
        let self = this;
        self.loading = true;
        MessageApi.saveSettings({
          fieldList: self.checkList,
          message_id: self.messageModel.message_id,
          message_type: 'mp',
          template_id: self.template_id
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
        if(JSON.stringify(self.settings) == "{}"){
          return;
        }
        Object.keys(self.settings.var_data).forEach(function(key) {
          self.fieldList.forEach(function(field) {
            if (field.field_ename == key) {
              self.$refs.fieldTable.toggleRowSelection(field, true);
              field.field_new_ename = self.settings.var_data[key].field_name;
              field.filed_new_value = self.settings.var_data[key].filed_value;
            }
          });
        });
      },
    }
  };
</script>