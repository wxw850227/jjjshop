<template>
  <!--
    	作者：4948286@qq.com
    	时间：2019-11-36
    	描述：添加图片类型
    -->
  <el-dialog
    title="添加分类"
    :visible.sync="dialogVisible"
    width="30%"
    :before-close="handleClose">
    <el-form :model="form" ref="form" label-width="100px" class="demo-ruleForm">
      <el-form-item
        label="分类名称"
        prop="categoryname"
        :rules="[
          { required: true, message: '名字不能为空'}
        ]"
      >
        <el-input type="age" v-model.number="form.categoryname" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="handleClose">取消</el-button>
        <el-button type="primary" @click="submitForm('form')">提交</el-button>
      </el-form-item>
    </el-form>

  </el-dialog>
</template>

<script>
import FileApi from '@/api/file.js';
export default {
  data(){
    return {
      /*是否显示*/
      dialogVisible:true,
      /*from表单模型*/
      form:{
        categoryname:''
      },
      /*分类名称*/
      categoryName:''
    }
  },
  methods:{

    /*添加图片类别*/
    addCategory: function(categoryname) {
      let self = this;
      FileApi.addCategory({group_name: categoryname},).then(data => {
          self.$message({
            message: '添加成功',
            type: 'success'
          });
          self.handleClose({status:'success'});
        })
        .catch(error => {
          self.$message.error('添加失败');
        });
    },

    /*提交*/
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.addCategory(this.form.categoryname);
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },


    /*关闭弹窗*/
    handleClose(param){
      this.dialogVisible=false;
      this.$emit('closeCategory',param);
    }
  }

}

</script>

<style>
</style>
