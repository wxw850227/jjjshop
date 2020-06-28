<template>
	<view>
		<form @submit="formSubmit" @reset="formReset">
			<view class="bg-white">
				<view class="d-s-c p30">
					<text>收货人</text>
					<input class="ml20" name="name" type="text" value="" placeholder-class="grary" placeholder="姓名" />
				</view>
				<view class="d-s-c p30">
					<text>联系方式</text>
					<input class="ml20" name="phone" type="text" value="" placeholder-class="grary" placeholder="手机号码" />
				</view>
				<view class="d-s-c p30">
					<text>所在地区</text>
					<view class="input-box">
						<input class="ml20" type="text" value="" placeholder-class="grary" placeholder="" v-model="selectCity" disabled="true"
						 @click="showMulLinkageThreePicker" />
					</view>
				</view>
				<view class="d-s-c p30">
					<text>详细地址</text>
					<textarea class="ml20 flex-1" name="detail" :auto-height="true" value="" placeholder="请输入街道小区楼层" />
					</view>
	    	</view>
				<view class="p30">
					<button type="primary" form-type="submit" class="btn-red f30 mt30">确认</button>
				</view>
	  </form>
	  <mpvue-city-picker :themeColor="themeColor" ref="mpvueCityPicker" :pickerValueDefault="cityPickerValueDefault"
	   @onConfirm="onConfirm"></mpvue-city-picker>
	</view>
</template>

<script>
	import mpvueCityPicker from '@/components/mpvue-citypicker/mpvueCityPicker.vue'
	export default {
		components: {
			mpvueCityPicker
		},
		data() {
			return {
				cityPickerValueDefault: [0, 0, 0],
				selectCity:'选择省,市,区',
				province_id:0,
				city_id:0,
				region_id:0,
			}
		},
		methods: {
			   formSubmit: function(e) {
					let self = this;
					var formdata = e.detail.value;
					formdata.province_id = self.province_id;
					formdata.city_id = self.city_id;
					formdata.region_id = self.region_id;
					 self._post('user.address/add', formdata, function(res) {
						 self.showSuccess(res.msg,function(){
							 uni.navigateBack();
						 });
					 });
			     },
			     formReset: function(e) {
			         console.log('清空数据')
			      },
				  // 三级联动选择
				  showMulLinkageThreePicker() {
				  	this.$refs.mpvueCityPicker.show()
				  },
				  onConfirm(e) {
				  	this.selectCity = e.label;
				  	this.province_id = e.cityCode[0];
				  	this.city_id = e.cityCode[1];
				  	this.region_id = e.cityCode[2];
				  },
		}
	}
</script>

<style>

</style>
