<template>
    <view>
        <form @submit="formSubmit" @reset="formReset">
            <view class="bg-white">
                <view class="d-s-c p30">
                    <text>收货人</text>
                    <input class="ml20" name="name" type="text" v-model="address.name" placeholder-class="grary"
                           placeholder="姓名"></input>
                </view>
                <view class="d-s-c p30">
                    <text>联系方式</text>
                    <input class="ml20" name="phone" type="text" v-model="address.phone" placeholder-class="grary"
                           placeholder="手机号码"></input>
                </view>
                <view class="d-s-c p30">
                    <text>所在地区</text>
                    <view class="input-box">
                        <input class="ml20" type="text" value="" placeholder-class="grary" placeholder=""
                               v-model="selectCity" disabled="true"
                               @click="showMulLinkageThreePicker"></input>
                    </view>
                </view>
                <view class="d-s-c p30">
                    <text>详细地址</text>
                    <textarea class="ml20 flex-1" name="detail" :auto-height="true" v-model="address.detail"
                              placeholder="请输入街道小区楼层"></textarea>
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
                selectCity: '选择省,市,区',
                province_id: 0,
                city_id: 0,
                region_id: 0,
                /*地址id*/
                address_id: 0,
                /*地址数据*/
                address: {},
                /*地区*/
                region: {},

            }
        },
        onLoad(e){
            this.address_id = e.address_id;
        },
        mounted(){
            /*获取地址数据*/
            this.getData();
        },
        methods: {

            /*获取数据*/
            getData(){
                let self = this;
                let address_id = self.address_id;
                self._get(
                    'user.address/detail',
                    {
                        address_id: address_id
                    },
                    function (res)
                    {
                        console.log(res);
                        self.address = res.data.detail;
                        self.address_id = res.data.detail.address_id;
                        self.province_id=res.data.detail.province_id;
                        self.city_id=res.data.detail.city_id;
                        self.region_id=res.data.detail.region_id;
                        self.region = res.data.region;
                        let add = '';
                        var b = self.region.forEach((item) =>
                        {
                            add += item;
                        })
                        self.selectCity = add;
                    }
                );
            },

            /*提交地址*/
            formSubmit: function (e)
            {
                let self = this;
                var formdata = e.detail.value;
                formdata.province_id = self.province_id;
                formdata.city_id = self.city_id;
                formdata.region_id = self.region_id;
                formdata.address_id = self.address_id;
                formdata.region = self.region;
                self._post('user.address/edit', formdata, function (res)
                {
                    self.showSuccess(res.msg,function(){
                    	uni.navigateBack();
                    });
                });
            },
            formReset: function (e)
            {
                console.log('清空数据')
            },
            // 三级联动选择
            showMulLinkageThreePicker() {
                this.$refs.mpvueCityPicker.show()
            },
            onConfirm(e) {
                console.log(JSON.stringify(e));
                this.selectCity = e.label;//JSON.stringify(e)
                this.province_id = e.cityCode[0];
                this.city_id = e.cityCode[1];
                this.region_id = e.cityCode[2];
            },
        }
    }
</script>

<style>

</style>
