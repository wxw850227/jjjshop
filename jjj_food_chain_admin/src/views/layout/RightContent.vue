<template>
	<div class="right-content">
    <!--头部-->
    <Heads></Heads>
    <!--内容区域-->
    <div class="right-content-box">
      <div class="subject-wrap">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
import {
	defineComponent,
} from 'vue';
import Heads from '@/views/layout/Head.vue';
export default defineComponent({
	components: {
		Heads
	},
});
</script>

<style>
</style>
