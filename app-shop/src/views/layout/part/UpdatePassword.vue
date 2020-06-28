<template>
  <!--
          作者：luoyiming
          时间：2019-11-13
          描述：修改密码
      -->
  <el-dialog title="修改密码" :visible.sync="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false" :close-on-press-escape="false" width="30%">
    <el-form size="small" :model="formData" :rules="rules" ref="formData">
      <!--原始密码-->
      <el-form-item label="原始密码" :label-width="formLabelWidth" prop="oldpass">
        <el-input type="password" v-model="formData.oldpass" autocomplete="off"></el-input>
      </el-form-item>
      <!--新密码-->
      <el-form-item label="新密码" :label-width="formLabelWidth" prop="password">
        <el-input type="password" v-model="formData.password" autocomplete="off"></el-input>
      </el-form-item>
      <!--确认新密码-->
      <el-form-item label="确认新密码" :label-width="formLabelWidth" prop="confirmPass">
        <el-input type="password" v-model="formData.confirmPass" autocomplete="off"></el-input>
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible">取 消</el-button>
      <el-button type="primary" @click="submitFunc('formData')" :loading="loading">确 定</el-button>
    </div>
  </el-dialog>
</template>

<script>
import UserApi from '@/api/user.js';
export default {
  data() {

    /*新密码校验规则*/
    var validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        if (this.formData.confirmPass !== '') {
          this.$refs.formData.validateField('confirmPass');
        }
        callback();
      }
    };
    /*确认密码校验规则*/
    var validateConfirmPass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入确认密码'));
      } else if (value !== this.formData.password) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };

    return {
      loading: false,
      /*左边长度*/
      formLabelWidth: '100px',
      /*是否显示*/
      dialogVisible: true,
      /*表单数据对象*/
      formData: {
         /*原始密码*/
        oldpass:'',
        /*新密码*/
        password:'',
        /*确认密码*/
        confirmPass:''
      },
      /*验证规则*/
      rules: {
        /*原始密码*/
        oldpass: [{ required: true, message: '请输入原始密码', trigger: 'blur' }],
        /*新密码*/
        password: [{ required: true, validator:validatePassword, trigger:'blur' }],
        /*确认密码*/
        confirmPass: [{ required: true, validator:validateConfirmPass, trigger:'blur' }]
      }
    };
  },
  methods: {

    /*提交修改密码*/
    submitFunc(formData) {
      let self = this;
      let Param = self.formData;
      self.$refs[formData].validate(valid => {
        /*验证*/
        if (valid) {
          self.loading = true;
          UserApi.EditPass(Param, true)
            .then(data => {
              self.loading = false;
              /*修改成功，调用关闭弹窗方法*/
              if (data.code == 1) {
                self.$message({
                  message: data.msg,
                  type: 'success'
                });
                self.dialogFormVisible();
              } else {
                self.loading = false;
              }
            })
            .catch(error => {
              self.loading = false;
            });
        }
      });
    },

    /*关闭弹窗*/
    dialogFormVisible() {
      this.$emit('close', false);
    }
  }
};
</script>

<style></style>
