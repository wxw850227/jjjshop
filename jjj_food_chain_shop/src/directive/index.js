import {
	defaultImg
} from '../config/env.js'
import {
	getSessionStorage,
	setSessionStorage
} from '@/utils/base.js'
import {
	getStorage
} from '@/utils/storageData';
import {
	createdAuth
} from '@/utils/createdAuth.js'
import configObj from "@/config"; 
let { menu } = configObj;
/** 挂载自定义指令 */
export function loadDirectives(app) {
	/*指令测试*/
	app.directive('demo', {
		bind: function(el, binding, vnode) {
			var s = JSON.stringify
			el.innerHTML =
				'name: ' + s(binding.name) + '<br>' +
				'value: ' + s(binding.value) + '<br>' +
				'expression: ' + s(binding.expression) + '<br>' +
				'argument: ' + s(binding.arg) + '<br>' +
				'modifiers: ' + s(binding.modifiers) + '<br>' +
				'vnode keys: ' + Object.keys(vnode).join(', ')
		}
	})

	/*权限*/
	app.directive('auth', {
		mounted(el, binding, vnode) {
			// console.log(binding.value)
			let auth = getSessionStorage('authlist');
			if (!(auth && JSON.stringify(auth) !== "{}")) {
				let authlist = {}
				auth = getStorage(menu);
				createdAuth(auth, authlist);
				setSessionStorage('authlist', authlist);
				console.log(authlist)
				auth = authlist;
			}
			let value = binding.value.toLowerCase();
			if (auth[value] != true) {
				el.style.display = "none";
			}
		}
	})

	/*默认图片*/
	app.directive('img-url', async function(el, binding) {
		let imgURL = '';
		if (binding.value instanceof Object) {
			let jsonStr = binding.expression.split(',')[0].replace(/\'/g, '');
			imgURL = checkChild(binding.value, jsonStr);
		} else {
			imgURL = binding.value;
		}

		if (imgURL && typeof(imgURL) != 'undefined') {
			let exist = await imageIsExist(imgURL);
			if (exist) {
				el.setAttribute('src', imgURL);
			} else {
				el.setAttribute('src', defaultImg);
			}
		} else {
			el.setAttribute('src', defaultImg);
		}
	})
}





/*检查子对象是否为NUll*/
let checkChild = function(obj, str) {
	let _img = null;
	let arr = str.match(/\./g);
	if (!arr) {
		_img = obj[str];
	} else {
		let _i = str.indexOf('.');
		let key = str.substring(0, _i);
		let newobj = obj[key];
		if (newobj instanceof Object == true) {
			let newstr = str.substring(_i + 1);
			_img = checkChild(newobj, newstr);
		}
	}
	return _img;
}

/* 检测图片是否存在*/
let imageIsExist = function(url) {
	return new Promise((resolve) => {
		var img = new Image();
		img.onload = function() {
			if (this.complete == true) {
				resolve(true);
				img = null;
			}
		}
		img.onerror = function() {
			resolve(false);
			img = null;
		}
		img.src = url;
	})
}