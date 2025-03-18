<template>
	<!--
      	描述：diy组件-模拟显示-导航组
      -->
	<div @click.stop="$parent.$parent.onEditer(index)">
		<div
			class="drag optional"
			:style="{
				background: item.style.bgcolor,
				paddingLeft: item.style.paddingLeft + 'px',
				paddingRight: item.style.paddingLeft + 'px',
				paddingTop: item.style.paddingTop + 'px',
				paddingBottom: item.style.paddingBottom + 'px'
			}"
			:class="{ selected: index === selectedIndex }"
		>
			<div class="diy-navBar">
				<ul
					class="list column-2"
					:style="{
						borderTopLeftRadius: item.style.topRadio + 'px',
						borderTopRightRadius: item.style.topRadio + 'px',
						borderBottomLeftRadius: item.style.bottomRadio + 'px',
						borderBottomRightRadius: item.style.bottomRadio + 'px',
						backgroundImage: 'linear-gradient(to bottom, ' + (item.style.background1 || '#fff') + ', ' + (item.style.background2 || '#fff') + ')'
					}"
				>
					<li class="item openline"  :key="index" v-for="(navBar, index) in item.data">
						<div class="item-navimg"><img v-img-url="navBar.imgUrl" alt="" /></div>
						<div class="item-text1 text-ellipsis tc" :style="{ color: navBar.titlecolor }">{{ navBar.title }}</div>
						<div class="item-text2 text-ellipsis tc" :style="{ color: navBar.textcolor }">{{ navBar.text }}</div>
					</li>
				</ul>
			</div>
			<div class="btn-edit-del"><div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div></div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {};
	},
	props: ['item', 'index', 'selectedIndex'],
	methods: {}
};
</script>

<style scoped lang="scss">
.title1 {
	width: 64px;
	height: 20px;
	font-size: 16px;
	font-family: Source Han Sans CN;
	font-weight: bold;
	color: #3a3a3a;
	white-space: nowrap;
	margin-right: 45px;
}
.diy-navBar .list {
	box-sizing: border-box;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
	justify-content: center;
}

.diy-navBar .list .item {
	padding: 10px;
	display: flex;
	box-sizing: border-box;
}

.diy-navBar .list.column-1 .item {
	flex: 1;
	height: 75px;
	margin: 0 auto;
	justify-content: space-between;
	align-items: center;
}

.diy-navBar .list.column-2 .item {
	flex: 1;
	height: 125px;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	    flex-shrink: 0;
	    overflow: hidden;
		position: relative;
	.item-navimg {
		margin-bottom: 6px;
	}
}
.diy-navBar .list.column-2 .item.openline::after{
	position: absolute;
	content: '';
	top: 0;
	bottom: 0;
	height: 60%;
	width: 1px;
	background-color: #eee;
	margin: auto;
	left: 0;
}
.diy-navBar .list.column-2 .item.openline:first-child::after{
	width: 0;
}
.diy-navBar .list .item-image {
	width: 60%;
}

.diy-navBar .list .item-image img {
	width: 100%;
}

.diy-navBar .list .item-text1 {
	width: 100%;
	font-size: 15px;
	font-weight: 600;
	color: #333333;
}
.diy-navBar .list .item-text2 {
	width: 100%;
	padding: 4px 0;
	font-weight: 400;
	color: #999999;
	font-size: 11px;
}
.item-navimg {
	width: 50px;
	height: 50px;
	img {
		width: 50px;
		height: 50px;
	}
}
.item-navimg1 {
	width: 50px;
	height: 50px;
	margin-left: 130px;
}
</style>
