<template>
	<div class="drag optional" @click.stop="$parent.$parent.onEditer(index)"
		:class="{ selected: index === selectedIndex }" :style="{
			background: item.style.background,
			paddingLeft: item.style.paddingLeft + 'px',
			paddingRight: item.style.paddingLeft + 'px',
			paddingTop: item.style.paddingTop + 'px',
			paddingBottom: item.style.paddingBottom + 'px'
		}">
		<div class="diy-banner pr" :style="'height:' + item.style.height * 0.5 + 'px;'">
			<div class="img-list pr">
				<img class="banner-img" style="display: block;" :style="{
						borderTopLeftRadius: item.style.topRadio + 'px',
						borderTopRightRadius: item.style.topRadio + 'px',
						borderBottomLeftRadius: item.style.bottomRadio + 'px',
						borderBottomRightRadius: item.style.bottomRadio + 'px'
					}" v-img-url="item.data[0] && item.data[0].imgUrl" />
			</div>
			<div class="dots center d-c-c">
				<div :key="index" :class="index == 0 ? 'active ' + item.style.imgShape : item.style.imgShape"
					v-for="(banner, index) in item.data"
					:style="index == 0 ? 'background:' + item.style.btnColor : 'background:' + item.style.btnColor + ';opacity: 0.4;'">
				</div>
			</div>
		</div>
		<div class="btn-edit-del">
			<div class="btn-del" @click.stop="$parent.$parent.onDeleleItem(index)">删除</div>
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

<style lang="scss" scoped>
	.banner-img {
		width: 100%;
	}

	.drag.optional {
		position: relative;
	}

	.p15 {
		padding: 15px;
	}

	.diy-banner.round {
		padding: 12px;
		box-sizing: content-box;
		overflow: hidden;
		text-align: center;
	}

	.diy-banner.round img {
		width: 100%;
		height: 100px;
		object-fit: fill;
		border-radius: 10px;
		margin-bottom: 12px;
		box-sizing: content-box;
	}

	.diy-banner.square {
		height: 100px;
		overflow: hidden;
		text-align: center;
	}

	.diy-banner.square img {
		width: 100%;
		height: 100px;
		object-fit: fill;
	}

	.diy-banner .dots {
		position: absolute;
		left: 0;
		right: 0;
		margin: 0 auto;
		bottom: 8px;
		width: 100%;
		z-index: 1;
	}

	.diy-banner.round .dots {
		position: absolute;
		left: 0;
		right: 0;
		margin: 0 auto;
		bottom: 20px;
	}

	.diy-banner .dots .square,
	.diy-banner .dots .round,
	.diy-banner .dots .rectangle {
		bottom: 40rpx;
		left: 0;
		right: 0;
		margin: auto;
	}

	.diy-banner .dots .square {
		width: 7px;
		height: 7px;
		margin: 0 2px;
		background: #ebedf0;
		opacity: 0.3;
	}

	.diy-banner .dots .round {
		width: 11px;
		height: 11px;
		margin: 0 2px;
		background: #ebedf0;
		opacity: 0.3;
		border-radius: 50%;
	}

	.diy-banner .dots .rectangle {
		width: 20px;
		height: 3px;
		margin: 0 2px;
		background: #ebedf0;
		opacity: 0.3;
		border-radius: 4rpx;
	}

	.diy-banner .dots .active {
		opacity: 1;
	}

	/* 登录状态 */
	.diy-user-wrap {
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
		bottom: 0;
		z-index: 1;
		width: 100%;
		box-sizing: border-box;
	}

	.diy-user-bg {
		margin: 0 auto;
		width: 100%;
		box-sizing: border-box;
		// width: 335px;
		height: 42px;
		box-shadow: 0px 5px 12px 0px rgba(6, 0, 1, 0.03);
		border-radius: 15px;
		position: relative;
		overflow: hidden;

		.bg {
			width: 100%;
			height: 100%;
			opacity: 1;
			position: absolute;
			bottom: 0;
		}

		.diy-user-content {
			position: absolute;
			width: 100%;
			height: 100%;
		}

		.login-wrap {
			display: flex;
			justify-content: space-between;
			align-items: center;
			height: 100%;
			color: #fff;

			.img {
				width: 30px;
				height: 30px;
				border-radius: 50%;
			}

			.txt {
				margin-left: 4px;
			}


			.btn {
				background: #fff;
				padding: 5px 8px;
				border-radius: 15px;
				color: #FFCC00;
			}
		}
	}

	.loginImage {
		width: 30px;
		height: 30px;
		margin-left: 18px;
	}
</style>