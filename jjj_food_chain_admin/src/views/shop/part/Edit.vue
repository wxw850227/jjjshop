<template>
  <!--
    	作者：luoyiming
    	时间：2019-10-25
    	描述：商城管理-编辑商城
    -->
  <el-dialog title="编辑平台" v-model="dialogVisible" @close='dialogFormVisible' :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form size="small" :model="form" ref="form" :rules="rules">
      <!--去除浏览器默认-->
      <div style="height: 0; overflow: hidden;">
        <input type="password">
      </div>
      <el-form-item label="平台名称" :label-width="formLabelWidth" prop="app_name">
        <el-input v-model="form.app_name" autocomplete="off" placeholder="请输入平台名称"></el-input>
      </el-form-item>
      <el-form-item label="过期时间" :label-width="formLabelWidth" prop="expire_time_text">
        <el-date-picker v-model="form.expire_time_text" type="date" placeholder="过期时间" value-format="YYYY-MM-DD"
          :disabled="form.no_expire"></el-date-picker>
        <el-checkbox v-model="form.no_expire" class="pl16">永不过期</el-checkbox>
      </el-form-item>
      <el-form-item label="平台账户名" :label-width="formLabelWidth" prop="user_name">
        <el-input v-model="form.user_name" autocomplete="off" placeholder="请输入平台账户名"></el-input>
        <p class="gray">注：平台后台用户名</p>
      </el-form-item>
      <el-form-item label="平台账户密码" :label-width="formLabelWidth" prop="password">
        <el-input type="password" v-model="form.password" autocomplete="off" placeholder="请输入密码"></el-input>
        <p class="gray">注：平台后台用户密码</p>
      </el-form-item>
      <el-form-item label="确认密码" :label-width="formLabelWidth" prop="password_confirm">
        <el-input type="password" v-model="form.password_confirm" autocomplete="off" placeholder="请输入确认密码"></el-input>
      </el-form-item>
    </el-form>
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="dialogFormVisible">取 消</el-button>
        <el-button type="primary" @click="addClick()" :loading="loading">确 定</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script>
  import ShopApi from '@/api/shop.js';
  export default {
    data() {
      /*验证商城名称*/
      let checkAppName = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('请输入平台名称'));
        } else {
          if (this.$filter.isAllSpace(value)) {
            return callback(new Error('不能全是空格'));
          } else {
            callback();
          }
        }
      };

      /*验证用户名*/
      let checkUsername = (rule, value, callback) => {
        if (!this.$filter.replaceSpace(value)) {
          return callback(new Error('平台账户名'));
        } else {
          if (this.$filter.hasSpace(value)) {
            return callback(new Error('不能包含空格'));
          } else {
            callback();
          }
        }
      };

      /*密码验证*/
      let checkPassword = (rule, value, callback) => {
        if (!value) {
          callback();
        } else {
          if (this.$filter.hasSpace(value)) {
            return callback(new Error('不能包含空格'));
          } else if (value.length < 6) {
            return callback(new Error('长度不能小于6位'));
          } else {
            callback();
          }
        }
      };

      /*确认新密码验证*/
      let checkPasswordConfirm = (rule, value, callback) => {
        if (!this.form.password) {
          callback();
        } else if (value !== this.form.password) {
          callback(new Error('确认密码不一致'));
        } else {
          callback();
        }
      };
      /* 时间验证 */
      let checkExpireTime = (rule, value, callback) => {
        if(this.form.no_expire){
          callback();
        }else if(!value){
          callback(new Error('请输入过期时间'));
        }else{
          callback();
        }
      };
      return {
        form: {
          no_expire: false,
        },
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        /*是否正在加载*/
        loading: false,
        /*验证规则*/
        rules: {
          app_name: [{
            validator: checkAppName,
            required: true,
            trigger: 'blur'
          }],
          user_name: [{
            validator: checkUsername,
            required: true,
            trigger: 'blur'
          }],
          password: [{
            validator: checkPassword,
            trigger: 'change'
          }],
          password_confirm: [{
            validator: checkPasswordConfirm,
            trigger: 'blur'
          }],
          expire_time_text: [{
            validator: checkExpireTime,
            required: true,
            trigger: 'blur'
          }],
        },
      };
    },
    props: ['open_edit', 'curModel'],
    created() {
      this.dialogVisible = this.open_edit;
      this.form = this.curModel;
    },
    methods: {
      /*添加商城*/
      addClick() {
        let self = this;
        let params = this.form;
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.loading = true;
            ShopApi.editShop(params, true).then(res => {
                self.loading = false;
                ElMessage({
                  message: '恭喜你，修改成功',
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
      }

    }
  };
</script>

<style></style>
