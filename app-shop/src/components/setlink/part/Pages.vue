<template>
    <el-select v-model="activePage" placeholder="请选择" class="percent-w100" @change="changeFunc" value-key="url">
        <el-option v-for="item in pages" :key="item.url" :label="item.name" :value="item"></el-option>
    </el-select>
</template>

<script>
    export default{
        data(){
            return {
                /*页面数据*/
                pages: [
                    {
                        url: 'pages/index/index',
                        type: '页面',
                        name: '首页'
                    },
                    {
                        url: 'pages/product/category',
                        name: '分类',
                        type: '页面',
                    },
                    {
                        url: 'pages/cart/cart',
                        name: '购物车',
                        type: '页面',
                    },
                    {
                        url: 'pages/user/index/index',
                        name: '我的',
                        type: '页面',
                    }],
                /*选中的值*/
                activePage: {}
            }
        },
        methods: {
            /*选中的值*/
            changeFunc(e){
                this.$emit('changeData', e);
            }
        }
    }
</script>

<style>
</style>
