var app_url = 'http://www.jjj-shop-git.com';
//var app_url = 'https://small.jjjshop.net';
if(process.env.NODE_ENV === 'development'){
    //#ifdef H5
	app_url = '';
	//#endif
}

export default {
	/*服务器地址*/
	app_url: app_url,
	/*appid*/
	app_id: 10001,
	//h5发布路径
	h5_addr: '/h5',
} 