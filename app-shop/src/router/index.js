import Vue from 'vue'
import Router from 'vue-router'
import baserouter from './baseRouter.js'

const originalPush = Router.prototype.push
Router.prototype.push = function push(location, onResolve, onReject)
{
    if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
    return originalPush.call(this, location).catch(err => err)
}

Vue.use(Router)

/*创建路由方法*/
const createRouter = () => new Router({
    scrollBehavior: () => ({y: 0}),
    routes: baserouter
})

/*创新新路由*/
const router = createRouter()
/*重置路由*/
export function resetRouter()
{
    const newRouter = createRouter()
    router.matcher = newRouter.matcher
}

export function getBaseRouter()
{
    return baserouter;
}

export default router;
