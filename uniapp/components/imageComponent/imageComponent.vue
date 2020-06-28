<template>
	<image :src="url" :mode="mode"></image>
</template>

<script>
	
	export default{
		data(){
			return{
				url:''
			}
		},
		props:{
			imgurl: {
				type: String,
				default: ''
			},
			mode: {
				type: String,
				default: 'widthFix'
			},
		},
		watch:{
			'imgurl':function(n,o){
				if(n!=o){
					this.filterImg(n);
				}
			}
		},
		created() {
			this.filterImg(this.imgurl);
		},
		methods:{
			
			filterImg:async function(imgURL){
				let self=this;
				if (imgURL) {
				    let exist = self.imageIsExist(imgURL);
					exist.then((res)=>{
						if(res){
							self.url= imgURL;
						}else{
							self.url=self.config.defaultImg;
						}
					},()=>{
						self.url=self.config.defaultImg;
					});
				}else{
					self.url=self.config.defaultImg;
				}
			},
			
			/**
			 * 检测图片是否存在
			 * @param url
			 */
			imageIsExist(url) {
			    return new Promise((resolve,reject) => {
					
					// #ifndef H5
						uni.getImageInfo({
							src: url,
							success: function (image) {
								resolve(true);
							},
							fail:function(err){
								resolve(false);
							}
						});
					// #endif
					
					// #ifdef H5
					    var img = new Image();
					    img.onload = function () {
					        if (this.complete == true){
					            resolve(true);
					            img = null;
					        }else{
					    		resolve(false);
					    	}
					    }
					    img.onerror = function () {
					        resolve(false);
					        img = null;
					    }
					    img.src = url;
					// #endif
			        
			    })
			}
		}
	}
</script>

<style>
	image{ width: 100%; height: 100%;}
</style>
