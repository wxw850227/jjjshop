import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import {
	ElementPlusResolver
} from 'unplugin-vue-components/resolvers';
import path from 'path';
import legacy from '@vitejs/plugin-legacy';
import viteCompression from 'vite-plugin-compression';
export default defineConfig(({ mode }) => {
  // 获取当前环境的配置
  const config = loadEnv(mode, './')
  return {
	base: process.env.NODE_ENV === 'production' ? './' : '/',
    server: {
      proxy: {
        '/api': {
          target: config.VITE_BASIC_URL,
          changeOrigin: true,
          rewrite: (path) => path.replace(/^\/api/, ''),
        }
      }
    },
	plugins: [
		viteCompression({
			verbose: true,
			disable: false,
			threshold: 10240,
			algorithm: 'gzip',
			ext: '.gz',
		}),
		vue(),
		legacy({
			targets: ['ie>=11'],
			additionalLegacyPolyfills: ['regenerator-runtime/runtime'],
		}),
		AutoImport({
			resolvers: [ElementPlusResolver()],
		}),
		Components({
			resolvers: [ElementPlusResolver()],
		}),
	],
	css: {
		preprocessorOptions: {
			// 全局样式引入
			// scss: {
			// 	additionalData: '@import "./static/scss/element.scss";@import "./static/scss/main.scss";',
			// 	javascriptEnabled: true,
			// }
		}
	},
	resolve: {
		alias: {
			'@': path.join(__dirname, './src'),
		},
	},
	build: {
		assetsDir: 'static',
		minify: 'terser',
		productionSouceMap: false,
		assetsPublicPath: '/admin/',
		rollupOptions: {
			output: {
				chunkFileNames: 'static/js/[name]-[hash].js',
				entryFileNames: 'static/js/[name]-[hash].js',
				assetFileNames: 'static/[ext]/[name]-[hash].[ext]',
				manualChunks(id) {
					if (id.includes('node_modules')) {
						return id.toString().split('node_modules/')[1].split('/')[0].toString();
					}
				}
			}
		},
		terserOptions: {
			compress: {
				drop_console: true,
				drop_debugger: true,
			},
		}
	}
  }
})