<template>
  <!--
      	作者：luoyiming
      	时间：2019-12-25
      	描述：运营-文章
      -->
    <div class="user">
        <div class="common-seach-wrap">
            <el-tabs v-model="activeName">
                <el-tab-pane label="文章管理" name="article">
                    <Article v-if="activeName=='article'"></Article>
                </el-tab-pane>
                <el-tab-pane label="分类管理" name="category">
                    <Category v-if="activeName=='category'"></Category>
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>

<script>
    import ArticleApi from '@/api/article.js';
    import Article from './article/index.vue';
    import Category from './category/index.vue';
    export default {
        components: {
            Article,
            Category,
        },
        data() {
            return {
                activeName: 'article',
                /*是否加载完成*/
                loading: true,
            }
        },
        created() {


        },
        methods: {}
    }
</script>
