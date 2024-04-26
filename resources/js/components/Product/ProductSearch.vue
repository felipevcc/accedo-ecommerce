<template>
	<div class="d-flex justify-content-between search">
		<div class="my-4 search__title">
			<h1 class="fs-1">Resultados</h1>
		</div>
		<div class="d-flex flex-wrap" style="width: 80%; ">
			<product-card v-for="(product, index) in products" :key="index" :product="product" />
		</div>
	</div>
</template>

<script>
import ProductCard from './ProductCard.vue';

export default {
	props: ['products'],
	components: { ProductCard },
	setup() {
		return {}
	}
}
</script>
