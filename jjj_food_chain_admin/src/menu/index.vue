<template>
    <div>kkk</div>
</template>
  <script>
  import { reactive, toRefs, defineComponent } from "vue";
  export default defineComponent({
    components: {},
      setup() {
        const state = reactive({
          value1:null,
        })
        return {
        ...toRefs(state),
      };
      }
  })
  </script>
  <style scoped>
  
  </style>
  