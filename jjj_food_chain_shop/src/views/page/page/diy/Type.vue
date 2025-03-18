<template>
	<div v-if="typeList != null">
		<div class="min-group">
			<div style="border: none;" :name="key" v-for="(group, key) in typeList" :key="key">
				<div class="hd">{{ typename(key) }}</div>
				<div class="bd">
					<div class="item" v-for="(item, index) in group.children" :key="index" @click="$parent.onAddItem(item.type)">
						<p class="p-icon icon iconfont " :class="item.icon"></p>
						<p class="p-txt">{{ item.name }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			/*类别列表*/
			typeList:null,
			activeName: 0
		};
	},
	props: {
		defaultData: Object
	},
	created() {
		this.init();
	},
	methods: {
		typename(type){
			let name='';
			if(type=='media'){
				name='媒体组件';
			}else if(type=='shop'){
				name='平台组件';
			}else if(type=='tools'){
				name='工具组件';
			}
			return name;
		},
		/*初始化数据*/
		init() {
			let tempList={};
			for(let key in this.defaultData){
				let item=this.defaultData[key];
				if(!tempList.hasOwnProperty(item.group)){
					tempList[item.group]={};
					tempList[item.group].children=[];
				}
				tempList[item.group].children.push(item);
			}
			this.typeList=tempList;
			console.log('type',this.typeList);
		}
	}
};
</script>

<style scoped>
	.diy-container .min-group{
		padding-top: 10px;
	}
	.diy-container .min-group .hd{
		color: #333;
		padding: 0 20px;
	}
	.diy-container .min-group .hd::after{
		content: none;
	}
	.diy-container .min-group .bd{
		padding-top: 0;
	}
</style>
