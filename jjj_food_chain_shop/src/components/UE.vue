<template>
	<vue-ueditor-wrap v-model="msg" :config="this_config" :editor-id="editorId" @ready="ready"></vue-ueditor-wrap>
	<Upload v-if="isupload" :config="{ total: 9 }" :isupload="isupload" @returnImgs="returnImgsFunc">上传图片</Upload>
</template>
<script>
import { reactive, toRefs, ref, watch } from 'vue';
import Upload from '@/components/file/Upload.vue'; 
export default {
	components: {
		Upload
	},
	props: ['text','editorId'],
	setup(props, { emit }) {
		const state = reactive({
			msg: props.text,
			editor: null,
			isupload: false,
			hasCallback: false,
			callback: null,
			this_config: {
				//不需要工具栏漂浮
				autoFloatEnabled: false,
			},
		});
		watch(
			() => state.msg,
			(v) => {
				emit("contentChange",v)
			}
		);
		return {
			...toRefs(state),
		};
	},
	methods: {
		ready(){
			window.openUpload = this.openUpload;
		},
		// addCustomButtom(editorId){
		// 	let _this = this;
        //     window.UE.registerUI('test-button', function (editor, uiName) {
        //         // 注册按钮执行时的 command 命令，使用命令默认就会带有回退操作
        //         editor.registerCommand(uiName, {
        //             execCommand: () => {
        //                 /* _this.imageList = [];
        //                 _this.dialogVisible = true; */
        //                 _this.openUpload();
        //                 _this.editorHandler = editor;
        //             }
        //         })
        //         // 创建一个 button
        //         var btn = new window.UE.ui.Button({
        //             // 按钮的名字
        //             name: uiName,
        //             // 提示
        //             title: '图片上传',
        //             // 需要添加的额外样式，可指定 icon 图标，图标路径参考常见问题 2
        //             cssRules: "background-position: -380px 0;",
        //             // 点击时执行的命令
        //             onclick: function () {
        //                 // 这里可以不用执行命令，做你自己的操作也可
        //                 editor.execCommand(uiName)
        //             }
        //         })

        //         // 当点到编辑内容上时，按钮要做的状态反射
        //         editor.addListener('selectionchange', function () {
        //         var state = editor.queryCommandState(uiName)
        //             if (state === -1) {
        //                 btn.setDisabled(true)
        //                 btn.setChecked(false)
        //             } else {
        //                 btn.setDisabled(false)
        //                 btn.setChecked(state)
        //             }
        //         })
        //         // 因为你是添加 button，所以需要返回这个 button
        //         return btn
        //     }, 46 /* 指定添加到工具栏上的哪个位置，默认时追加到最后 */, editorId /* 指定这个 UI 是哪个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮 */)
		// },
		/*打开选择图片*/
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
	}
};
</script>