/**
 * @description 字符串去除/ 拼接成字符串，用于导航name拼接
 * @param value
 * @returns {boolean}
 */
function strReplace(str){
	let strList = Array.from(str);
	if(strList && strList.length > 0){
		strList.forEach((v,idx)=>{
			if(v == '/'){
				strList.splice(idx,1);
			}
		});
		return strList.join('');
	}
	return '';
}
/**
 * @description 导航数据处理
 * @param value
 * @returns {boolean}
 */
function handMenu(data,arr){
	if(data && data.length > 0){
		data.forEach((v)=>{
			v.meta = { title: v.name,topTree: v.path };
			v.name = strReplace(v.path);
			const { access_id, icon, is_menu, is_route, is_show, meta, redirect_name, path} = v;
			arr.push({
				access_id,
				icon,
				is_menu,
				is_route,
				is_show,
				meta,
				path,
				redirect_name,
			})
			handMenu(v.children,arr);
		})
	}
	return arr
}
/**
 * @description 加入路由表的数据
 * @returns
 */
export function handRouterTable(menus) {
	let menuList = [];
	if(menus && menus.length > 0){
		menus.forEach((items,indexs)=>{
			let list = [];
			items.meta = { title: items.name,topTree: items.path };
			items.name = strReplace(items.path);
			let topTree = items.path;
			const { access_id, icon, is_menu, is_route, is_show, meta, redirect_name, path } = items;
			list.push({
				access_id,
				icon,
				is_menu,
				is_route,
				is_show,
				meta,
				redirect_name,
				path,
				topTree,
			})
			if(items.children && items.children.length > 0){
				items.children.forEach((v)=>{
					v.meta = { title: v.name,topTree: items.path };
					v.name = strReplace(v.path);
					let topTree = items.path;
					const { access_id, icon, is_menu, is_route, is_show, meta, redirect_name, path} = v;
					list.push({
						access_id,
						icon,
						is_menu,
						is_route,
						is_show,
						meta,
						redirect_name,
						path,
						topTree,
					})
				})
				list = list.concat(handMenu(items.children,[]));
			}
			menuList = menuList.concat(list)
			list = [];
		})
	}
	console.log('menus-----------',menuList)
	return menuList;
}
/**
 * @description 显示导航数据处理
 * @param value
 * @returns {boolean}
 */
export function handMenuData(data){
	let showMenuData = [];
	if(data && data.length > 0){
		let childrenList = [];
		data.forEach((v,idx)=>{
			const { name, path, redirect_name, app_id, alias, is_menu, icon } = v;
			showMenuData.push({
				name,
				path,
				redirect_name,
				app_id,
				alias,
				is_menu,
				icon,
			})
			if(v.children && v.children.length > 0){
				v.children.forEach((item)=>{
					const { name, path, redirect_name, app_id, alias, is_menu, icon } = item;
					showMenuData[idx].children = showMenuData[idx].children || [];
					showMenuData[idx].children.push({
						name,
						path,
						redirect_name,
						app_id,
						alias,
						is_menu,
						icon,
					})
					childrenList = [].concat(getChildrenList(v.children,[]));
				})
			}
			childrenList.push(v.path)
			showMenuData[idx].childrenList = childrenList;
		});
	}
	return showMenuData;
}

function getChildrenList(data,arr){
	data.forEach((v)=>{
		arr.push(v.path)
		if(v.children && v.children.length > 0){
			getChildrenList(v.children,arr);
		}
	})
	return arr
}