<template>
    <!--
            作者：luoyiming
            时间：2020-04-20
            描述：修改价格
        -->
    <el-dialog title="订单价格修改" v-model="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
               :close-on-press-escape="false" width="30%">
        <el-form size="small" :model="order" ref="order">
            <el-form-item label="订单金额" :label-width="formLabelWidth" prop="update_price"
                          :rules="[{required: true,message: ' '}]">
                <el-input type="number" v-model="order.update_price" autocomplete="off"></el-input>
                <p>最终付款价 = 订单金额 + 运费金额</p>
            </el-form-item>
            <el-form-item label="运费金额" :label-width="formLabelWidth" prop="update_express_price"
                          :rules="[{required: true,message: ' '}]">
                <el-input type="number" v-model="order.update_express_price" autocomplete="off"></el-input>
            </el-form-item>
        </el-form>
        <template #footer>
        <div class="dialog-footer">
            <el-button @click="dialogFormVisible">取 消</el-button>
            <el-button type="primary" @click="submitFunc()" :loading="loading">确 定</el-button>
        </div>
        </template>
    </el-dialog>
</template>

<script>
    import OrderApi from '@/api/order.js';
    export default {
        data() {
            return {
                order_id: 0,
                loading: false,
                /*左边长度*/
                formLabelWidth: '100px',
                /*是否显示*/
                dialogVisible: true,
                /*表单*/
                order: {
                    update_price: 0,
                    update_express_price: 0.00,
                }
            };
        },
        props: ['open_edit'],
        created() {
            this.order_id = this.$route.query.order_id;
            this.getData();
        },
        methods: {
//            获取数据
            getData(){
                let self = this;
                OrderApi.orderdetail({
                    order_id: this.order_id
                }, true).then(data =>
                {
                    self.loading = false;
                    self.order.update_price = data.data.detail.pay_price;
                }).catch(error =>
                {
                    self.loading = false;
                });
            },
            /*确认事件*/
            submitFunc(e) {
                let self = this;
                let order = this.order;
                self.$refs.order.validate((valid) =>
                {
                    if (valid) {
                        self.loading = true;

                        OrderApi.updatePrice({
                            order_id: this.order_id,
                            order: order,
                        }, true).then(data =>
                        {
                            self.loading = false;
                            ElMessage({
                                message: '修改成功',
                                type: 'success'
                            });
                            self.dialogFormVisible(true);
                        }).catch(error =>
                        {
                            self.loading = false;
                        });
                    }
                });
            },

            /*关闭弹窗*/
            dialogFormVisible() {
                this.$emit('close', {openDialog: false});
            }
        }
    };
</script>

<style></style>
