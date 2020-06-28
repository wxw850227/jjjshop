<template>
  <!--
          作者：luoyiming
          时间：2020-01-08
          描述：超链接选择
      -->
  <el-dialog title="超链接设置" :visible.sync="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false" :close-on-press-escape="false">
    <!--内容-->
    <el-tabs type="border-card" v-model="activeName">
      <!--页面-->
      <el-tab-pane label="页面" name="pages"><Pages v-if="activeName == 'pages'" @changeData="activeDataFunc"></Pages></el-tab-pane>
      <el-tab-pane label="产品" name="product"><Product v-if="activeName == 'product'" @changeData="activeDataFunc"></Product></el-tab-pane>
    </el-tabs>
    <div slot="footer" class="dialog-footer d-b-c">
      <div>
        当前链接：
        <template v-if="activeData != null">
          {{ activeData.type + '   ' + activeData.name + '   ' + activeData.url }}
        </template>
        <template v-else>
          暂无
        </template>
      </div>
      <div>
        <el-button size="small" @click="dialogFormVisible(false)">取 消</el-button>
        <el-button size="small" type="primary" @click="dialogFormVisible(true)">确 定</el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
import Pages from './part/Pages.vue';
import Product from './part/Product.vue';
export default {
  components: {
    Pages,
    Product
  },
  data() {
    return {
      /*是否显示*/
      dialogVisible: true,
      /*选中的链接*/
      activeData: null,
      activeName: 'pages'
    };
  },
  props: ['is_linkset'],
  created() {
    this.dialogVisible = this.is_linkset;
  },
  methods: {
    /*关闭弹窗*/
    dialogFormVisible(e) {
      if (e) {
        console.log(this.activeData);
        this.$emit('closeDialog', this.activeData);
      } else {
        this.$emit('closeDialog', {url:null,type:'',name:''});
      }
    },

    /*页面返回值*/
    activeDataFunc(e) {
      this.activeData = e;
    }
  }
};
</script>

<style>
.marketing-box .el-tabs__item {
  font-size: 12px;
}
</style>
