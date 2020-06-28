<template>
  <!--
      作者 WuYuseng
      时间：2019-10-26
      描述：设置-消息设置-会员通知
  -->
  <div>
    <el-tabs v-model="activeName">
      <el-tab-pane label="会员通知" name="first">
      </el-tab-pane>
      <el-tab-pane label="商家通知" name="second">
      </el-tab-pane>
      <el-tab-pane label="短信设置" name="third">
      </el-tab-pane>
    </el-tabs>

    <Message v-if="activeName=='first'" :message_to="10"></Message>
    <Message v-if="activeName=='second'" :message_to="20"></Message>
    <Sms v-if="activeName=='third'"></Sms>

  </div>
</template>
<script>
  import Sms from './Sms';
  import Message from './Message';
  export default {
    components: {
      /*编辑组件*/
      Message,
      Sms
    },
    data() {
      return {
        /*参数*/
        message_to: 10,
        /*当前选中*/
        activeName: 'first'
      };
    },
    created() {
      if(this.$route.query.type!=null){
        this.activeName=this.$route.query.type;
      }

    },
    methods: {

    }
  };
</script>
