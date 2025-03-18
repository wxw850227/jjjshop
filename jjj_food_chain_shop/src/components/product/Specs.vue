<template>
  <!--
    	作者：luoyiming
    	时间：2020-06-28
    	描述：组件-选择规格
    -->
<el-dialog title="选择规格" ="dialogVisible" @close="dialogFormVisible" :close-on-click-modal="false" :close-on-press-escape="false" width="600px">

    <!--内容-->
    <div>
      <div class="table-wrap">
        <el-table size="small" :data="specsList" border style="width: 100%" highlight-current-row v-loading="loading" @selection-change="tableCurrentChange">
          <el-table-column  label="产品/规格">
            <template #default="scope">
              {{scope.row.spec_name}}
            </template>
          </el-table-column>
          <el-table-column type="selection" :selectable="selectableFunc" width="44"></el-table-column>
        </el-table>
      </div>

    </div>
    <template #footer>
      <div class="dialog-footer">
        <el-button size="small" @click="dialogVisible=false">取 消</el-button>
        <el-button size="small" type="primary" @click="addClerk">确 定</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script>
  import PorductApi from '@/api/product.js';
  export default {
    data() {
      return {
        /*是否加载完成*/
        loading: true,
        //规格列表
        specsList: [],
        /*多选的*/
        multipleSelection: [],
        /*左边长度*/
        formLabelWidth: '120px',
        /*是否显示*/
        dialogVisible: false,
        /*结果类别*/
        type:'error',
        /*传出去的参数*/
        params:null
      };
    },
    props: ['isspecs','productId','excludeIds','islist'],
    watch:{
      isspecs:function(n,o){
        if(n!=o){
          if(n){
            this.specsList=[];
            this.dialogVisible=n;
            this.type='error';
            this.params=null;
            this.getData();
          }
        }
      }
    },
    created() {

    },
    methods: {

      /*是否可以勾选*/
      selectableFunc(e){
        if(typeof e.noChoose !=='boolean'){
          return true;
        }
        return e.noChoose;
      },

      /*获取规格列表*/
      getData() {
        let self = this;
        PorductApi.chooseSpec({product_id:self.productId}, true)
          .then(res => {
            if (res.code == 1) {
              self.loading = false;
               console.log(self.excludeIds);
              /*判断是否需要去重*/
              if(self.excludeIds&&typeof(self.excludeIds)!='undefined'&&self.excludeIds.length>0){
                res.data.specList.forEach(item=>{
                  console.log(item.product_sku_id);
                  if(self.excludeIds.indexOf(item.product_sku_id)>-1){
                    item.noChoose=false;
                  }else{
                    item.noChoose=true;
                  }
                });
              }
              self.specsList = res.data.specList;
            }
          })
          .catch(error => {});
      },

      //点击确定
      addClerk() {
        let self = this;
        let params = null;
        let type = 'success';
        if (self.multipleSelection.length < 1) {
          self.$message({
            message: '请至少选择一件产品商品！',
            type: 'error'
          });
          return;
        }
        if (self.islist&&typeof(self.islist)!='undefined') {
          params = self.multipleSelection;
        } else {
          params = self.multipleSelection[0];
        }
        self.params=params;
        self.type='success';
        self.dialogVisible=false;
      },

      /*关闭弹窗*/
      dialogFormVisible() {
        this.$emit('close', {
          type: this.type,
          open: false,
          params: this.params
        });
      },

      /*监听复选按钮选中事件*/
      tableCurrentChange(val) {
        this.multipleSelection = val;
      }
    }
  };
</script>

<style>
</style>
