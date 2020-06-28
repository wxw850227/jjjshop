<template>
  <!--
    	作者：wangxw
    	时间：2019-11-05
    	描述：diy组件 导航组
    -->
    <div @click.stop="$parent.$parent.onEditer(index)">
      <div class="drag optional" :class="{selected:index === selectedIndex}">
        <div class="diy-navBar" :style="{background:item.style.background}">
          <ul class="list" :class="'column-'+item.style.rowsNum">
            <li class="item" :key="index" v-for="(navBar,index) in item.data">
              <div class="item-image">
                <img v-img-url="navBar.imgUrl">
              </div>
              <div class="item-text text-ellipsis" :style="{color:navBar.color}">
                {{navBar.text}}</div>
            </li>
          </ul>
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
.diy-navBar .list{ display: flex; flex-wrap: wrap; align-items: flex-start;}
.diy-navBar .list .item{ padding: 10px 0; display: flex; justify-content: center; align-items: center; flex-direction: column;}
.diy-navBar .list.column-3 .item{ width: 33.333333333%;}
.diy-navBar .list.column-4 .item{ width: 25%;}
.diy-navBar .list.column-5 .item{ width: 20%;}
.diy-navBar .list .item-image{ width: 60%;}
.diy-navBar .list .item-image img{ width: 100%;}
.diy-navBar .list .item-text{ width: 100%; padding: 4px 0; text-align: center;}
</style>
