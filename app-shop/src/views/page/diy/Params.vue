<template>
  <!--
    	作者：wangxw
    	时间：2019-15-05
    	描述：diy参数
    -->
  <div id="diy-editor" ref="diy-editor" class="diy-editor form-horizontal" :style="{ visibility: form.selectedIndex != -1 ? 'visible' : 'hidden' }">


    <template v-show="diyData.items.length && form.curItem">
      <!--顶部设置-->
      <template  v-if="form.curItem.type == 'page'">
        <Setpages :curItem="form.curItem"></Setpages>
      </template>
      <!--搜索栏-->
      <template  v-if="form.curItem.type == 'search'">
        <Search :curItem="form.curItem"></Search>
      </template>
      <!--图片轮播-->
      <template  v-if="form.curItem.type == 'banner'">
        <Banner :curItem="form.curItem" :selectedIndex="form.selectedIndex"></Banner>
      </template>
      <!--图片-->
      <template v-if="form.curItem.type == 'imageSingle'">
        <ImageSingle :curItem="form.curItem" :selectedIndex="form.selectedIndex"></ImageSingle>
      </template>
      <!--图片橱窗-->
      <template  v-if="form.curItem.type == 'window'">
        <Window :curItem="form.curItem" :selectedIndex="form.selectedIndex"></Window>
      </template>
      <!--视频组件-->
      <template  v-if="form.curItem.type == 'video'">
        <Video :curItem="form.curItem" :selectedIndex="form.selectedIndex"></Video>
      </template>
      <!--公告组-->
      <template  v-if="form.curItem.type == 'notice'">
        <Notice :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></Notice>
      </template>
      <!--导航组-->
      <template  v-if="form.curItem.type == 'navBar'">
        <NavBar :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></NavBar>
      </template>
      <!--导航组-->
      <template  v-if="form.curItem.type == 'product'">
        <Product :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></Product>
      </template>
      <!--导航组-->
      <template  v-if="form.curItem.type == 'coupon'">
        <Coupon :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></Coupon>
      </template>
      <!--客服-->
      <template  v-if="form.curItem.type == 'service'">
        <Service :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></Service>
      </template>
      <!--富文本-->
      <template  v-if="form.curItem.type == 'richText'">
        <RichText :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></RichText>
      </template>
      <!--辅助空白-->
      <template  v-if="form.curItem.type == 'blank'">
        <Blank :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></Blank>
      </template>
      <!--辅助线-->
      <template  v-if="form.curItem.type == 'guide'">
        <Guide :curItem="form.curItem" :opts="opts" :selectedIndex="form.selectedIndex"></Guide>
      </template>

    </template>

    <!--上传图片-->
    <Upload v-if="isupload" :isupload="isupload" :config="{ total: 3 }" @returnImgs="returnImgsFunc"></Upload>
    <!--选择商品-->
    <ProductSelect v-if="isproduct" :isproduct="isproduct" @closeDialog="closeProductDialogFunc($event)">产品列表弹出层</ProductSelect>
  </div>
</template>

<script>
  import Setpages from './params/Setpages.vue';
  import Search from './params/Search.vue';
  import Banner from './params/Banner.vue';
  import ImageSingle from './params/ImageSingle.vue';
  import Window from './params/Window.vue';
  import Video from './params/Video.vue';
  import Notice from './params/Notice.vue';
  import NavBar from './params/NavBar.vue';
  import Product from './params/Product.vue';
  import Service from './params/Service.vue';
  import RichText from './params/RichText.vue';
  import Blank from './params/Blank.vue';
  import Guide from './params/Guide.vue';
  import Upload from '@/components/file/Upload';
  import ProductSelect from '@/components/product/Product';

  export default {
    components: {
      /*顶部设置*/
      Setpages,
      /*搜索组件*/
      Search,
      /*图片轮播组件*/
      Banner,
       /*图片组件*/
      ImageSingle,
     /*图片橱窗*/
      Window,
      /*视频*/
      Video,
      /*公告组*/
      Notice,
      /*导航组*/
      NavBar,
      /*商品组*/
      Product,
      /*客服*/
      Service,
      /*富文本*/
      RichText,
      /*辅助空白*/
      Blank,
      /*辅助线*/
      Guide,
      /*上传图片*/
      Upload,
      /*商品选择*/
      ProductSelect,
    },
    data() {
      return {
        /*是否上传图片*/
        isupload: false,
        /*图片当前对象*/
        imgModel:null,
        /*是否打开产品弹出层*/
        isproduct: false,
      };
    },
    props: ['form', 'defaultData', 'diyData', 'opts'],
    methods: {
      /**
       * 编辑器：添加data元素
       */
      onEditorAddData: function() {
        let self = this;
        // 新增data数据
        var newDataItem = self.defaultData[self.form.curItem.type].data[0];
        self.form.curItem.data.push(newDataItem);
      },
      /**
       * 编辑器：重置颜色
       * @param holder
       * @param attribute
       * @param color
       */
      onEditorResetColor: function(holder, attribute, color) {
        holder[attribute] = color;
      },
      /**
       * 编辑器：删除data元素
       * @param index
       * @param selectedIndex
       */
      onEditorDeleleData: function(index, selectedIndex) {
        let self = this;
        if (self.diyData.items[selectedIndex].data.length <= 1) {
          self.$message({
            message: '至少保留一个',
            type: 'error'
          });
          return false;
        }
        self.diyData.items[selectedIndex].data.splice(index, 1);
      },
      /**
       * 编辑器：选择图片
       * @param source
       * @param index
       */
      onEditorSelectImage: function(index, imgUrl) {
        this.isupload=true;
        this.imgModel={
          index:index,
          imgUrl:imgUrl
        }
      },

      /*上传图片*/
      returnImgsFunc(e){
        if (e != null) {
          this.imgModel.index[ this.imgModel.imgUrl]=e[0]['file_path'];
        }
        this.isupload = false;
      },

      /*产品列表弹出层*/
      openProduct() {
        this.isproduct = true;
      },

      /*关闭弹窗*/
      closeProductDialogFunc(e) {
        if(this.form.curItem == null){
          return;
        }
        this.isproduct = e.openDialog;
        if (e.type == 'success') {
          //去重
          let canAdd = true;
          for(var i=0;i<this.form.curItem.data.length;i++){
            if(this.form.curItem.data[i].product_id == e.params.product_id){
              canAdd = false;
              break;
            }
          }
          if(canAdd){
            this.form.curItem.data.push(e.params);
          }
        }
      },

    }
  };
</script>

<style>
.param-img-item { position: relative; padding: 10px; margin-bottom: 10px; border: 1px solid #EEEEEE; line-height:20px;}
.param-img-item .delete-box{ position: absolute; top: 10px; right:10px; font-size: 20px; cursor: pointer;color:#CCCCCC;}
.param-img-item .delete-box:hover{ color: rgb(255, 51, 0);}
.param-img-item .pic img{ width:200px; height: 100px; margin: 0 auto;}
.param-img-item .icon img{ width:100px; height: 100px; margin: 0 auto;}
.param-img-item .url-box{ display: flex; justify-content: flex-start; line-height: 40px;}
.param-img-item .url-box .key-name{ display: block; width: 80px;}
.param-img-item .url-box .el-input{ flex: 1;}
</style>
