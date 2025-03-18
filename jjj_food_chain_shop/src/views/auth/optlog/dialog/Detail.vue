<template>
  <!--
        作者：luoyiming
        时间：2020-07-25
        描述：权限-操作日志-详情
    -->
  <el-dialog title="详情" v-model="dialogVisible" @close="dialogFormVisible" :close-on-press-escape="false">
    <el-form size="mini" :model="form">
      <el-form-item label="标题：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.title }}</div>
      </el-form-item>
      <el-form-item label="id：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.opt_log_id }}</div>
      </el-form-item>
      <el-form-item label="用户名：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.user_name }}</div>
      </el-form-item>
      <el-form-item label="真实姓名：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.real_name }}</div>
      </el-form-item>
      <el-form-item label="url：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.url }}</div>
      </el-form-item>
      <el-form-item label="内容：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.content }}</div>
      </el-form-item>
      <el-form-item label="ip：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.ip }}</div>
      </el-form-item>
      <el-form-item label="agent：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.agent }}</div>
      </el-form-item>
      <el-form-item label="添加时间：" :label-width="formLabelWidth">
        <div class="item-content-box">{{ form.create_time }}</div>
      </el-form-item>
    </el-form>
    <template #footer>
    <div class="dialog-footer"><el-button size="small" @click="dialogVisible = false">关闭</el-button></div>
    </template>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      /*左边长度*/
      formLabelWidth: '140px',
      /*是否显示*/
      dialogVisible: false
    };
  },
  props: ['open', 'form'],
  created() {},
  watch: {
    open: function(n, o) {
      if (n != o && n) {
        this.dialogVisible = this.open;
      }
    }
  },
  methods: {
    /*关闭弹窗*/
    dialogFormVisible(e) {
      this.$emit('close', {});
    }
  }
};
</script>

<style scoped="scoped">
  .item-content-box{
    padding-top: 4px;
    line-height: 20px;
     min-height: 20px;
    color: #333333;
    border-bottom: 1px solid #EEEEEE;
  }
</style>
