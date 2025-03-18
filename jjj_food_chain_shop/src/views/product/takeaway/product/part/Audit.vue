<template>
  <!--提交-->
  <div class="basic-setting-content pl16 pr16 p-fix">
    <el-form-item label="审核状态：" :rules="[{ required: true, message: '选择审核状态' }]"  prop="model.audit_status" >
      <el-radio-group v-model="form.model.audit_status">
        <el-radio :label="10" >通过</el-radio>
        <el-radio :label="20">未通过</el-radio>
      </el-radio-group>
    </el-form-item>
    <el-form-item label="审核备注：">
      <el-input type="textarea" min="0" v-model="form.model.audit_remark" class="mb16 max-w460" placeholder="请输入拒绝原因">
      </el-input>
    </el-form-item>
  </div>
</template>

<script>
export default {
  data() {
    return {

    };
  },
  inject: ['form'],
  created() {

  },
  methods: {

  }
};
</script>

<style>

</style>
