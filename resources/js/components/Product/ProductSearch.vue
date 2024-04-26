<template>
	<div class="d-flex justify-content-between search">
		<div class="my-4 search__title me-4">
			<h1 class="fs-1">Resultados</h1>
		</div>
		<div class="d-flex row products">
			<product-card v-for="(product, index) in products" :key="index" :product="product" />
		</div>
	</div>
</template>

<script>
import ProductCard from './ProductCardHoriz.vue';

export default {
	props: ['products'],
	components: { ProductCard },
	setup() {
		return {}
	}
}
</script>
