<template>
  <div>
    <script id="editor" type="text/plain"></script>
    <Upload v-if="isupload" :config="{total:9}" :isupload="isupload" @returnImgs="returnImgsFunc">上传图片</Upload>
  </div>
</template>
<script>
  import Upload from '@/components/file/Upload';
  export default {
    components:{Upload},
    name: 'ue',
    data() {
      return {
        editor: null,
        isupload:false,
        hasCallback: false,
        callback: null,
        this_config:{
          //不需要工具栏漂浮
          autoFloatEnabled:false
        }
      }
    },
    props: {
      text: '',
      config: {

      }
    },
    created() {
      window.openUpload = this.openUpload;
    },
    watch:{

    },
    mounted() {

      Object.assign(this.this_config,this.config);
      this.editor = window.UE.getEditor('editor', this.this_config);
      this.editor.addListener('ready', (e) => {
        this.editor.setContent(this.text);
      });
      this.editor.addListener('contentChange', (e) => {
        this.$emit('contentChange',this.editor.getContent());
      });
    },
    methods: {
      getUEContent() {
        return this.editor.getContent()
      },
      openUpload: function(callback) {
        this.isupload = true;
        if (callback) {
          this.hasCallback = true;
          this.callback = callback;
        }
      },
      /*获取图片*/
      returnImgsFunc(e) {
        if (e != null) {
          this.hasCallback && this.callback(e);
        }
        this.isupload = false;
      },
    },
    destroyed() {
      this.editor.destroy()
    }
  }
</script>
