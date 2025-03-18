<template>
	<el-dialog title="选择经纬度" width="50%" align-center v-model="dialogVisible" @close="dialogFormVisible"
		:close-on-click-modal="false" :close-on-press-escape="false">
		<Getpoint :form="form" :tx_key="tx_key" @chose="choseFunc"></Getpoint>
		<el-form size="small" class="mt16">
			<el-form-item label="地址" >
				<el-input class="max-w460" disabled v-model="form.address" placeholder=""></el-input>
			</el-form-item>
			<el-form-item label="坐标" >
				<el-input class="max-w460" disabled v-model="form.coordinate" placeholder=""></el-input>
			</el-form-item>
		</el-form>

		<!-- <el-form-item label="纬度" >
			<el-input class="max-w460" disabled v-model="form.lat" placeholder=""></el-input>
		</el-form-item> -->
		<template #footer>
			<div class="dialog-footer">
				<el-button @click="dialogFormVisible">取 消</el-button>
				<el-button type="primary" @click="submitClick">确 定</el-button>
			</div>
		</template>
	</el-dialog>
</template>

<script>
	import Getpoint from '@/components/map/Getpoint.vue';
	export default {
		components: {
			Getpoint
		},
		data() {
			return {
				/*左边长度*/
				formLabelWidth: '120px',
				/*是否显示*/
				dialogVisible: false,
				loading: false,
				form: {
					coordinate: '',
					address: '',
				},
			};
		},
		props: ['open', 'mapData', 'tx_key'],
		watch: {
			open: function(n, o) {
				this.dialogVisible = this.open;
				if (this.mapData) {
					this.form = this.mapData;
				}
			}
		},
		created() {

		},
		methods: {
			/*选择的地址*/
			choseFunc(e) {
				this.form.coordinate = e.location.lat + ',' + e.location.lng;
				this.form.address = e.address;
			},
			submitClick() {
				this.dialogFormVisible(this.form);
			},
			/*关闭弹窗*/
			dialogFormVisible(e) {
				if (e) {
					this.$emit('close', {
						type: 'success',
						params: e
					});
					this.form.coordinate = "";
					this.form.address = "";
				} else {
					this.$emit('close', {
						type: 'error'
					});
				}
			}
		}
	};
</script>

<style></style>