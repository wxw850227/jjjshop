<template>
	<view
		class="drag optional pr"
		:style="{
			background: itemData.style.bgcolor,
			paddingLeft: itemData.style.paddingLeft * 2 + 'rpx',
			paddingRight: itemData.style.paddingLeft * 2 + 'rpx',
			paddingTop: itemData.style.paddingTop * 2 + 'rpx',
			paddingBottom: itemData.style.paddingBottom * 2 + 'rpx'
		}"
	>
		<view class="diy-navBar">
			<view class="list" :class="'column-' + itemData.style.rowsNum" v-if="itemData.style.rowsNum == 1">
				<view
					class="item"
					:key="index"
					v-for="(navBar, index) in itemData.data"
					@click="gotoDetail(navBar.linkUrl)"
					:style="{
						borderTopLeftRadius: itemData.style.topRadio * 2 + 'rpx',
						borderTopRightRadius: itemData.style.topRadio * 2 + 'rpx',
						borderBottomLeftRadius: itemData.style.bottomRadio * 2 + 'rpx',
						borderBottomRightRadius: itemData.style.bottomRadio * 2 + 'rpx',
						backgroundImage: 'linear-gradient(to bottom, ' + (itemData.style.background1 || '#fff') + ', ' + (itemData.style.background2 || '#fff') + ')'
					}"
				>
					<view class="d-r d-b-c pl16 ww100">
						<view class="d-b-s d-c flex-1">
							<view class="title1" :style="{ color: navBar.titlecolor }">{{ navBar.title }}</view>
							<view class="item-text2 text-ellipsis tl" :style="{ color: navBar.textcolor }">{{ navBar.text }}</view>
						</view>
						<view class="item-navimg1 "><image class="images" :src="navBar.imageUrl" alt="" /></view>
					</view>
				</view>
			</view>
			<view class="list" :class="'column-' + itemData.style.rowsNum" v-if="itemData.style.rowsNum == 2">
				<view
					class="item"
					:key="index"
					v-for="(navBar, index) in itemData.data"
					@click="gotoDetail(navBar.linkUrl)"
					:style="{
						borderTopLeftRadius: itemData.style.topRadio * 2 + 'rpx',
						borderTopRightRadius: itemData.style.topRadio * 2 + 'rpx',
						borderBottomLeftRadius: itemData.style.bottomRadio * 2 + 'rpx',
						borderBottomRightRadius: itemData.style.bottomRadio * 2 + 'rpx',
						backgroundImage: 'linear-gradient(to bottom, ' + (itemData.style.background1 || '#fff') + ', ' + (itemData.style.background2 || '#fff') + ')'
					}"
				>
					<view class="item-navimg"><image class="images"  :src="navBar.imageUrl" alt="" /></view>
					<view class="item-text1 text-ellipsis tc" :style="{ color: navBar.titlecolor }">{{ navBar.title }}</view>
					<view class="item-text2 text-ellipsis tc" :style="{ color: navBar.textcolor }">{{ navBar.text }}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			//单个宽度
			item_width: '',
			item_height: ''
		};
	},
	props: ['itemData'],
	created() {
		this.item_width = this.itemData.style.rowsNum == 1 ? '690rpx' : '330rpx';
		this.item_height = this.itemData.style.rowsNum == 1 ? '150rpx' : '254rpx';
	},
	methods: {
		/*跳转页面*/
		gotoDetail(path) {
			let self = this;
			console.log(path);
			if (path.startsWith('scanQrcode')) {
				self[path]();
			} else {
				self.gotoPage(path);
			}
		},
		/*扫一扫核销*/
		scanQrcode: function() {
			this.$emit('scanQrcode');
		}
		/*跳转页面*/
	}
};
</script>

<style lang="scss" scoped>
.title1 {
	width: 128rpx;
	height: 40rpx;
	font-size: 32rpx;
	font-family: Source Han Sans CN;
	font-weight: bold;
	color: #3a3a3a;
	white-space: nowrap;
	margin-right: 90rpx;
}
.diy-navBar .list {
	box-sizing: border-box;
	display: flex;
	flex-wrap: wrap;
	align-items: flex-start;
	justify-content: space-between;
}

.diy-navBar .list .item {
	padding: 20rpx;
	display: flex;
	box-sizing: border-box;
}

.diy-navBar .list.column-1 .item {
	width: 100%;
	height: 150rpx;
	margin: 0 auto;
	justify-content: space-between;
	align-items: center;
}
.diy-navBar .list.column-2 {
	display: grid;
	grid-template-columns: repeat(2,1fr);
	grid-gap: 20rpx;
}
.diy-navBar .list.column-2 .item {
	height: 250rpx;
	align-items: center;
	flex-direction: column;
	.item-navimg {
		margin-bottom: 12rpx;
	}
}

// .diy-navBar .list .item-image {
// 	width: 60%;
// }

// .diy-navBar .list .item-image .images {
// 	width: 100%;
// }

.diy-navBar .list .item-text1 {
	width: 100%;
	font-size: 30rpx;
	font-weight: 600;
	color: #333333;
}
.diy-navBar .list .item-text2 {
	width: 100%;
	padding: 8rpx 0;
	font-weight: 400;
	color: #999999;
	font-size: 22rpx;
}
.item-navimg {
	width: 100rpx;
	height: 100rpx;
	.images {
		width: 100rpx;
		height: 100rpx;
	}
}
.item-navimg1 {
	width: 100rpx;
	height: 100rpx;
	.images {
		width: 100rpx;
		height: 100rpx;
	}
	// margin-left: 130px;
}
</style>
