<template>
  <!--
          作者：luoyiming
          时间：2019-10-26
          描述：门店添加
      -->
  <div class="product-add" v-loading="loading">
    <!--form表单-->
    <el-form size="small" ref="form" :model="form" label-width="100px">
      <!--添加门店-->
      <div class="common-form">商品评价详情</div>
      <el-form-item label="用户">
        <p>{{ form.user.nickName }}</p>
      </el-form-item>
      <el-form-item label="商品名称">
        <p>{{ form.product.product_name | isNull }}</p>
      </el-form-item>
      <el-form-item label="商品图片">
        <img :src="path" width="120px;" />
      </el-form-item>
      <el-form-item label="评论时间">
        <p>{{ form.create_time }}</p>
      </el-form-item>
      <el-form-item label="评价图片">
        <div v-if="form.image.length > 0" v-for="(item, index) in form.image" :key="index"><img v-img-url="item.file_path" alt="" width="60" /></div>
      </el-form-item>
      <el-form-item label="评分" v-model="form.score">
        <p v-if="form.score == 10">好评</p>
        <p v-if="form.score == 20">中评</p>
        <p v-if="form.score == 30">差评</p>
      </el-form-item>
      <el-form-item label="评价内容">
        <div>{{ form.content }}</div>
      </el-form-item>
      <el-form-item label="排序">
        <p>{{ form.sort }}</p>
      </el-form-item>
      <el-form-item label="审核">
        <el-radio-group v-model="form.status">
          <el-radio :label="1">通过</el-radio>
          <el-radio :label="2">不通过</el-radio>
        </el-radio-group>
      </el-form-item>
      <!--提交-->
      <div class="common-button-wrapper">
        <el-form-item><el-button type="primary" @click="onSubmit">提交</el-button></el-form-item>
      </div>
    </el-form>
  </div>
</template>

<script>
import PorductApi from '@/api/product.js';
export default {
  components: {
  },
  data() {
    return {
      is_comment: false,
      /*商品图片*/
      path: '',
      /*评论图片*/
      comment_img: '',
      /*form表单数据*/
      form: {
        user: {},
        product: {}
      },
      loading: true
    };
  },
  created() {
    /*获取列表*/
    this.getComment();
  },
  methods: {

    /*获取评论*/
    getComment() {
      let self = this;
      let comment_id = this.$route.query.comment_id;
      PorductApi.getComment(
        {
          comment_id: comment_id
        },
        true
      )
        .then(data => {
          self.loading = false;
          self.form = data.data.data;
          self.path = data.data.data.product.image[0].file_path;
          self.comment_id = data.data.data.comment_id;
        })
        .catch(error => {});
    },

    /*提交*/
    onSubmit() {
      let self = this;
      let params = {
        comment_id:this.form.comment_id,
        status:this.form.status
      }
      PorductApi.editComment(params, true)
        .then(data => {
          if (data.code == 1) {
            self.$message({
              message: '恭喜你，操作成功',
              type: 'success'
            });
            self.$router.push('/product/comment/Evaluation');
          } else {
            self.$message.error('错了哦，这是一条错误消息');
          }
        })
        .catch(error => {});
    }
  }
};
</script>

<style scoped="">
.edit_container {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

.ql-editor {
  height: 400px;
}
</style>
