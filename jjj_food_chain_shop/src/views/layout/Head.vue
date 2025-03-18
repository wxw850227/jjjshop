<template>
	<div class="common-header">
		<div class="breadcrumb">
			<!--一般的标题显示-->
			<div class="baseInfo-left-base d-s-c">
				<span class="name">{{ menu_title }}</span>
				<!--插件切换-->
				<div class="el-tabs-container">
					<el-tabs :model-value="activeValue" @tab-click="tabClick">
						<el-tab-pane :label="item.value" :name="item.key" v-for="(item, index) in tabList"
							:key="index"></el-tab-pane>
					</el-tabs>
				</div>
			</div>

			<div class="header-navbar">
				<div class="header-navbar-icon">
					<span class="gray">当前版本：{{ userInfo.version }}</span>
				</div>
				<div class="header-navbar-icon">
					<span class="ml4 icon iconfont icon-geren9"></span>
					<span class="text ml4 blue">{{ userInfo.userName }}，欢迎您！</span>
				</div>
				<div class="header-navbar-icon"><span class="gray">|</span></div>
				<div class="header-navbar-icon" @click="passwordFunc()">
					<span class="text">修改密码</span>
				</div>
				<div class="header-navbar-icon login-out" @click="exit()">
					<span class="icon iconfont icon-tuichu"></span>
					<span class="text ml4">退出</span>
				</div>
			</div>
		</div>

		<!--修改密码-->
		<UpdatePassword v-if="is_password" @close="closeFunc"></UpdatePassword>
	</div>
</template>

<script>
	import {
		reactive,
		toRefs,
		defineComponent,
		onBeforeUnmount
	} from "vue";
	import {
		useUserStore
	} from "@/store";
	import UserApi from '@/api/user.js';
	import {
		useRouter
	} from "vue-router";
	import UpdatePassword from "./part/UpdatePassword.vue";
	export default defineComponent({
		components: {
			UpdatePassword,
		},
		setup() {
			const router = useRouter();
			const {
				userInfo,
				bus_on,
				bus_emit,
				bus_off,
				afterLogout
			} = useUserStore();
			const state = reactive({
				/*菜单名称*/
				menu_title: "菜单",
				/*切换菜单*/
				tabList: [],
				/*切换选中*/
				activeValue: 0,
				/*是否修改密码*/
				is_password: false,
				/*tab切换类别*/
				tab_type: "",
			});
			bus_on("MenuName", (res) => {
				state.menu_title = res;
			});
			bus_on("tabData", (res) => {
				state.tabList = res.list;
				state.activeValue = res.active;
				state.tab_type = res.tab_type;
			});
			bus_on('activeValue', (res) => {
				if (res && res.params) {
					state.activeValue = res.params;
				} else {
					state.activeValue = res;
				}
			})
			bus_on('noTarget', (res) => {
				state.activeValue = res;
			})
			//发送给其它组件头部加载完成
			bus_emit("headLoad", true);
			onBeforeUnmount(() => {
				bus_off("menuName");
				bus_off("tabData");
			});
			return {
				...toRefs(state),
				userInfo,
				afterLogout,
				router,
				bus_emit,
			};
		},
		methods: {
			exit() {
				let self = this;
				ElMessageBox.confirm("此操作将退出登录, 是否继续?", "提示", {
						confirmButtonText: "确定",
						cancelButtonText: "取消",
						type: "warning",
					})
					.then(() => {
						UserApi.loginOut({}, true)
							.then(data => {
								self.logout();
							})
							.catch(error => {});
					})
					.catch(() => {
						ElMessage({
							type: "info",
							message: "已取消退出",
						});
					});
			},
			async logout() {
				await this.afterLogout();
				this.router.push("/login");
			},

			/*点击跳转*/
			tabClick(event) {
				let e = event.props;
				/*取号管理*/
				if (this.tab_type == "queuemanage") {
					this.router.push({
						path: "/plus/queue/index",
						query: {
							type: e.name,
						},
					});
				}
				/*桌位管理*/
				if (this.tab_type == "tablemanage") {
					this.router.push({
						path: "/store/table/index",
						query: {
							type: e.name,
						},
					});
				}
				/*外卖商品*/
				if (this.tab_type == "takeaway") {
					this.$router.push({
						path: "/product/takeaway/index",
						query: {
							type: e.name
						}
					});
				}
				/*店内商品*/
				if (this.tab_type == "storeproduct") {
					this.$router.push({
						path: "/product/store/index",
						query: {
							type: e.name
						}
					});
				}
				/*商品扩展*/
				if (this.tab_type == "expand") {
					this.$router.push({
						path: "/product/expand/index",
						query: {
							type: e.name
						}
					});
				}
				/*分销*/
				if (this.tab_type == "agent") {
					this.$router.push({
						path: "/plus/agent/index",
						query: {
							type: e.name
						}
					});
				}
				/*配送员*/
				if (this.tab_type == "driver") {
					this.$router.push({
						path: "/plus/driver/index",
						query: {
							type: e.name
						}
					});
				}
				/*优惠券*/
				if (this.tab_type == "coupon") {
					this.$router.push({
						path: "/plus/coupon/index",
						query: {
							type: e.name
						}
					});
				}
				/*积分商城*/
				if (this.tab_type == "points") {
					this.$router.push({
						path: "/plus/points/index",
						query: {
							type: e.name
						}
					});
				}
				/* 现时秒杀*/
				if (this.tab_type == "seckill") {
					this.$router.push({
						path: "/plus/seckill/index",
						query: {
							type: e.name
						}
					});
				}
				/* 限时拼团*/
				if (this.tab_type == "assemble") {
					this.$router.push({
						path: "/plus/assemble/index",
						query: {
							type: e.name
						}
					});
				}
				/* 砍价*/
				if (this.tab_type == "bargain") {
					this.$router.push({
						path: "/plus/bargain/index",
						query: {
							type: e.name
						}
					});
				}
				/*门店*/
				if (this.tab_type == "store") {
					this.$router.push({
						path: "/store/index",
						query: {
							type: e.name
						}
					});
				}
				/*门店*/
				if (this.tab_type == "takeout") {
					this.$router.push({
						path: "/takeout/index",
						query: {
							type: e.name
						}
					});
				}
				/*app设置*/
				if (this.tab_type == "appopen") {
					this.$router.push({
						path: "/appsetting/appopen/event",
						query: {
							type: e.name
						}
					});
				}
				this.activeValue = e.name;
				this.bus_emit("activeValue", e.name);
			},

			/*修改密码*/
			passwordFunc() {
				this.is_password = true;
			},

			/*关闭修改密码*/
			closeFunc() {
				this.is_password = false;
			},
		},
	});
</script>

<style lang="scss">
	.common-header .el-tabs__nav-wrap::after {
		display: none;
	}

	.common-header .el-tabs-container {
		margin-left: 20px;
		padding-left: 20px;
		border-left: 1px solid #eeeeee;
	}

	.common-header .el-tabs__header {
		margin-bottom: 0;
	}

	.login-out .icon-tuichu {
		color: red;
	}

	.header-navbar-icon .icon-geren9 {
		font-size: 20px;
	}
</style>