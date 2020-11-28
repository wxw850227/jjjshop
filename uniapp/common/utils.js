/**
 * 工具类
 */
var utils = {
	/**
	 * scene解码
	 */
	scene_decode(e) {
		if (e === undefined)
			return {};
		let scene = decodeURIComponent(e),
			params = scene.split(','),
			data = {};
		for (let i in params) {
			var val = params[i].split(':');
			val.length > 0 && val[0] && (data[val[0]] = val[1] || null)
		}
		return data;
	},

	/**
	 * 格式化日期格式 (用于兼容ios Date对象)
	 */
	format_date(time) {
		// 将xxxx-xx-xx的时间格式，转换为 xxxx/xx/xx的格式 
		return time.replace(/\-/g, "/");
	},
	
	/**
	 * 格式化详情内容,去除图片之间的间隙，图片宽度最大100%
	 */
	format_content(str) {
		return str.replace(/\<img/gi, '<img style="display:block; margin:0 auto; max-width:100%;" ');
	},

	/**
	 * 对象转URL
	 */
	urlEncode(data) {
		var _result = [];
		for (var key in data) {
			var value = data[key];
			if (value.constructor == Array) {
				value.forEach(_value => {
					_result.push(key + "=" + _value);
				});
			} else {
				_result.push(key + '=' + value);
			}
		}
		return _result.join('&');
	},

	/**
	 * 遍历对象
	 */
	objForEach(obj, callback) {
		Object.keys(obj).forEach((key) => {
			callback(obj[key], key);
		});
	},

	/**
	 * 是否在数组内
	 */
	inArray(search, array) {
		for (var i in array) {
			if (array[i] == search) {
				return true;
			}
		}
		return false;
	},

	/**
	 * 判断是否为正整数
	 */
	isPositiveInteger(value) {
		return /(^[0-9]\d*$)/.test(value);
	},

	/**
	 * 安全获取对象子集
	 */
	safeGet(data, path) {
		if (data == undefined) return undefined;

		const pathArr = path.split('.');
		let result = data;

		for (let i = 0; i < pathArr.length; i++) {
			if (result[pathArr[i]] == undefined) return undefined;
			result = result[pathArr[i]];
		}
		return result
	},
	/**
	 * 获取场景值(scene)
	 */
	getSceneData(query) {
		return query.scene ? this.scene_decode(query.scene) : {};
	},

};
export default utils;
