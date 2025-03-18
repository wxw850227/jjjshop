<template>
  <!--
    	描述：商品-加料库-添加加料
    -->
  <el-dialog title="添加加料" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form size="small" :model="form" :rules="formRules" ref="form">
      <el-form-item label="排序" prop="sort" :label-width="formLabelWidth">
        <el-input type="text" v-model.number="form.sort" ></el-input>
      </el-form-item>
      <el-form-item label="加料名称" prop="feed_name" :label-width="formLabelWidth">
        <el-input type="text" v-model="form.feed_name"></el-input>
      </el-form-item>
      <el-form-item label="价格" prop="price" :label-width="formLabelWidth">
        <el-input type="number" v-model="form.price" ></el-input>
      </el-form-item>
    </el-form>
    <template #footer>
    <div  class="dialog-footer">
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
          feed_id:'',
          feed_name: '',
          sort: 100,
          price:'',
          feed_value: []
        },
        formRules: {
          feed_name: [{
            required: true,
            message: '请输入加料名称',
            trigger: 'blur'
          }],
          price: [{
            required: true,
            message: '请输入价格',
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
      this.form.feed_id = this.editform.feed_id;
      this.form.feed_name = this.editform.feed_name;
      this.form.price = this.editform.price;
      this.form.sort = this.editform.sort;
    },
    methods: {
      addvalue() {
        this.form.feed_value.push('')
      },
      deleteattr(i) {
        this.form.feed_value.splice(i, 1)
      },
      submit() {
        let self = this;
        let params = self.form;
        self.$refs.form.validate((valid) => {
          if (valid) {
            self.loading = true;
            PorductApi.editFeed(params).then(data => {
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
