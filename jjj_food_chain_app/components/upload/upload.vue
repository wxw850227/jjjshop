<template>
	<view></view>
</template>

<script>
export default {
	data() {
		return {
			/*需要返回的图片*/
			imageList:[]
		};
	},
	onLoad() {},
	mounted() {
		this.chooseImageFunc();
	},
	methods: {
		
		/*打开相机或者相册，选择图片*/
		chooseImageFunc() {
			let self=this;
			uni.chooseImage({
				count: 9, //默认9
				sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
				sourceType: ['album'], //从相册选择
				success: function(res) {
					//console.log(JSON.stringify(res.tempFilePaths));
					//let imgs=JSON.stringify(res.tempFilePaths);
					self.uploadFile(res.tempFilePaths);
				},
				fail:function(res){
					self.$emit('getImgs',null);
				},
				complete:function(res){
					
				}
			});
		},
		
		/*上传图片*/
		uploadFile: function(tempList) {
			let self = this;
			let i = 0;
			let img_length=tempList.length;
			let params = {
				token: uni.getStorageSync('token'),
                app_id: self.getAppId()
			};
			uni.showLoading({
				title:'上传中'
			})
			tempList.forEach(function(filePath, fileKey) {
				uni.uploadFile({
					url: self.websiteUrl + '/index.php?s=/api/file.upload/image',
					filePath: filePath,
					name: 'iFile',
					formData: params,
          header: {
            'appId': self.getAppId()
          },
					success: function(res) {
						let result = typeof res.data === 'object' ? res.data : JSON.parse(res.data);
						if (result.code === 1) {
							self.imageList.push(result.data);
						}
					},
					complete: function() {
						i++;
						if (img_length === i) {
							uni.hideLoading()
							// 所有文件上传完成
							self.$emit('getImgs',self.imageList);
						}
					}
				});
			});
		}
	}
};
</script>

<style></style>
