<template>
  <!--
    	描述：商品-属性库-添加属性
    -->
  <el-dialog title="添加属性" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form size="small" :model="form" :rules="formRules" ref="form">
      <el-form-item label="排序" prop="sort" :label-width="formLabelWidth">
        <el-input v-model.number="form.sort" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="属性名称" prop="attribute_name" :label-width="formLabelWidth">
        <el-input v-model="form.attribute_name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="属性值" prop="attribute_value" :label-width="formLabelWidth">
        <div class="mb16 d-s-c" v-for="(item,index) in form.attribute_value" :key='index'>
          <el-input  v-model="form.attribute_value[index]" autocomplete="off"></el-input>
          <el-button style="margin-left: 20px;" type="danger" size="small" @click="deleteattr(index)">删除</el-button>
        </div>
        <el-button type="text" @click="addvalue">+添加属性名</el-button>
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
          attribute_id:'',
          attribute_name: '',
          sort: 100,
          attribute_value: []
        },
        formRules: {
          attribute_name: [{
            required: true,
            message: '请输入属性名称',
            trigger: 'blur'
          }],
          attribute_value: [{
            required: true,
            message: '请输入属性值',
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
    props: ['open_edit', 'editform'],
    created() {
      this.dialogVisible = this.open_edit;
      this.form.attribute_value = this.editform.attribute_value;
      this.form.attribute_id = this.editform.attribute_id;
      this.form.attribute_name = this.editform.attribute_name;
      this.form.sort = this.editform.sort;
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
            PorductApi.editAttribute(params).then(data => {
              self.loading = false;
              ElMessage({
                message: '修改成功',
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
