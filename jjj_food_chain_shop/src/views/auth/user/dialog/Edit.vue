<template>
  <!--
        作者：luoyiming
        时间：2019-10-25
        描述：权限-管理员列表-修改管理员
    -->
  <el-dialog title="修改管理员" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false" :close-on-press-escape="false">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" :rules="formRules" :label-width="formLabelWidth" v-loading="loading">
      <el-form-item label="用户名" prop="user_name"><el-input v-model="form.user_name" placeholder="请输入用户名"></el-input></el-form-item>
      <el-form-item label="所属角色" prop="access_id">
        <el-select v-model="form.access_id" :multiple="true">
          <el-option v-for="item in roleList" :value="item.role_id" :key="item.role_id" :label="item.role_name_h1"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="登录密码" prop="password"><el-input v-model="form.password" placeholder="请输入登录密码" type="password"></el-input></el-form-item>
      <el-form-item label="确认密码" prop="confirm_password"><el-input v-model="form.confirm_password" placeholder="请输入确认密码" type="password"></el-input></el-form-item>
      <el-form-item label="姓名" prop="real_name"><el-input v-model="form.real_name"></el-input></el-form-item>
    </el-form>
    <template #footer>
    <div  class="dialog-footer">
      <el-button @click="dialogVisible = false">取 消</el-button>
      <el-button type="primary" @click="onSubmit" :loading="loading">确 定</el-button>
    </div>
    </template>
  </el-dialog>
</template>

<script>
import AuthApi from '@/api/auth.js';
export default {
  data() {
    return {
      /*左边长度*/
      formLabelWidth: '120px',
      /*是否显示*/
      loading: false,
      /*是否显示*/
      dialogVisible: false,
      /*form表单对象*/
      form: {
        confirm_password:'',
        access_id:[]
      },
      /*当前角色*/
      access_id: [],
      /*角色对象*/
      roleList: [],
      /*form验证*/
      formRules: {
        user_name: [
          {
            required: true,
            message: ' ',
            trigger: 'blur'
          }
        ],
        access_id: [
          {
            required: true,
            message: ' ',
            trigger: 'blur'
          }
        ],
        password: [
          {
            required: true,
            message: ' ',
            trigger: 'blur'
          }
        ],
        confirm_password: [
          {
            required: true,
            message: ' ',
            trigger: 'blur'
          }
        ],
        real_name: [
          {
            required: true,
            message: ' ',
            trigger: 'blur'
          }
        ]
      }
    };
  },
  props: ['open','shop_user_id'],
  watch: {
    open: function(n, o) {
      if (n != o) {
        this.dialogVisible = this.open;
        this.getData();
      }
    }
  },
  created() {},
  methods: {

    /*获取数据*/
    getData() {
      let self = this;
      AuthApi.userEditInfo({
          shop_user_id: this.shop_user_id
        }).then(res => {
          self.loading = false;
          self.roleList = res.data.roleList;
          let obj=res.data.info;
          obj.access_id = res.data.role_arr;
          obj.password = '';
          self.form=obj;
        })
        .catch(error => {
          self.loading = false;
        });
    },

    /*修改*/
    onSubmit() {
      let self = this;
      self.loading = true;
      let params = self.form;
      AuthApi.userEdit(params, true)
        .then(data => {
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
    },

    /*关闭弹窗*/
    dialogFormVisible(e) {
      if (e) {
        this.$emit('close', {
          type: 'success',
          openDialog: false
        });
      } else {
        this.$emit('close', {
          type: 'error',
          openDialog: false
        });
      }
    }
  }
};
</script>

<style></style>
