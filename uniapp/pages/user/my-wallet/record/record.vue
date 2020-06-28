<template>
    <view class="p-0-30">
        <view class="d-b-c border-b p-20-0" v-for="(item,index) in tableData" :key="index">
            <view class="d-s-s f-w d-c flex-1">
                <text class="30">充值</text>
                <text class="gray9 f22">2019-12-09 16:00:00</text>
            </view>
            <view class="red">
                +1233.00元
            </view>
        </view>
    </view>
</template>

<script>
    export default {
        data() {
            return {
                /*数据列表*/
                tableData: {},
                /*最后一页码数*/
                last_page: 0,
                /*当前页面*/
                page: 1,
                /*每页条数*/
                list_rows: 10,
            }
        },
        mounted(){
            /*获取数据*/
            this.getData();
        },
        methods: {
            /*获取数据*/
            getData(){
                let self = this;
                self._get('recharge.order/lists', {}, function (data)
                {

                    self.tableData = data.data.list.data;
                    self.last_page = res.data.list.last_page
                });
            },
        }
    }
</script>

<style>

</style>
