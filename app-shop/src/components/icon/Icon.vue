<template>

	<svg class="svg-icon" aria-hidden="true">
        <use :xlink:href="iconname"></use>
    </svg>

</template>

<script>

	export default {

		props:['iconname']

	}
</script>

<style lang="scss">


/*图标*/
.svg-icon {
    vertical-align: -0.15em;
    fill: currentColor;
    overflow: hidden;
    width: 30px;
    height: 30px;
    font-size: 30px;
    color: #cccccc;
}
.iconfont .svg-icon {
    width: 1em;
    vertical-align: -0.15em;
    fill: currentColor;
    overflow: hidden;
}

</style>
