<template>
  <!--
          作者：wangxw
          时间：2019-10-26
          描述：产品分类管理
      -->
  <div v-loading="loading" class="diy-container clearfix">
    <div class="diy-menu">
      <div class="common-form">组件库</div>
      <div class="min-group">
        <div class="hd">媒体组件</div>
        <div class="bd">
          <div class="item" @click="onAddItem('banner')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">图片轮播</p>
          </div>
          <div class="item" @click="onAddItem('imageSingle')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">单图组</p>
          </div>
          <div class="item" @click="onAddItem('window')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">图片橱窗</p>
          </div>
          <div class="item" @click="onAddItem('video')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">视频组</p>
          </div>
          <div class="item" @click="onAddItem('article')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">文章组</p>
          </div>
          <div class="item" @click="onAddItem('special')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">头条快报</p>
          </div>
        </div>
      </div>
      <div class="min-group">
        <div class="hd">商城组件</div>
        <div class="bd">
          <div class="item" @click="onAddItem('search')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">搜索框</p>
          </div>
          <div class="item" @click="onAddItem('notice')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">公告组</p>
          </div>
          <div class="item" @click="onAddItem('navBar')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">导航组</p>
          </div>
          <div class="item" @click="onAddItem('product')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">商品组</p>
          </div>
        </div>
      </div>
      <div class="min-group">
        <div class="hd">工具组件</div>
        <div class="bd">
          <div class="item" @click="onAddItem('service')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">在线客服</p>
          </div>
          <div class="item" @click="onAddItem('richText')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">富文本</p>
          </div>
          <div class="item" @click="onAddItem('blank')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">辅助空白</p>
          </div>
          <div class="item" @click="onAddItem('guide')">
            <p class="p-icon">
              <Icon :iconname="'#icon-tuichu'"></Icon>
            </p>
            <p class="p-txt">辅助线</p>
          </div>
        </div>
      </div>
    </div>

    <!--手机diy容器-->
    <div class="diy-phone">
      <!--基础信息-->
      <Model ref="model" :form="form" :opts="opts" :defaultData="defaultData" :diyData="diyData"></Model>
    </div>

    <!--基础信息-->
    <div class="diy-info">
      <Params :form="form" :opts="opts" :defaultData="defaultData" :diyData="diyData"></Params>
    </div>

    <!--提交-->
    <div class="common-button-wrapper">
      <el-button size="small" type="primary" @click="Submit()" :loading="loading">保存</el-button>
    </div>
  </div>
</template>

<script>
  import PageApi from '@/api/page.js';
  import Model from './diy/Model.vue';
  import Params from './diy/Params.vue';
  export default {
    components: {
      /*组件信息*/
      Model,
      /*参数信息*/
      Params
    },
    data() {
      return {
        /*是否加载完成*/
        loading: true,
        page_id: 0,
        defaultData: {},
        diyData: {},
        opts: {},
        form: {
          umeditor: {},
          curItem: {
          },
          // 当前选中的元素（下标）
          selectedIndex: -1
        }
      };
    },
    created() {
      /*获取列表*/
      this.getData();
    },
    methods: {
      /*获取列表*/
      getData() {
        let self = this;
        PageApi.editPage({}, true).then(data => {
          self.loading = false;
          self.defaultData = data.data.defaultData;
          self.diyData = data.data.jsonData;
          self.opts = data.data.opts;
        }).catch(error => {
          self.loading = false;
        });
      },
      /**
       * 新增Diy组件
       * @param key
       */
      onAddItem: function(key) {
        let self = this;
        // 复制默认diy组件数据
      console.log(self.defaultData)
        var data = self.defaultData[key];
        self.diyData.items.push(data);
        // 编辑当前选中的元素
        self.$refs.model.onEditer(self.diyData.items.length - 1);
      },
      /*上架*/
      Submit() {
        let self = this;
        self.loading = true;
        let Parmens = self.diyData;
        let page_id = self.page_id;
        PageApi.SavePage({
          Parmens: Parmens,
          page_id: page_id
        }, true).then(data => {
          self.loading = false;
          self.$message({
            message: '恭喜你，修改成功',
            type: 'success'
          });
          self.getData();
        }).catch(error => {
          self.loading = false;
        });
      }
    }
  };
</script>

<style lang="scss">
  @import '@/styles/page/diy.scss';

</style>
