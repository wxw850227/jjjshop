/**
 * @description 路由守卫，目前两种模式：all模式与intelligence模式
 */
import { useUserStore } from '@/store';
// import dealWithRoute from './dealWithRoute.js';
export async function setupPermissions(router) {
	let load = 0;
	router.beforeEach(async (to, from, next) => {
		const { token } = useUserStore(); 
		const whiteList = ['/login'];
		if (!token) {
			if (whiteList.includes(to.path)) {
				next();
				return;
			}
			next('/login');
		} else {
			if (to.path == '/login') {
				next({
					path: '/Home'
				});
				return;
			}
			// if (menus && load == 0) {
			if (load == 0) {
				load++;
				console.log("kkkk")
				// dealWithRoute(menus);
				next({
					...to,
					replace: true
				});
				return;
			}
			console.log("走了",load)
			next();
		}
	});

}