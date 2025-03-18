/*
 * 设置cookie
 */
export const setCookie = (name, value, expiredays) => {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = name + '=' + escape(value) + ((expiredays == null) ? '' : ';expires=' + exdate
		.toGMTString()) + ';path=/';
};

/*
 * 获取cookie
 */
export const getCookie = (name) => {
	var arr, reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
	if (arr = document.cookie.match(reg)) {
		return unescape(arr[2]);
	} else {
		return null;
	}
};

/*
 * 删除cookie
 */
export const delCookie = (name) => {
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval = getCookie(name);
	if (cval != null)
		document.cookie = name + '=' + cval + ';expires=' + exp.toGMTString() + ';path=/';
};

/*
 * 设置sessionStorage
 */
export const setSessionStorage = (name, val) => {
	sessionStorage.setItem(name, JSON.stringify(val));
};

/*
 * 通过参数获取sessionStorage的数据
 */
export const getSessionStorage = (name) => {
	if (sessionStorage.hasOwnProperty(name)) {
		return JSON.parse(sessionStorage.getItem(name));
	} else {
		return false;
	}
};

/*
 * 附加sessionStorage, type==true 则是整个修改，
 */
export const addSessionStorage = (name, val) => {
	if (sessionStorage.hasOwnProperty(name)) {
		let old = JSON.parse(sessionStorage.getItem(name));
		let obj = Object.assign(old, val);
		sessionStorage.setItem(name, JSON.stringify(obj));
	} else {
		sessionStorage.setItem(name, JSON.stringify(val));
	}
};

/*
 * 删除sessionStorage
 */
export const deleteSessionStorage = (name = null) => {
	if (name != null) {
		sessionStorage.removeItem(name);
	} else {
		sessionStorage.clear();
	}
};

/*
 * 设置localStorage
 */
export const setLocalStorage = (name, val) => {
	localStorage.setItem(name, JSON.stringify(val));
};

/*
 * 通过参数获取localStorage的数据
 */
export const getLocalStorage = (name) => {
	if (localStorage.hasOwnProperty(name)) {
		return JSON.parse(localStorage.getItem(name));
	} else {
		return false;
	}
};

/*
 * 附加localStorage, type==true 则是整个修改，
 */
export const addLocalStorage = (name, val) => {
	if (localStorage.hasOwnProperty(name)) {
		let old = JSON.parse(localStorage.getItem(name));
		let obj = Object.assign(old, val);
		localStorage.setItem(name, JSON.stringify(obj));
	} else {
		localStorage.setItem(name, JSON.stringify(val));
	}
};

/*
 * 删除localStorage
 */
export const deleteLocalStorage = (name = null) => {
	if (name != null) {
		localStorage.removeItem(name);
	} else {
		localStorage.clear();
	}
};

/*
 *深拷贝
 */
export const deepClone = (obj) => {
	var objClone = Array.isArray(obj) ? [] : {};
	if (obj && typeof obj === 'object') {
		for (var key in obj) {
			if (obj.hasOwnProperty(key)) {
				if (obj[key] && typeof obj[key] === 'object') {
					objClone[key] = deepClone(obj[key]);
				} else {
					objClone[key] = obj[key];
				}
			}
		}
	}
	return objClone;
};


/*
 *深合并
 */
export const deepMerger = (obj1, obj2) => {
	for (var key in obj2) {
		if (obj2.hasOwnProperty(key)) {
			if (obj2[key] && typeof obj2[key] === 'object') {
				obj1[key] = deepMerger(obj1[key], obj2[key]);
			} else {
				obj1[key] = obj2[key];
			}
		}
	}
	return obj1;
};

/*
 *格式
 */
export const formatModel = (thisObj, sourceObj) => {
	for (var key in thisObj) {
		if (sourceObj && typeof(sourceObj[key]) != 'undefined') {
			if (thisObj[key] && Object.prototype.toString.call(thisObj[key]) === '[object Object]') {
				formatModel(thisObj[key], sourceObj[key]);
			} else {
				thisObj[key] = sourceObj[key];
			}
		}
	}
	return thisObj;
};