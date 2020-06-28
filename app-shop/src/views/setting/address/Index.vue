<template>
  <!--
    	作者：wangxw
    	时间：2019-10-26
    	描述：设置-退货地址
    -->
    <div class="user">
        <div class="common-form">退货地址列表</div>
        <!--退货地址-->
        <div class="common-level-rail">
            <el-button size="small" icon="el-icon-plus" type="primary" @click="addClick" v-auth="'/setting/address/add'">添加</el-button>
        </div>

        <!--内容-->
        <div class="product-content">
            <div class="table-wrap">
                <el-table :data="tableData" border style="width: 100%" v-loading="loading">
                    <el-table-column prop="address_id" label="地址ID"></el-table-column>
                    <el-table-column prop="name" label="收货人姓名"></el-table-column>
                    <el-table-column prop="phone" label="联系电话"></el-table-column>
                    <el-table-column prop="detail" label="详细地址"></el-table-column>
                    <el-table-column prop="sort" label="排序"></el-table-column>
                    <el-table-column prop="create_time" label="添加时间"></el-table-column>
                    <el-table-column fixed="right" label="操作" width="90px">
                        <template slot-scope="scope">
                            <el-button @click="editClick(scope.row)" type="text" size="small"  v-auth="'/setting/address/edit'">编辑</el-button>
                            <el-button @click="deleteClick(scope.row)" type="text" size="small" v-auth="'/setting/address/delete'">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <!--分页-->
            <div class="pagination">
                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" background
                               :current-page="curPage"
                               :page-size="pageSize"
                               layout="total, prev, pager, next, jumper"
                               :total="totalDataNumber">
                </el-pagination>
            </div>
        </div>

    </div>
</template>

<script>
    import SettingApi from '@/api/setting.js';

    export default {
        data() {
            return {
                /*是否加载完成*/
                loading: true,
                /*列表数据*/
                tableData: [],
                /*一页多少条*/
                pageSize: 20,
                /*一共多少条数据*/
                totalDataNumber: 0,
                /*当前是第几页*/
                curPage: 1,
                /*横向表单数据模型*/
                formInline: {
                    user: '',
                    region: ''
                },
                /*是否打开添加弹窗*/
                open_add: false,
                /*是否打开编辑弹窗*/
                open_edit: false,
                /*当前编辑的对象*/
                userModel: {}
            };
        },
        created() {

            /*获取列表*/
            this.getData();

        },
        methods: {

            /*选择第几页*/
            handleCurrentChange(val) {
                let self = this;
                self.curPage = val;
                self.loading = true;
                self.getData();
            },

            /*每页多少条*/
            handleSizeChange(val) {
                this.curPage = 1;
                this.pageSize = val;
                this.getData();
            },

            /*获取列表*/
            getData() {
                let self = this;
                let Params = {};
                Params.page = self.curPage;
                Params.list_rows = self.pageSize
                SettingApi.addressList(Params, true)
                    .then(data =>
                    {
                        self.loading = false;
                        self.tableData = data.data.list.data;
                        self.totalDataNumber = data.data.list.total
                    })
                    .catch(error =>
                    {

                    });
            },
            /*打开添加*/
            addClick() {
                this.$router.push('/setting/address/add');
            },

            /*打开编辑*/
            editClick(item) {
                let self = this;
                this.$router.push({
                    path: '/setting/address/edit',
                    query: {
                        address_id: item.address_id
                    }
                })
            },


            /*删除用户*/
            deleteClick(row) {
                let self = this;
                self.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() =>
                {
                    SettingApi.deleteAddress({
                        address_id: row.address_id
                    }, true)
                        .then(data =>
                        {
                            self.$message({
                                message: data.msg,
                                type: 'success'
                            });
                            self.getData();

                        })
                        .catch(error =>
                        {

                        });

                }).catch(() =>
                {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            }

        }
    };
</script>

<style></style>
