import { defineConfig } from 'vite';
import uni from '@dcloudio/vite-plugin-uni'
import dev_url from './env/development.js';
export default defineConfig({
	plugins: [uni()],
	server: {
		proxy: {
			'/api': {
				target: dev_url.url,//线上
				changeOrigin: true,
				rewrite: (path) => path.replace(/^\/api/, ''),
			},
		},
		host: "0.0.0.0",
		port: 8080, // 端口号
		https: false, // https:{type:Boolean}
	},

});

