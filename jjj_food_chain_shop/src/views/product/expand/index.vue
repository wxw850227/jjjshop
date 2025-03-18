<template>
  <!--
      	描述：商品-商品扩展
      -->
  <div class="common-seach-wrap">
    <!--规格库-->
    <spec v-if="activeName=='spec'"></spec>
    <!--属性库-->
    <attr v-if="activeName=='attr'"></attr>
    <!--加料库-->
    <unit v-if="activeName=='unit'"></unit>
    <!--单位库-->
    <feed v-if="activeName=='feed'"></feed>
  </div>
</template>
<script>
  import { reactive, toRefs, defineComponent } from 'vue';
  import { useUserStore } from "@/store";
  import spec from './spec/index.vue';
  import attr from './attr/index.vue';
  import unit from './unit/index.vue';
  import feed from './feed/index.vue';
  export default defineComponent({
    components: {
      spec,
      attr,
      unit,
      feed,
    },
    setup() {
      const { bus_emit, bus_off, bus_on } = useUserStore();
       const state = reactive({
         bus_emit,
        bus_off,
        bus_on,
       /*是否加载完成*/
        loading: true,
        form: {},
        /*参数*/
        param: {},
        /*当前选中*/
        activeName: 'spec',
        /*切换数组原始数据*/
      sourceList: [
        {
            key: 'spec',
            value: '规格库',
            path:'/product/expand/spec/index'
        },
        {
            key: 'attr',
            value: '属性库',
            path:'/product/expand/attr/index'
        },
        {
            key: 'feed',
            value: '加料库',
            path:'/product/expand/feed/index'
        },
        {
            key: 'unit',
            value: '单位库',
            path:'/product/expand/unit/index'
        },
      ],
        /*权限筛选后的数据*/
        tabList:[],
         paramsFlag: false
       })
       return{
     ...toRefs(state),

   };

     
    },
    created() {
          this.tabList = this.authFilter();
      if(this.tabList.length>0){
        this.activeName=this.tabList[0].key;
      }

      if (this.$route.query.type != null) {
        this.activeName = this.$route.query.type;
      }

      /*监听传插件的值*/
      this.bus_on('activeValue', res =>
      {
        this.activeName = res;
      });
      //发送类别切换
      let params = {
          active: this.activeName,
          list: this.tabList,
          tab_type: 'expand',
      }
      this.bus_emit('tabData', params);

    
    },
    beforeUnmount() {
     //发送类别切换
     this.bus_emit('tabData', { active: null, tab_type:'expand',list: [] });
     this.bus_off('activeValue');
    },
    methods: {
      /*权限过滤*/
      authFilter() {
        let list = [];
        for (let i = 0; i < this.sourceList.length; i++) {
          let item = this.sourceList[i];
          if (this.$filter.isAuth(item.path)) {
            list.push(item);
          }
        }
        return list;
      },
    
    }
  });
</script>

<style>
  .operation-wrap {
    height: 124px;
    border-radius: 8px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 30px 30px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    overflow: hidden;
    background: #909399;
    background-size: 100% 100%;
    color: #fff;
  }
</style>
