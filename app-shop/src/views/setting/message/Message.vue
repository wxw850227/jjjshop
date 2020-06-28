<template>
  <!--
      作者 WuYuseng
      时间：2019-10-26
      描述：设置-消息设置-商家通知
  -->
  <div class="user">
    <!--内容-->
    <div class="product-content">
      <div class="table-wrap">
        <el-table :data="tableData" border style="width: 100%" v-loading="loading">
          <el-table-column prop="message_type" label="所属"></el-table-column>
          <el-table-column prop="message_name" label="通知名称"></el-table-column>
          <el-table-column prop="remark" label="推送规则"></el-table-column>
          <el-table-column label="小程序通知" v-if="message_to == 10">
            <template slot-scope="scope">
              <el-checkbox v-model="scope.row.wx_status" @change="checked=>wxStatusChange(checked, scope.row)">启用</el-checkbox>
              <el-link type="primary" :underline="false" style="padding-left: 10px;" @click="wxClick(scope.row)">设置</el-link>
            </template>
          </el-table-column>
          <el-table-column label="短信通知">
            <template slot-scope="scope">
              <el-checkbox v-model="scope.row.sms_status" @change="checked=>smsStatusChange(checked, scope.row)">启用</el-checkbox>
              <el-link type="primary" :underline="false" style="padding-left: 10px;" @click="smsClick(scope.row)">设置</el-link>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>

    <!--小程序-->
    <Wx v-if="open_wx" :open_wx="open_wx" :messageModel="messageModel" @closeDialog="closeDialogFunc($event, 'wx')"></Wx>
    <!--短信-->
    <Sms v-if="open_sms" :open_sms="open_sms" :messageModel="messageModel" @closeDialog="closeDialogFunc($event, 'sms')"></Sms>
  </div>
</template>

<script>
  import MessageApi from '@/api/message.js';
  import Wx from './settings/Wx.vue';
  import Sms from './settings/Sms.vue';

  export default {
    components: {
      Wx,
      Sms
    },
    data() {
      return {
        /*是否加载完成*/
        loading: true,
        /*列表数据*/
        tableData: [],
        open_wx: false,
        open_sms: false,
        /*当前编辑的对象*/
        messageModel: {}
      };
    },
    props: ['message_to'],
    watch:{
      'message_to':function(n,o){
        if(n!=o){
          /*获取列表*/
          this.getData();
        }
      }
    },
    created() {
      /*获取列表*/
      this.getData();
    },
    methods: {
      /*获取列表*/
      getData() {
        let self = this;
        MessageApi.messageList({
            message_to: self.message_to
          }, true)
          .then(data => {
            self.loading = false;
            self.tableData = data.data.list;
            self.tableData.forEach(function(message) {
              message.wx_status = message.wx_status === 1 ? true : false;
              message.sms_status = message.sms_status === 1 ? true : false;
              if (message.message_settings_id == null) {
                message.message_settings_id = 0;
              }
            });
          })
          .catch(error => {

          });
      },
      /*微信小程序消息模板设置*/
      wxClick(item) {
        this.messageModel = item;
        this.open_wx = true;
      },
      /*短信模板设置*/
      smsClick(item) {
        this.messageModel = item;
        this.open_sms = true;
      },
      /*关闭弹窗*/
      closeDialogFunc(e, f) {
        if (f == 'wx') {
          this.open_wx = e.openDialog;
          if (e.type == 'success') {
            this.getData();
          }
        }
        if (f == 'sms') {
          this.open_sms = e.openDialog;
          if (e.type == 'success') {
            this.getData();
          }
        }
      },
      wxStatusChange: function(checked, row) {
        let self = this;

        if (row.message_settings_id == 0 || row['wx_template'] == null) {
          self.$alert('请先点击左边设置，设置模板规则', '提示', {
            confirmButtonText: '确定'
          });
          row.wx_status = false;
          return;
        }
        self.loading = true;
        MessageApi.updateSettingsStatus({
            message_type: 'wx',
            message_settings_id: row.message_settings_id
          }, true)
          .then(data => {
            self.loading = false;
            row.wx_status = checked;
          })
          .catch(error => {
            self.loading = false;
            row.wx_status = !checked;
          });
      },
      smsStatusChange: function(checked, row) {
        let self = this;

        if (row.message_settings_id == 0 || row['sms_template'] == null) {
          self.$alert('请先点击左边设置，设置模板规则', '提示', {
            confirmButtonText: '确定'
          });
          row.sms_status = false;
          return;
        }
        self.loading = true;
        MessageApi.updateSettingsStatus({
            message_type: 'sms',
            message_settings_id: row.message_settings_id
          }, true)
          .then(data => {
            self.loading = false;
            row.sms_status = checked;
          })
          .catch(error => {
            self.loading = false;
            row.sms_status = !checked;
          });
      },
    }
  };
</script>

<style>
  .operation-wrap {
    border-radius: 8px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 15px 15px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    overflow: hidden;
    background: #909399;
    background-size: 100% 100%;
    color: #fff;
    margin-bottom: 10px;
  }
</style>
