<template>
  <div class="right-content pr">
    <!--头部-->
    <Head></Head>
    <!--内容区域-->
    <div class="right-content-box">
      <div class="subject-wrap">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
  import Head from '@/views/layout/Head.vue';
  export default {
    components: {
      Head
    },
    data() {
      return {};
    },
    created() {},
    methods: {}
  };
</script>

<style>
</style>
