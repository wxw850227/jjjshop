<template>
  <el-dialog title="查看物流" ="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false"
    :close-on-press-escape="false" width="900px">
    <el-timeline :reverse="reverse">
      <el-timeline-item v-for="(activity, index) in activities" :key="index" :color="index==0?'#2ECC40':''" :timestamp="activity.time">
        {{activity.context}}
      </el-timeline-item>
    </el-timeline>
  </el-dialog>
</template>

<script>
  import OrderApi from '@/api/order.js';
  export default {
    data() {
      return {
        /*是否显示*/
        dialogVisible: false,
        /*结果类别*/
        type: 'error',
        /*传出去的参数*/
        params: null,
        reverse: false,
        order_id:0,
        activities: []
      }
    },
    props: ['isLogistics','logisticsData'],
    watch: {
      isLogistics: function(n, o) {
        if (n != o) {
          if (n) {
            this.dialogVisible = n;
            this.type = 'error';

          }
        }
      },
      logisticsData: function(n, o) {
        if (n != o) {
          if (n) {
            this.activities = n;
            console.log(this.activities)

          }
        }
      }
    },
    methods: {
      getData(){
        let self=this;
        let Params={
            order_id:self.order_id,
          }
        OrderApi.orderlist(Params, true)
          .then(res => {

          })
          .catch(error => {});
      },
      /*关闭弹窗*/
      dialogFormVisible() {
        this.$emit('closeDialog', {
          type: this.type,
          openDialog: false,
          params: this.params
        });
      },
    }
  }
</script>

<style>
  .el-step__main .el-step__description {
    padding-bottom: 10px;
  }
</style>
