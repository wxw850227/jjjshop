/** vite的特殊性, 需要处理图片 */
export const require = (imgPath) => {
	try {
		const handlePath = imgPath.replace('@', '..');
		// https://vitejs.cn/guide/assets.html#the-public-directory
		return new URL(handlePath, import.meta.url).href;
	} catch (error) {
		console.warn(error);
	}
};