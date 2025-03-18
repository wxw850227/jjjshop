/**
 * @description 字符串去除/ 拼接成字符串，用于导航name拼接
 * @param value
 * @returns {boolean}
 */
function strReplace(str) {
	let strList = Array.from(str);
	if (strList && strList.length > 0) {
		strList.forEach((v, idx) => {
			if (v == '/') {
				strList.splice(idx, 1);
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
let newMenuChirdlen = [];

function handMenu(data, outsideData, type, parentObj) {
	if (type == 1) {
		newMenuChirdlen = [];
		let obj = JSON.parse(JSON.stringify(data));
		delete obj.children;
		newMenuChirdlen.push(obj);
	}
	if (data.children && data.children.length > 0) {
		data.children.forEach((v) => {
			v.meta = {
				title: v.name,
				topTree: outsideData.path
			};
			if (v.isRoute == 2) {
				v.meta.parentPage = data.path;
			}
			v.name = strReplace(v.path);
			let obj1 = JSON.parse(JSON.stringify(v));
			delete obj1.children;
			newMenuChirdlen.push(obj1);
			if (v.children && v.children.length > 0) {
				handMenu(v.children, outsideData, null, v);
			}
		});
	} else {
		data.forEach((v) => {
			v.meta = {
				title: v.name,
				topTree: outsideData.path
			};
			if (v.isRoute == 2) {
				v.meta.parentPage = parentObj.path;
			}
			v.name = strReplace(v.path);
			newMenuChirdlen.push(v);
		});
	}
	return newMenuChirdlen;
}
/**
 * @description 加入路由表的数据
 * @returns
 */
export function handRouterTable(menus) {
	let list = [];
	menus.forEach((items) => {
		items.meta = {
			title: items.name,
			topTree: items.path
		};
		items.name = strReplace(items.path);
		if (items.children && items.children.length > 0) {
			list = [];
			items.children.forEach((item) => {
				item.meta = {
					title: item.name,
					topTree: items.path
				};
				item.name = strReplace(item.path);
				if (item.children && item.children.length > 0) {
					list = list.concat(handMenu(item, items, 1));
				} else {
					item.name = strReplace(item.path);
					list.push(item);
				}
			});
			items.children = list;
		}
	});
	return menus;
}
/**
 * @description 显示导航数据处理
 * @param value
 * @returns {boolean}
 */
export function handMenuData(data) {
	// console.log(data)
	if (data && data.length > 0) {
		data.forEach((v, idx) => {
			if (v.is_route != 1 || v.is_menu != 1) {
				data.splice(idx, 1);
			}
			if (v.children && v.children.length > 0) {
				handMenuData(v.children);
			}
		});
	}
	return data;
}