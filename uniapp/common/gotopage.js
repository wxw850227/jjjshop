
const tabBarLinks = [
  'pages/index/index',
  'pages/product/category',
  'pages/cart/cart',
  'pages/user/index/index'
];

/*
 * 跳转页面
 */
export const gotopage = (url) => {
	if (!url || url.length == 0) {
		return false;
	}
	// tabBar页面
	if (tabBarLinks.indexOf(url) > -1) {
		uni.switchTab({
			url: '/' + url
		});
	} else {
		// 普通页面
		uni.navigateTo({
			url: '/' + url
		});
	}
}
