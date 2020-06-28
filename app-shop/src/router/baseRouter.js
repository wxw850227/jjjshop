/*基础页面路由*/
let baserouter = [
    /*登录页面*/
    {
        path: '/login',
        name: 'Login',
        meta: {
            title: '登录'
        },
        component: () =>
            import('@/views/Login')
    },
    {
        path: '/',
        redirect: {
            name: 'Home'
        },
        meta: {
            title: '母版'
        },
        component: () =>
            import('@/views/layout/Main'),
        children: [
            /*后台首页*/
            {
                path: '/home',
                name: 'Home',
                meta: {
                    title: '首页'
                },
                component: () =>
                    import('@/views/home/Home')
            },
        ]
    },
    /*测试页面*/
    {
        path: '/test',
        name: 'Test',
        meta: {
            title: '测试'
        },
        component: () =>
            import('@/views/help/Test')
    },
    /*字体图标查找页面*/
    {
        path: '/fonticon',
        name: 'Fonticon',
        meta: {
            title: '字体图标'
        },
        component: () =>
            import('@/views/help/Fonticon')
    }
];

/*错误页面路由*/
export const errpage = [{
  path: '*',
  name: 'Page404',
  meta: {
    title: '错误页面'
  },
  component: () =>
    import('@/views/error-page/404')
}]

export default baserouter;
