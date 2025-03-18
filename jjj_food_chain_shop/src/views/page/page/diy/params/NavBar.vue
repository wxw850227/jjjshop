<template>

	<div>
		<div class="common-form">
			<span>{{ curItem.name }}（--广告导航--）</span>
		</div>
		<div class="f16 gray3 form-subtitle">样式设置</div>
		<!--上下边距-->
		<div class="form-item">
			<div class="form-label">上边距：</div>
			<el-slider v-model="curItem.style.paddingTop" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
		</div>
		<!--上下边距-->
		<div class="form-item">
			<div class="form-label">下边距：</div>
			<el-slider v-model="curItem.style.paddingBottom" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
		</div>
		<!--左右边距-->
		<div class="form-item">
			<div class="form-label">左右边距：</div>
			<el-slider v-model="curItem.style.paddingLeft" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
		</div>
		<!--上圆角-->
		<div class="form-item">
			<div class="form-label">上圆角：</div>
			<el-slider v-model="curItem.style.topRadio" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
		</div>
		<!--下圆角-->
		<div class="form-item">
			<div class="form-label">下圆角：</div>
			<el-slider v-model="curItem.style.bottomRadio" size="small" show-input :show-input-controls="false" input-size="small"></el-slider>
		</div>
		<div class="form-item">
			<div class="form-label">底部背景：</div>
			<div class="flex-1 d-s-c" style="height: 36px;">
				<el-color-picker size="default" v-model="curItem.style.bgcolor"></el-color-picker>
				<el-input class="ml10" v-model="curItem.style.bgcolor" placeholder="透明" />
				<el-button style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(curItem.style, 'bgcolor', '#F2F2F2')" type="primary" link>重置</el-button>
			</div>
		</div>
		<div class="form-item">
			<div class="form-label">组件背景：</div>
			<div class="flex-1 d-s-c" style="height: 36px;">
				<el-input class="ml10" v-model="curItem.style.background1" placeholder="透明" />
				<el-input class="ml10" v-model="curItem.style.background2" placeholder="透明" />
				<view class="ml10"><el-color-picker size="default" v-model="curItem.style.background1"></el-color-picker></view>
				<view class="ml10"><el-color-picker size="default" v-model="curItem.style.background2"></el-color-picker></view>
				<el-button style="margin-left: 10px;" @click.stop="ResetColor('background1', 'background2')" type="primary" link>重置</el-button>
			</div>
		</div>
		<div class="form-chink"></div>
		<div class="f16 gray3 form-subtitle">导航模式</div>
		<!--每行数量-->
		<el-form-item label="每行数量：" class="pl56">
			<el-radio-group v-model="curItem.style.rowsNum">
				<el-radio label="1">1个</el-radio>
				<el-radio label="2">2个</el-radio>
			</el-radio-group>
		</el-form-item>
		<el-form size="small" :model="curItem" label-width="100px">
			<div class="f16 gray3 form-subtitle">
				图片设置
				<span class="gray9 f12">图片上传建议宽度88*88px，拖拽图片可调整图片显示顺序哦</span>
			</div>
			<template v-if="curItem.data && curItem.data.length > 0">
				<draggable v-model="curItem.data" group="people" item-key="index" class="draggable-list">
					<template #item="{ element,index }">
						<div class="d-b-c param-img-item">
							<div class="d-c d-c-c mr10" style="width: 50px;">
								<el-icon style="height: 6px;" color="#999" size="12px" v-for="item in 6" :key="item"><MoreFilled /></el-icon>
							</div>
							<div class="right">
								<el-icon class="el-icon-DeleteFilled" @click="onEditorDeleleData(index)"><CloseBold /></el-icon>
								<div class="d-s-c mb16 ww100">
									<div class="url-box  d-s-c"><span class="key-name">图标</span></div>
									<div class="d-a-c flex-1">
										<div class="icon">
											<div class="icon-text">点击替换</div>
											<img v-img-url="element.imageUrl" alt="" @click="$parent.onEditorSelectImage(element, 'imageUrl')" />
										</div>
									</div>
								</div>
								<div class="url-box mb16 flex-1 d-s-c ww100">
									<span class="key-name">标题</span>
									<el-input maxlength="6" show-word-limit v-model="element.title"></el-input>
								</div>
								<div class="url-box mb16 flex-1 d-s-c ww100">
									<span class="key-name">标题颜色</span>
									<div class="flex-1 d-s-c" style="height: 36px;">
										<el-color-picker size="default" v-model="element.titlecolor"></el-color-picker>
										<el-input class="ml10" v-model="element.titlecolor" placeholder="透明" />
										<el-button style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(element, 'titlecolor', '#333333')" type="primary" link>
											重置
										</el-button>
									</div>
								</div>
								<div class="url-box mb16 flex-1 d-s-c ww100">
									<span class="key-name">内容</span>
									<el-input v-model="element.text"></el-input>
								</div>
								<div class="url-box mb16 flex-1 d-s-c ww100">
									<span class="key-name">内容颜色</span>
									<div class="flex-1 d-s-c" style="height: 36px;">
										<el-color-picker size="default" v-model="element.textcolor"></el-color-picker>
										<el-input class="ml10" v-model="element.textcolor" placeholder="透明" />
										<el-button style="margin-left: 10px;" @click.stop="$parent.onEditorResetColor(element, 'textcolor', '#999999')" type="primary" link>
											重置
										</el-button>
									</div>
								</div>
								<div class="d-s-c  ww100">
									<div class="url-box flex-1 d-s-c">
										<span class="key-name">链接</span>
										<el-input :suffix-icon="ArrowRight" @click="changeLink(index)" v-model="element.name">
											<template #suffix>
												<el-icon color="#333" size="16px"><ArrowRight /></el-icon>
											</template>
										</el-input>
									</div>
								</div>
							</div>
						</div>
					</template>
				</draggable>
			</template>
			<div class="d-c-c pb16" v-if="curItem.data.length < 5">
				<el-button plain type="primary" @click="$parent.onEditorAddData">+添加{{ curItem.data.length }}/5</el-button>
			</div>
		</el-form>
		<Setlink v-if="is_linkset" :is_linkset="is_linkset" @closeDialog="closeLinkset">选择链接</Setlink>
	</div>
</template>

<script>
import Setlink from '@/components/setlink/Setlink.vue';
import draggable from 'vuedraggable';
export default {
	components: {
		Setlink,
		draggable
	},
	data() {
		return {
			/*是否选择链接*/
			is_linkset: false,
			index: null
		};
	},
	props: ['curItem', 'selectedIndex', 'opts'],
	methods: {
		/*选择链接*/
		changeLink(index) {
			this.is_linkset = true;
			this.index = index;
		},
		onEditorDeleleData: function(index) {
			let self = this;
			if (self.curItem.data.length <= 1) {
				ElMessage({
					message: '至少保留一个',
					type: 'error'
				});
				return false;
			}
			self.curItem.data.splice(index, 1);
		},
		/*获取链接并关闭弹窗*/
		closeLinkset(e) {
			this.is_linkset = false;
			if (e) {
				this.curItem.data[this.index].linkUrl = e.url;
				this.curItem.data[this.index].name = '链接到' + ' ' + e.type + ' ' + e.name;
			}
		},
		ResetColor(title_color1, title_color2) {
			this.$parent.onEditorResetColor(this.curItem.style, title_color1, '#ffffff');
			if (title_color2) {
				this.$parent.onEditorResetColor(this.curItem.style, title_color2, '#ffffff');
			}
		}
	}
};
</script>

<style scoped>
.diy-tabbar {
	margin: 0;
	padding: 0;
	background: none;
}

.model-container {
	width: 375px;
	height: calc(100vh - 150px);
	margin: 0 auto;
	background-color: #fff;
}

.mr30 {
	margin-right: 30px;
}

.model-container img {
	width: 100%;
}

.model-container .img-box {
	box-shadow: 0 0 16px 0 rgba(0, 0, 0, 0.1);
}

.param-container {
	width: 476px;
	max-height: calc(100vh - 98px);
	overflow-y: auto;
	background: #fff;
	/* border: 1px solid #cccccc; */
}

.param-container .el-form-item {
	--font-size: 14px !important;
}

.form-title {
	padding: 0 22px;
	height: 62px;
	display: flex;
	justify-content: flex-start;
	align-items: center;
	border-bottom: 1px solid #eee;
	font-size: 16px;
	color: #666;
	font-weight: bold;
}

.form-subtitle {
	padding-top: 18px;
	padding-bottom: 18px;
	padding-left: 20px;
}

.icon {
	position: relative;
}

.icon img {
	width: 88px;
	height: 88px;
}

.icon .icon-text {
	width: 88px;
	height: 28px;
	text-align: center;
	color: #fff;
	font-size: 16px;
	line-height: 28px;
	position: absolute;
	z-index: 1;
	bottom: 0;
	left: 0;
	background-color: rgba(0, 0, 0, 0.45);
}

.nav_img {
	width: 30px !important;
	height: 30px;
}

.delete-box {
	z-index: 99;
	display: flex;
	justify-content: flex-end;
}

.param-img-item {
	position: relative;
	border: 1px solid #eee;
	margin-left: 20px;
	margin-top: 23px;
	padding: 0 22px 0 6px;
	border-radius: 10px;
	width: 100%;
	box-sizing: border-box;
}
.param-img-item .right {
	padding: 6px 0 26px 0;
	flex: 1;
}
.param-img-item .el-icon-DeleteFilled {
	font-size: 26px;
	position: absolute;
	right: -6px;
	top: -6px;
	background-color: #666;
	color: #fff;
	border-radius: 50%;
	padding: 4px;
}

.form-item {
	display: flex;
	justify-content: center;
	align-items: center;
	font-size: 14px;
	padding: 10px;
}

.el-color-picker--small .el-color-picker__trigger {
	width: 36px;
	height: 36px;
}

.f12 {
	font-size: 12px;
}
.draggable-list {
	padding-bottom: 20px;
	padding-right: 18px;
}
</style>
