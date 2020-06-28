<template>
  <view class="prompt-box" v-if="visible" @touchmove="true">
    <view class="prompt">
      <view class="prompt-top">
        <text class="prompt-title">{{title}}</text>
        <input class="prompt-input" type="text" :placeholder="placeholder" v-model="value">
      </view>
      <slot></slot>
      <view class="prompt-buttons" :style="'background:' + mainColor">
        <button class="prompt-cancle" :style="'color:' + mainColor" @click="close">取消</button>
        <button @click="$emit('confirm', value)">确定</button>
      </view>
    </view>
  </view>
</template>

<script>
export default {
  props: {
    visible: {
      type: Boolean,
      default: false,
      required: true,
    },
    title: {
      type: String,
      default: '提示',
    },
    placeholder: {
      type: String,
      default: '请输入内容',
    },
    mainColor: {
      type: String,
      default: '#e74a39',
    },
    defaultValue: {
      type: String,
    }
  },
  data() {
    return {
      value: '',
    }
  },
  mounted() {
    this.value = this.defaultValue
  },
  methods: {
    close() {
      this.$emit('update:visible', false)
    }
  }
}
</script>

<style scoped>
  view, button, input {
    box-sizing: border-box;
  }
  
  .prompt-box {
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, .2);
    transition: opacity .2s linear;
  }
  .prompt {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    width: 600upx;
    min-height: 300upx;
    background: white;
    border-radius: 20upx;
    overflow: hidden;
  }
  .prompt-top {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }
  .prompt-title {
    margin: 20upx 0;
    color: #333;
  }
  .prompt-input {
    width: 520upx;
		height: 80upx;
    font-size: 28upx;
    border-radius: 8upx;
		border: 1px solid #ccc;
		padding: 5upx 10upx;
  }
  .prompt-buttons {
    display: flex;
    width: 100%;
    box-shadow: 0 0 2upx 2upx #eee;
  }
  button {
    width: 50%;
    border-radius: 0;
  }
  .prompt-cancle {
    background: white;
  }
</style>
