<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 视频
    -->
    <div class="pr">
      <div class="cover-box"  @click.stop="$parent.$parent.onEditer(index)"></div>
      <div class="drag-optional" :class="{selected:index === selectedIndex}">
        <div class="diy-video" :style="{padding:item.style.paddingTop + 'px 0'}">
          <video :style="{height:item.style.height + 'px'}" :src="item.params.videoUrl" :poster="item.params.poster"
            :autoplay="item.params.autoplay == 1" controls>
            您的浏览器不支持 video 标签
          </video>
        </div>
        <div class="btn-edit-del">
          <div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {

      };
    },
    props: ['item', 'index', 'selectedIndex'],
    methods: {
        
    }
  };
</script>

<style>

.diy-video video{ display: block; width: 100%;}
.cover-box{ position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10;}
</style>
