<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件
    -->
  <div class="diy-phone-container">
    <div class="diy-phone-item" v-if="diyData.page">
      <div class="phone-top" @click.stop="onEditer('page')" :style="'background:' + diyData.page.style.titleBackgroundColor">
        <div class="status-bar">
          <span><Icon :iconname="'#icon-wifi'" :style="'color:' + diyData.page.style.titleTextColor"></Icon></span>
          <span class="time" :style="'color:' + diyData.page.style.titleTextColor">19:00</span>
          <span>
            <Icon :iconname="'#icon-xinhao'" :style="'color:' + diyData.page.style.titleTextColor"></Icon>
            <Icon class="ml4" :iconname="'#icon-iconset0250'" :style="'color:' + diyData.page.style.titleTextColor"></Icon>
          </span>
        </div>
        <div class="navigation" :style="'color:' + diyData.page.style.titleTextColor">{{ diyData.page.params.title }}</div>
      </div>
    </div>
    <draggable class="wrapper" v-model="diyData.items" :options="{ animation: 120, filter: '.drag__nomove' }">
      <div :class="form.selectedIndex == index ? 'diy-phone-item active' : 'diy-phone-item'" :key="item.id" v-for="(item, index) in diyData.items">
        <!-- 搜索栏 -->
        <template v-if="item.type == 'search'">
          <Search :item="item" :index="index" :selectedIndex="form.selectedIndex"></Search>
        </template>
        <!-- 图片轮播 -->
        <template v-else-if="item.type == 'banner'">
          <Banner :item="item" :index="index" :selectedIndex="form.selectedIndex"></Banner>
        </template>
        <!-- 图片-->
        <template v-else-if="item.type == 'imageSingle'">
          <ImageSingle :item="item" :index="index" :selectedIndex="form.selectedIndex"></ImageSingle>
        </template>
        <!-- 橱窗-->
        <template v-else-if="item.type == 'window'">
          <Window :item="item" :index="index" :selectedIndex="form.selectedIndex"></Window>
        </template>
        <!-- 视频组-->
        <template v-else-if="item.type == 'video'">
          <Video :item="item" :index="index" :selectedIndex="form.selectedIndex"></Video>
        </template>
        <!--公告组-->
        <template v-else-if="item.type == 'notice'">
          <Notice :item="item" :index="index" :selectedIndex="form.selectedIndex"></Notice>
        </template>
        <!--导航组-->
        <template v-else-if="item.type == 'navBar'">
          <NavBar :item="item" :index="index" :selectedIndex="form.selectedIndex"></NavBar>
        </template>
        <!--商品组-->
        <template v-else-if="item.type == 'product'">
          <Product :item="item" :index="index" :selectedIndex="form.selectedIndex"></Product>
        </template>
        <!--客服-->
        <template v-else-if="item.type == 'service'">
          <Service :item="item" :index="index" :selectedIndex="form.selectedIndex"></Service>
        </template>
        <!--富文本-->
        <template v-else-if="item.type == 'richText'">
          <RichText :item="item" :index="index" :selectedIndex="form.selectedIndex"></RichText>
        </template>
        <!--辅助空白-->
        <template v-else-if="item.type == 'blank'">
          <Blank :item="item" :index="index" :selectedIndex="form.selectedIndex"></Blank>
        </template>
        <!--辅助线-->
        <template v-else-if="item.type == 'guide'">
          <Guide :item="item" :index="index" :selectedIndex="form.selectedIndex"></Guide>
        </template>
      </div>
    </draggable>
  </div>
</template>

<script>
import Search from './model/Search.vue';
import Banner from './model/Banner.vue';
import ImageSingle from './model/ImageSingle.vue';
import Window from './model/Window.vue';
import Video from './model/Video.vue';
import Notice from './model/Notice.vue';
import NavBar from './model/NavBar.vue';
import Product from './model/Product.vue';
import Service from './model/Service.vue';
import RichText from './model/RichText.vue';
import Blank from './model/Blank.vue';
import Guide from './model/Guide.vue';
import draggable from 'vuedraggable';
export default {
  components: {
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
    /*拖动*/
    draggable
  },
  data() {
    return {};
  },
  props: ['form', 'defaultData', 'diyData', 'opts'],
  created() {},
  methods: {
    /**
     * 删除diy元素
     * @param index
     */
    onDeleleItem: function(index) {
      let self = this;
      self
        .$confirm('确定要删除吗?', '提示', {
          type: 'warning'
        })
        .then(() => {
          self.diyData.items.splice(index, 1);
          self.form.selectedIndex = -1;
        });
    },
    /**
     * 编辑当前选中的Diy元素
     * @param index
     */
    onEditer: function(index) {
      let self = this;
      // 记录当前选中元素的索引
      self.form.selectedIndex = index;
      // 当前选中的元素数据
      self.form.curItem = self.form.selectedIndex === 'page' ? self.diyData.page : self.diyData.items[self.form.selectedIndex];
      // 注册编辑器事件
      self.initEditor();
    },
    /**
     * 注册编辑器事件
     */
    initEditor: function() {
      let self = this;
      // 注册dom事件
      self.$nextTick(function() {
        // 销毁 umeditor 组件
        if (self.form.umeditor.hasOwnProperty('key')) {
          self.form.umeditor.destroy();
        }
        // 注册html组件
        self.editorHtmlComponent();
        // 富文本事件
        if (self.form.curItem.type === 'richText') {
          //self.onRichText(self.form.curItem);
        }
      });
    },

    /**
     * 编辑器事件：html组件
     */
    editorHtmlComponent: function() {
      let self = this;
      var editor = self.$refs['diy-editor'];
      // 单/多选框
      //editor.find('input[type=checkbox], input[type=radio]').uCheck();
      // select组件
      // $editor.find('select').selected();
    }
  }
};
</script>

<style>
.diy-phone-container {
  position: relative;
  height: calc(100vh - 150px);
}
.diy-phone-container .wrapper {
  position: absolute;
  top: 68px;
  right: 0;
  bottom: 0;
  left: 0;
  overflow-y: auto;
}
.diy-phone-container .phone-top {
  padding: 0 20px;
  overflow: hidden;
  border-radius: 18px 18px 0 0;
}
.diy-phone-container .phone-top .status-bar {
  height: 20px;
  display: flex;
  justify-content: space-between;
}
.diy-phone-container .phone-top .svg-icon {
  width: 20px;
  height: 20px;
  color: #333333;
}
.diy-phone-container .phone-top .navigation {
  height: 44px;
  line-height: 44px;
  text-align: center;
  font-size: 18px;
}
.diy-phone-container .diy-phone-item {
  position: relative;
}
.diy-phone-container .diy-phone-item > div {
  border: 2px solid #f1f1f2;
}
.diy-phone-container .diy-phone-item > div:hover,
.diy-phone-container .diy-phone-item.active > div {
  border: 2px dashed #3a8ee6;
}
.diy-phone-container .diy-phone-item .btn-edit-del {
  position: absolute;
  bottom: 0;
  right: 0;
  z-index: 10;
}
.diy-phone-container .diy-phone-item .btn-edit-del > div {
  width: 32px;
  height: 16px;
  line-height: 16px;
  display: inline-block;
  text-align: center;
  font-size: 10px;
  color: #fff;
  background: rgba(0, 0, 0, 0.4);
  margin-left: 2px;
  cursor: pointer;
}
.diy-phone-container img {
  width: 100%;
}
</style>
