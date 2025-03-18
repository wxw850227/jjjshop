function createdAuth(list, obj) {
	console.log(list)
	if(list && list.length > 0){
		for (let i = 0; i < list.length; i++) {
			let item = list[i];
			if (item.path && typeof(item.path) != 'undefined' && item.path != '') {
				let _path = item.path.toLowerCase();
				obj[_path] = true;
			}
			if (Object.prototype.toString.call(item.children) == '[object Array]' && item.children.length > 0) {
				createdAuth(item.children, obj);
			}
		}
	}
}

export {
	createdAuth
}