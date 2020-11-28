<template>
  <!--
      	作者：luoyiming
      	时间：2020-06-01
      	描述：插件中心-文章-类别
      -->
    <div>
        <div class="common-level-rail">
            <el-button size="small" type="primary" @click="addCategory">添加分类</el-button>
        </div>
        <div class="table-wrap">
            <el-table :data="categoryData" style="width: 100%" v-loading="loading">
                <el-table-column prop="category_id" label="分类ID" width="180"></el-table-column>
                <el-table-column prop="name" label="	分类名称"></el-table-column>
                <el-table-column prop="sort" label="分类排序"></el-table-column>
                <el-table-column prop="create_time" label="添加时间" width="180"></el-table-column>
                <el-table-column prop="name" label="操作" width="150">
                    <template slot-scope="scope">
                        <el-button @click="editCategory(scope.row)" type="text" size="small">编辑</el-button>
                        <el-button @click="deleteCategory(scope.row)" type="text" size="small">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <!--添加-->
            <Add v-if="open_add" :open_add="open_add" @closeDialog="closeDialogFunc($event, 'add')"></Add>

            <!--编辑-->
            <Edit v-if="open_edit" :open_edit="open_edit" :form="userModel"
                  @closeDialog="closeDialogFunc($event, 'edit')"></Edit>

        </div>
    </div>
</template>

<script>
    import ArticleApi from '@/api/article.js';
    import Edit from './part/edit.vue';
    import Add from './part/add.vue';
    import {deepClone} from '@/utils/base.js';
    export default {
        components: {
            /*编辑组件*/
            Edit,
            Add
        },
        data() {
            return {
                /*分类*/
                categoryData: [],
                /*是否打开添加弹窗*/
                open_add: false,
                /*是否打开编辑弹窗*/
                open_edit: false,
                /*当前编辑的对象*/
                userModel: {},
                commentData: [],
                /*是否加载完成*/
                loading: true,
            }
        },
        created() {
            /*获取文章列表*/
            this.getTableList();

        },
        methods: {
            /*获取文章列表*/
            getTableList() {
                let self = this;
                let Params = {};
                ArticleApi.getCategory(Params, true)
                    .then(data =>
                    {
                        self.loading = false;
                        self.categoryData = data.data.category;
                    })
                    .catch(error =>
                    {
                      self.loading = false;
                    });
            },


            /*删除文章分类*/
            deleteCategory(row) {
                let self = this;
                self.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() =>
                {
                    self.loading = true;
                    ArticleApi.deleteCategory({
                        category_id: row.category_id
                    }, true)
                        .then(data =>
                        {
                            self.loading = false;
                            self.$message({
                                message: data.msg,
                                type: 'success'
                            });
                            self.getTableList();

                        })
                        .catch(error =>
                        {
                            self.loading = false;
                        });

                }).catch(() =>
                {

                });
            },
            handleClick(tab, event) {

            },

            /*打开分类添加*/
            addCategory() {
                this.open_add = true;
            },

            /*打开分类编辑*/
            editCategory(item) {
                this.userModel = deepClone(item);
                this.open_edit = true;
            },

            /*关闭弹窗*/
            closeDialogFunc(e, f) {
                if (f == 'add') {
                    this.open_add = e.openDialog;
                    if (e.type == 'success') {
                        this.getTableList();
                    }
                }
                if (f == 'edit') {
                    this.open_edit = e.openDialog;
                    if (e.type == 'success') {
                        this.getTableList();
                    }
                }
            },
        }
    }
</script>

<style>
</style>
