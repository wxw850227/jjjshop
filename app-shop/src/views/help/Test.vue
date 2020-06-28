<template>
  <!--
    	作者：4948286@qq.com
    	时间：2019-10-30
    	描述：测试
    -->
  <div class="test-wrap">
    <h1>例子</h1>

    <table class="table">
      <tr>
        <td>1111</td>
      </tr>
      <tr>
        <td rowspan="2">22222</td>
        <td>222222</td>
      </tr>
    </table>

    <div>
      <el-button type="primary" @click="openUpload">上传图片</el-button>
      <!--上传图片组件-->
      <Upload v-if="isupload" :isupload="isupload" :config="{ total: 3 }" @returnImgs="returnImgsFunc">上传图片</Upload>

      <!--指令-->
      <div v-demo:foo.a.b="'message'"></div>

      <img v-img-url="11" style="width: 60px;" />

      <el-button v-auth="'yes'">权限</el-button>

      <div>{{obj['/order/order/Index']}}</div>
    </div>

    <el-tree :data="data" show-checkbox node-key="id" :default-expanded-keys="[2, 3]" :default-checked-keys="[9]" :props="defaultProps"></el-tree>

    <div>
      <el-input></el-input>
      <el-button  @click="is_linkset=true">超链接</el-button>
    </div>
    <!--超链接-->
    <Setlink v-if="is_linkset" :is_linkset="is_linkset" @closeDialog="closeLinkset"></Setlink>

    <div>
      <el-checkbox v-model="checked" :checked="checked=='1'"  @change="checkedFunc">商品名称</el-checkbox>
    </div>

  </div>
</template>

<script>
import Upload from '@/components/file/Upload';
import Setlink from '@/components/setlink/Setlink';
export default {
  components: {
    Upload: Upload,
    Setlink:Setlink
  },
  data() {
    return {
      /*是否上传图片*/
      isupload: false,
      data: [
        {
          id: 1,
          label: '一级 1',
          children: [
            {
              id: 4,
              label: '二级 1-1',
              children: [
                {
                  id: 9,
                  label: '三级 1-1-1'
                },
                {
                  id: 10,
                  label: '三级 1-1-2'
                }
              ]
            }
          ]
        },
        {
          id: 2,
          label: '一级 2',
          children: [
            {
              id: 5,
              label: '二级 2-1'
            },
            {
              id: 6,
              label: '二级 2-2'
            }
          ]
        },
        {
          id: 3,
          label: '一级 3',
          children: [
            {
              id: 7,
              label: '二级 3-1'
            },
            {
              id: 8,
              label: '二级 3-2'
            }
          ]
        }
      ],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      obj:{},
      /*超链接是否显示*/
      is_linkset:false,
      /*checked*/
      checked:'1'
    };
  },
  created() {
    let arr = { key: 1234, name: 'luoyiming' }; //[1,2,3,4,5];
    for (let item in arr) {
      // console.log(arr[item]);
    }

    // let arr1=[{o:'1212',b:'aaaaaa',list:[111,333,{v:'cccc'}]},1,23,4];
    // let arr2=[{e:'33333',b:'aaaaaa'},5,6,7];
    // let arr3=arr1.concat(arr2);
    // console.log(arr3);

    // var obj={a:{b:'111'}};
    // obj['a.b']=3333;
    // console.log(obj);

    /* if(arr!=null&&Object.prototype.toString.call(arr)== '[object Array]'){
        alert('数组');
      }else{
        alert('不是');
      } */

    //let array=[1,2,3];
    // let list=[{name:'',old:0}];
    // let res=array.concat(list,this.data);
    // console.log(res);

    // let path="aaa/vaa/baaa";

    // for(let i=0;i<path.length;i++){
    //   console.log(path.charAt(i));
    // }


    // this.obj['/order/order/Index']='订单';
    // console.log(this.obj)


    let array=[1,2,3,4];
    for(let i=0;i<array.length;i++){
      array.splice(i,1);
      i--;
    }
    console.log(111,array);
  },

  methods: {
    /*上传*/
    openUpload() {
      this.isupload = true;
    },

    /*获取图片*/
    returnImgsFunc(e) {
      if (e != null) {
        console.log('你选择的图片', e);
      }
      this.isupload = false;
    },

    /*获取链接并关闭弹窗*/
    closeLinkset(e){
      this.is_linkset=false;
      console.log(e);
    },

    /*checkedFunc方法*/
    checkedFunc(e){
      console.log(e);
    }
  }
};
</script>

<style lang="scss">
.test-wrap {
  padding: 30px;
}
.table td{border: 1px solid #CCCC77;}
</style>
