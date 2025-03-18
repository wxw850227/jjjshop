import router from './index.js'
// let  modules = import.meta.glob('../views/*/*/*.vue')
let  modules = import.meta.glob(['../views/*.vue','../views/*/*.vue','../views/*/*/*.vue', '../views/*/*/*/*.vue', '../views/*/*/*/*/*.vue'])
let count = 0;
const dealWithRoute = async (data, parent = 'Menu') => {
    for (let item of data) {
        count = count + 1;
        item.component = modules[`../views${item.path}.vue`];
        router.addRoute(parent, item)
        if(item.children && item.children.length > 0){
            dealWithRoute(item.children)
        }
    }
};

export default dealWithRoute;
