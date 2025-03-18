import config from '../env/config.js'
/*导航菜单白名单*/
const tabBarLinks = [
	'/pages/index/index',
	'/pages/order/myorder',
	'/pages/user/index/index',
];

/*分享页面,扫码白名单*/
const shareLinks = [
	'/pages/plus/assemble/fight-group-detail/fight-group-detail',
	'/pages/plus/bargain/haggle/haggle',
	'/pages/user/invite/invite',
	'/pages/product/detail/detail',
	'/pages/user/address/edit/edit',
	'/pages/user/address/add/add',
	'/pages/index/index',
]

/*
 * 跳转页面
 */
export const gotopage = (url,type) => {
	if (!url || url.length == 0) {
		return false;
	}
	
	if(url.substr(0,1)!=='/'){
		url='/' + url;
	}
	let p = url;
	if(url.indexOf('?') != -1){
		p = url.substr(0, url.indexOf('?'));
	}
	// tabBar页面
	if (tabBarLinks.indexOf(p) > -1) {
		uni.reLaunch({
			url: url
		})
	} else {
		if(process.env.NODE_ENV === 'production'){
			//判断是否分享页面
			if (shareLinks.indexOf(p) > -1) {
				//公众号
				// #ifdef  H5
				if(config.app_url.search(/h5/) < 0){
					window.location.href = config.app_url + config.h5_addr + url;
				}
				return;
				// #endif
			}
		}
		if (type == 'redirect') {
			uni.redirectTo({
				url: url
			});
			return
		}
		if (type == 'reLaunch') {
			uni.reLaunch({
				url: url
			});
			return
		}
		// 普通页面
		uni.navigateTo({
			url: url
		});
	}
}
