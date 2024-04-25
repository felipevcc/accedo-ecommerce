<template>
	<h1>{{ product.name }}</h1>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
	props: ['product'],
	//components: {},
	setup({ product }) {
		return {}
	}
}
</script>
