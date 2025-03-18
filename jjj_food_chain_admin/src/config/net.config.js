export default {
	baseURL: process.env.NODE_ENV === 'development' ? '/api/index.php' : `${import.meta.env.VITE_BASIC_URL}/index.php`,
	tokenName: 'token',
	strongToken: "jjjSingleAdminToken",
	isDev: process.env.NODE_ENV === 'development' ? true : false,
	contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
	requestTimeout: 50000,
	statusName: 'code',
	messageName: 'msg',
	withCredentials: true,
	responseType: 'json',
};