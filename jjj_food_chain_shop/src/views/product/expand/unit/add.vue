<template>
  <!--
    	描述：商品-单位库-添加单位
    -->
  <el-dialog title="添加单位" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form size="small" :model="form" :rules="formRules" ref="form">
      <el-form-item label="排序" prop="sort" :label-width="formLabelWidth">
        <el-input v-model.number="form.sort" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="单位名称" prop="unit_name" :label-width="formLabelWidth">
        <el-input v-model="form.unit_name" autocomplete="off"></el-input>
      </el-form-item>
    </el-form>
    <template #footer>
    <div class="dialog-footer">
      <el-button @click="dialogFormVisible">取 消</el-button>
      <el-button type="primary" @click="submit" :loading="loading">确 定</el-button>
    </div>
    </template>
  </el-dialog>

</template>

<script>
  import PorductApi from '@/api/product.js';
  import Upload from '@/components/file/Upload.vue';
  export default {
    components: {
      Upload
    },
    data() {
      return {
        form: {
          unit_name: '',
          sort: 100,
        },
        formRules: {
          unit_name: [{
            required: true,
            message: '请输入单位名称',
            trigger: 'blur'
          }],
          sort: [{
            required: true,
            message: '分类排序不能为空'
          }, {
            type: 'number',
            message: '分类排序必须为数字'
          }]
        },
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        loading: false,
        /*是否上传图片*/
        isupload: false,
      };
    },
    props: ['open_add', 'addform'],
    created() {
      this.dialogVisible = this.open_add;
    },
    methods: {
      addvalue() {
        this.form.attribute_value.push('')
      },
      deleteattr(i) {
        this.form.attribute_value.splice(i, 1)
      },
      submit() {
        let self = this;
        let params = self.form;
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.loading = true;
            PorductApi.addUnit(params).then(data => {
              self.loading = false;
              ElMessage({
                message: '添加成功',
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

    }
  };
</script>

<style>
  .img {
    margin-top: 10px;
  }
</style>
