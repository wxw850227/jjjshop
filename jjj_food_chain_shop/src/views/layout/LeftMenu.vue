<template>
	<div class="left-menu-wrapper">
		<!--主菜单-->
		<div class="menu-wrapper">
			<div class="home-login">
				<div :class="active_menu != null ? 'home-icon' : 'home-icon router-link-active'"
					@click="choseMenu(1,null, null)">
					<!-- <span class="icon iconfont icon-tubiaozhizuomoban-" v-if="!userInfo.logoUrl"></span> -->
					<img :src="userInfo.logoUrl" class="logoImg" />
				</div>
			</div>
			<div class="d-c-c">
				<span class="p-10-0 blue fb tc">{{ userInfo.shopName || '点餐系统连锁总店'}}</span>
			</div>
			<div class="nav-wrapper mt10">
				<div class="first-menu-content">
					<ul class="nav-ul">
						<template v-for="(item, index) in menuList" :key="index">
							<li :class="active_menu == index ? 'menu-item router-link-active' : 'menu-item'"
								@click="choseMenu(2,item, index)" v-if="item.is_menu==1">
								<div class="item-box">
									<span :class="'icon iconfont menu-item-icon ' + item.icon"></span>
									<span>{{ item.name }}</span>
								</div>
							</li>
						</template>
					</ul>
				</div>
			</div>
		</div>
		<!--子菜单-->
		<div class="child-menu-wrapper">
			<div class="child-menu right-animation">
				<ul v-if="active_menu != null">
					<template v-for="(item, index) in menuList[active_menu]['children']" :key="index">
						<li :class="active_child == index ? 'routre-link router-link-active' : 'router-link'" @click="choseMenu(3,item, index)" v-if="item.is_menu==1">
							<span class="name">{{ item.name }}</span>
						</li>
					</template>
				</ul>
			</div>
		</div>
	</div>
</template>

<script>
import {
	useUserStore
} from '@/store';
import {
	reactive,
	toRefs,
	defineComponent,
	nextTick,
	watch
} from 'vue';
import {
	useRoute,
	useRouter
} from 'vue-router';
export default defineComponent({
	components: {},
	setup(props, {
		emit
	}) {
		const {
			userInfo,
			bus_emit,
			menus,
			renderMenus,
		} = useUserStore();
		const route = useRoute();
		const router = useRouter();
		const state = reactive({
			route,
			/*传到顶部的标题*/
			munu_name: '首页',
			/*选中的菜单*/
			active_menu: null,
			/*子菜单选择*/
			active_child: 0,
			/*菜单数据*/
			menuList: renderMenus,
			/*商城名称*/
			shop_name: '',
			menus
		});

		/*菜单*/
		const selectMenu = (to) => {
			let menupath = to.path;
			let active_menu = null;
			let active_child = null;
			if(state.menuList && state.menuList.length > 0){
				for(let i = 0; i < state.menuList.length; i++){
					if(state.menuList[i].path == menupath){
						active_menu = i;
						break;
					}
					for(let j = 0; j < state.menuList[i].children.length; j++){
						if(state.menuList[i].children[j].path == menupath){
							active_menu = i;
							active_child = j;
							break;
						}
					}
					if(!active_menu && !active_child){
						if(state.menuList[i].childrenList.includes(menupath)){
							active_menu = i;
							break;
						}
					}
				}
				state.active_menu = active_menu;
				state.active_child = active_child;
				emit("selectMenu", active_menu);
			}
			nextTick(()=>{
				bus_emit("MenuName", to.meta && to.meta.showMenuTitle || to.meta.title); 
			})
		};
		watch(
					() => router.currentRoute.value,
					(to) => {
						selectMenu(to);
					}, {
						immediate: true
					}
				)
		selectMenu(route);
		return {
			...toRefs(state),
			userInfo,
			selectMenu,
			bus_emit
		};
	},
	mounted(){},
	methods: {
		/*点击菜单跳转*/
		choseMenu(type,item,index) {
			if(type == 1){
				this.active_menu = null;
				this.active_child = null;
				this.$router.push('/');
				this.$emit('selectMenu', null);
        		// this.bus_emit('MenuName', '首页');
			}else if(type == 2){
				this.active_menu = index;
				this.active_child = 0;
				this.$router.push(item.redirect_name);
        		// this.bus_emit('MenuName', item.name);
				if(item.children){
					this.$emit('selectMenu', false);
				}
			}else if(type == 3){
				console.log("item",item)
				let path = item.path;
				if(item.redirect_name){
					path = item.redirect_name;
				}
				this.active_child = index;
        		// this.bus_emit('MenuName', item.name);
				this.$router.push(path);
			}
		}
	}
});
</script>
<style>
	.home-login .icon-tubiaozhizuomoban- {
		color: #3a8ee6;
		font-size: 28px;
	}
	
	.logoImg{
		width: 100%;
	}
	
	.menu-item-icon.icon.iconfont {
		font-size: 20px;
	}
	
	.menu-item .item-box {
		display: flex;
	}
</style>