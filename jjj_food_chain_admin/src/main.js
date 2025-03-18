import { createApp } from 'vue';
import router from './router';
import { createPinia } from 'pinia';
const pinia = createPinia();
import { setupRouter } from '@/router';
import App from './App.vue';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';
import filters from '@/filters/index'

const app = createApp(App);
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
	app.component(key, component);
}
app.config.globalProperties.$filter = filters;
app.use(pinia);
app.use(router);
app.mount('#app');
setupRouter(app);
