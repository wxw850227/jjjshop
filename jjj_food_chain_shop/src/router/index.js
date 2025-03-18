import { createRouter, createWebHashHistory } from 'vue-router';
import {
	setupPermissions
} from './permissions';
export const constantRoutes = [{
		path: '/login',
		name: 'login',
		meta: {
			title: '登录'
		},
		component: () => import('@/views/login/index.vue'),
	},
	{
		path: '/',
		redirect: '/home',
		meta: {
			title: '登录'
		},
	},
	{
		path: '/home',
		name: 'Home',
		meta: {
			title: '管理台'
		},
		component: () => import('@/views/layout/main.vue'),
		children: [
			{
				path: '/home',
				name: 'HomeIndex',
				meta: {
					title: '首页',
					topTree: '/home'
				},
				component: () => import('@/views/home/home.vue'),
			}, 
		]
	},
];
const router = createRouter({
	history: createWebHashHistory(),
	routes: constantRoutes,
});
export function setupRouter(app) {
	setupPermissions(router);
	app.use(router);
	return router;
}

export default router;