<template>
  <!--
      作者：luoyiming
      时间：2019-10-25
      描述：会员-用户列表-会员充值
  -->
  <el-dialog title="会员充值" v-model="dialogVisible" @close='dialogFormVisible' :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="充值余额" name="first">
        <el-form size="small" :model="form">
          <el-form-item label="当前余额" :label-width="formLabelWidth">
            <el-input v-model="form.balance" autocomplete="off" disabled="disabled"></el-input>
          </el-form-item>
          <el-form-item label="充值方式" :label-width="formLabelWidth">
            <el-radio-group v-model="recharge.balance.mode">
              <el-radio label="inc">增加</el-radio>
              <el-radio label="dec">减少</el-radio>
              <el-radio label="final">最终金额</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="变更金额" :label-width="formLabelWidth">
            <el-input v-model="recharge.balance.money" autocomplete="off" placeholder="请输入变更金额"></el-input>
          </el-form-item>
          <el-form-item label="管理员备注" :label-width="formLabelWidth">
            <el-input type="textarea" v-model="recharge.balance.remark" placeholder="请输入管理员备注"></el-input>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>

    <template #footer>
    <div class="dialog-footer">
      <el-button @click="dialogFormVisible">取 消</el-button>
      <el-button type="primary" @click="addUser(form.user_id)" :loading="loading">确 定</el-button>
    </div>
    </template>
  </el-dialog>
</template>

<script>
  import UserApi from '@/api/user.js';
  export default {
    data() {
      return {
        loading: false,
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        /*默认选中*/
        activeName: 'first',
        recharge: {
          balance: {
            mode: 'inc',
            remark: '',
          },
        },
        source: 0,
      };
    },
    props: ['open_add', 'form'],
    created() {
      this.dialogVisible = this.open_add;
    },
    methods: {

      /*选中*/
      handleClick(tab, event) {
        this.source = tab.index;
      },
      /*充值*/
      addUser(e) {
        let self = this;
        let params = self.recharge;
        let user_id = e;
        let source = self.source;
        self.loading = true;
        UserApi.userRecharge({
            params: params,
            user_id: user_id,
            source: source
          }, true).then(data => {
            self.loading = false;
            if (data.code == 1) {
              ElMessage({
                message: '恭喜你，修改成功',
                type: 'success'
              });
              self.dialogFormVisible(true);
            } else {
              self.loading = false;
             ElMessage.error('错了哦，这是一条错误消息');
            }
          })
          .catch(error => {
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
      }

    }
  };
</script>

<style></style>
