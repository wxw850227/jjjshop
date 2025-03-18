<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：会员-用户列表-会员充值
  -->
  <div>
    <el-dialog title="取消订单" v-model="dialogVisible" @close='dialogFormVisible' :close-on-click-modal="false"
      :close-on-press-escape="false">
      <el-form size="small" ref="form" :model="form">
        <el-form-item label="订单号" :label-width="formLabelWidth" prop="order_no"
          :rules="[{required: true,message: ' '}]">
          <el-input v-model="form.order_no" placeholder="请输入订单号" class="max-w460" :readonly="true"></el-input>
        </el-form-item>
        <el-form-item label="取消原因" :label-width="formLabelWidth" prop="cancel_reason"
          :rules="[{required: true,message: ' '}]">
          <el-input type="textarea" v-model="form.cancel_reason" placeholder="请输入取消原因"></el-input>
        </el-form-item>
      </el-form>
      <template #footer>
      <div class="dialog-footer">
        <el-button @click="dialogFormVisible">取 消</el-button>
        <el-button type="primary" @click="submit" :loading="loading">确 定</el-button>
      </div>
      </template>
    </el-dialog>
  </div>
</template>

<script>
  import TakeOutApi from '@/api/takeout.js';
  import draggable from 'vuedraggable';
  export default {
    components: {
      draggable
    },
    data() {
      return {
        loading: false,
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        form: {
          deliver_id: '',
          cancel_reason: '',
          order_no: ''
        },
      };
    },
    props: ['open_edit', 'deliver_id', 'order_no'],
    created() {
      this.dialogVisible = this.open_edit;
      this.form.deliver_id = this.deliver_id;
      this.form.order_no = this.order_no;
    },
    methods: {
      /*处理*/
      submit() {
        let self = this;
        let form = self.form;
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.loading = true;
            TakeOutApi.cancel(form, true).then(data => {
                self.loading = false;
                ElMessage({
                  message: data.msg,
                  type: 'success'
                });
                self.dialogFormVisible(true);
              })
              .catch(error => {
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
    }
  };
</script>

<style></style>
