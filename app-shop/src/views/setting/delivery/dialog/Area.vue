<template>
  <!--
    	作者 WuYuseng
    	时间：2019-11-1
    	描述：区域选择
    -->
  <el-dialog title="添加可配送区域" :visible.sync="dialogVisible" width="700px" custom-class="area-dialog" :before-close="closeArea">
    <div class="modal-wrapper">
      <!--选择省-->
      <div class="address-item scroll-box">
        <el-checkbox :indeterminate="indeterminate" v-model="all_active" @change="allProvinceFunc">全选</el-checkbox>
        <div class="province-item" :class="{ curr: index == province_index }" v-for="(item, index) in options" :key="index" @click.self="cityShow(index)">
          <el-checkbox :indeterminate="item.indeterminate" v-model="item.checked" :disabled="item.disabled" :label="item.value" @change="handleCheckedProvinceChange(index)">
            {{ item.label }}
          </el-checkbox>
        </div>
      </div>
      <!--选择市-->
      <div class="address-item scroll-box">
        <template v-if="province_index != null">
          <el-checkbox
            :indeterminate="options[province_index].indeterminate"
            v-model="options[province_index].checked"
            :disabled="options[province_index].disabled"
            @change="allCityFunc"
          >
            全选
          </el-checkbox>
          <div class="province-item" :class="{ curr: index == province_index }" v-for="(item, index) in options[province_index]['children']" :key="index">
            <el-checkbox :label="item.value" v-model="item.checked" :disabled="item.disabled" @change="handleCheckedCityChange(index)">{{ item.label }}</el-checkbox>
          </div>
        </template>
      </div>
    </div>

    <span slot="footer" class="dialog-footer">
      <el-button @click="closeArea">取 消</el-button>
      <el-button type="primary" @click="confirmArea">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { deepClone } from '@/utils/base';
export default {
  data() {
    return {
      /*弹窗是否打开*/
      dialogVisible: false,
      /*省*/
      provinceList: [],
      /*选择省的角标*/
      province_index: null,
      /*当前选中的省*/
      activeProvinceId: [],
      /*当前对象*/
      activeModel: {},
      /*区域*/
      options: [],
      /*是否全部选中*/
      all_active: false,
      /*不确定*/
      indeterminate: false
    };
  },
  props: ['show_area', 'areaModel', 'areas'],
  created() {
    this.init();
  },
  methods: {
    /*初始化*/
    init() {
      this.dialogVisible = this.show_area;
      this.setList(this.areas);
      this.options = deepClone(this.areas);
      this.isAll();
    },

    /*设置list*/
    setList(list) {
      for (let i = 0; i < list.length; i++) {
        let item = list[i];
        if(item.checked!=true){
          item.checked = false;
        }
        if(item.indeterminate!=true){
           item.indeterminate = false;
        }
        if(item.index==null){
          item.index=[];
          item.disabled = false;
        }
        if (Object.prototype.toString.call(item.children) == '[object Array]') {
          //判断子本索引个数，和其它索引个数
          let this_num=0,other_num=0,no_num=0,count=item.children.length;
          for (let c = 0; c < count; c++) {
            let child = item.children[c];
            if(child.checked!=true){
              child.checked = false;
            }
            if(child.index==this.areaModel.index&&item.index.indexOf(this.areaModel.index)!=-1){
              child.checked=true;
              child.disabled = false;
              this_num++;
            }else if(child.index!=null&&item.index.indexOf(child.index)!=-1){
              child.checked=true;
              child.disabled = true;
              other_num++;
            }else{
              child.checked=false;
              child.disabled = false;
              no_num++;
            }

            if(this_num==count||other_num==count){
               item.checked=true;
               item.indeterminate = false;
               if(other_num==count){
                 item.disabled = true;
               }else{
                 item.disabled = false;
               }
            }else if(this_num==0||other_num==0){
              item.checked=false;
              item.indeterminate = false;
              item.disabled = false;
            }else{
              item.disabled = false;
              if(this_num>0){
                item.checked=false;
                item.indeterminate = true;
              }
            }
          }
        }
      }
    },

    /*选择全部省和市*/
    allProvinceFunc() {
      if (!this.all_active) {
        this.all_active = false;
        this.indeterminate = false;
      } else {
        this.all_active = true;
        this.indeterminate = false;
      }

      for (let i = 0; i < this.options.length; i++) {
        let item = this.options[i];
        if(item.disabled!=true){
          item.checked = this.all_active;
          if(this.all_active){
            item.index.push(this.areaModel.index);
          }else{
            let is_index=item.index.indexOf(this.areaModel.index);
            if(is_index!=-1){
              item.index.splice(is_index,1);
            }
          }
          item.indeterminate = this.indeterminate;
          if (Object.prototype.toString.call(item.children) == '[object Array]') {
            for (let c = 0; c < item.children.length; c++) {
              let child = item.children[c];
              if(child.disabled!=true){
                child.checked = this.all_active;
                if(this.all_active){
                  child.index=this.areaModel.index;
                }else{
                  child.index=null;
                }
              }
            }
          }
        }
      }
    },

    /*选择省*/
    handleCheckedProvinceChange(i) {
      this.province_index = i;
      this.options[i].indeterminate = false;
      if (this.options[i].checked&&this.options[i].disabled!=true) {
        this.options[i].checked = true;
        this.options[i].index.push(this.areaModel.index);
      } else {
        let is_index=this.options[i].index.indexOf(this.areaModel.index);
        if(is_index!=-1){
          this.options[i].index.splice(is_index,1);
        }
        if(this.options[i].disabled!=true){
          this.options[i].checked = false;
        }
      }
      if (Object.prototype.toString.call(this.options[i].children) == '[object Array]') {
        for (let c = 0; c < this.options[i].children.length; c++) {
          let child = this.options[i].children[c];
          if (child.disabled != true) {
            child.checked = this.options[i].checked;
            child.index=this.areaModel.index;
          }
        }
      }
      this.isAll();
    },

    /*选择全部的市*/
    allCityFunc() {
      let flag = this.options[this.province_index].checked;
      if (flag) {
        if(this.options[this.province_index].index.indexOf(this.areaModel.index)==-1){

          this.options[this.province_index].index.push(this.areaModel.index);
        }
      } else {
        let is_index=this.options[this.province_index].index.indexOf(this.areaModel.index);
        if(is_index!=-1){
          this.options[this.province_index].index.splice(is_index,1);
        }
      }
      this.options[this.province_index].indeterminate=false;
      for (let c = 0; c < this.options[this.province_index].children.length; c++) {
        let child = this.options[this.province_index].children[c];
        if (child.disabled != true) {
          child.checked = flag;
          if(flag){
            child.index=this.areaModel.index;
          }else{
            child.index=null;
          }
        }
      }
      this.isAll();
    },

    /*选择市*/
    handleCheckedCityChange(i) {
      let flag = this.options[this.province_index].children[i].checked;
      let indeterminate=this.options[this.province_index].children[i].indeterminate;
      let _index=this.options[this.province_index].index.indexOf(this.areaModel.index);
      let n = this.isChooseAll(this.options[this.province_index].children);
      if(flag){
        if(_index==-1){
          this.options[this.province_index].index.push(this.areaModel.index);
        }
        this.options[this.province_index].children[i].index=this.areaModel.index;
      }else{
        if(_index!=-1&&indeterminate==false){
          this.options[this.province_index].index.splice(_index,1);
        }else if(_index==-1&&indeterminate==true){
          this.options[this.province_index].index.push(this.areaModel.index);
        }
        this.options[this.province_index].children[i].index=null;
      }
      if (n == 0) {
        this.options[this.province_index].checked = false;
        this.options[this.province_index].indeterminate = false;
      } else if (n == 2) {
        this.options[this.province_index].checked = true;
        this.options[this.province_index].indeterminate = false;
      } else {
        this.options[this.province_index].checked = false;
        this.options[this.province_index].indeterminate = true;
      }
      this.isAll();
    },

    /*判断是否选中了所有的省和市*/
    isAll() {
      let n = this.isChooseAll(this.options);
      if (n == 0) {
        this.all_active = false;
        this.indeterminate = false;
      } else if (n == 2) {
        this.all_active = true;
        this.indeterminate = false;
      } else {
        this.all_active = false;
        this.indeterminate = true;
      }
    },

    /*判断列表是否全选*/
    isChooseAll(list) {
      let allcount = 0;
      if (Object.prototype.toString.call(list) == '[object Array]') {
        for (let i = 0; i < list.length; i++) {
          if(Object.prototype.toString.call(list[i].index) == '[object Array]'){
            if ((list[i]['checked']==true&&list[i].index.indexOf(this.areaModel.index)!=-1) || list[i]['indeterminate']==true) {
              allcount++;
            }
          }else{
            if (list[i]['checked']==true || list[i]['indeterminate']==true) {
              allcount++;
            }
          }
        }
        if (allcount == list.length) {
          return 2;
        } else {
          if (allcount > 0) {
            return 1;
          } else {
            return 0;
          }
        }
      } else {
        return 2;
      }
    },

    /*选择省 - 只是展示城市列表*/
    cityShow(i) {
      this.province_index = i;
    },

    /*关闭弹窗*/
    closeArea() {
      this.dialogVisible = false;
      this.$emit('closeArea', { statu: false, type: 'cancel' });
    },

    /*确认并关闭弹窗*/
    confirmArea() {
      this.dialogVisible = false;
      let obj = {
        statu: false,
        type: 'confirm',
        this_area: this.options,
        total_area: []
      };
      this.$emit('closeArea', obj);
    }


  }
};
</script>

<style>
.area-dialog .el-dialog__header {
  border-bottom: 1px solid #dddddd;
}
.area-dialog .el-dialog__body {
  padding: 20px;
}
.area-dialog .modal-wrapper {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.area-dialog .scroll-box {
  overflow-y: auto;
}
.area-dialog .address-item {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: row;
  flex-direction: row;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  border: 1px solid #eee;
  height: 400px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
.area-dialog .el-checkbox {
  padding: 8px 10px 8px 22px;
  min-height: 20px;
  box-sizing: border-box;
  margin-right: 0;
  position: relative;
}
.area-dialog .province-item {
  cursor: pointer;
}
.area-dialog .province-item:hover,
.area-dialog .province-item.curr {
  background: #eeeeee;
}
/* .area-dialog .address-item .el-checkbox {
  display: block;
  margin-right: 0;
}

.area-dialog .address-item .el-checkbox-item:hover,
.area-dialog .address-item .active-checkbox {
  background: #eeeeee;
}
.area-dialog .address-item .el-checkbox__input {
  position: absolute;
  left: 10px;
  top: 10px;
}
.area-dialog .address-item .el-checkbox__label {
  display: block;
} */
</style>
