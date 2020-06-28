<template>
  <div id="app"><router-view v-if="isRouterAlive" /></div>
</template>

<script>
export default {
  name: 'App',
  provide() {
    return {
      reload: this.reload
    };
  },
  data() {
    return {
      isRouterAlive: true
    };
  },
  methods: {
    reload() {
      this.isRouterAlive = false;
      this.$nextTick(function() {
        this.isRouterAlive = true;
      });
    }
  }
};
</script>

<style lang="scss">
/*cover*/
.el-button,
.el-table,
.el-form-item__label,
.el-form-item__content,
.el-input,
.el-radio,
.el-radio--medium.is-bordered .el-radio__label,
.el-radio__label,
.el-select-dropdown__item,
.el-checkbox__label,
.el-pagination__sizes .el-input .el-input__inner,
.el-pager li,
.el-divider__text, .el-link,
.el-picker-panel__shortcut,
.el-dialog__body,
.el-tree-node__label,
.el-tabs__item{
  font-size: 12px;
}

.el-tabs__item{ font-weight: bold;}

body .el-table th.gutter{
   display: table-cell!important;
}
</style>
