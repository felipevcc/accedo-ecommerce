<template>
	<div class="d-flex justify-content-between category-products">
		<div class="my-4 category-products__title">
			<h1 class="fs-1">Productos en {{ category_data.name }}</h1>
		</div>
		<div class="d-flex flex-wrap" style="width: 80%; ">
			<product-card v-for="(product, index) in category_data.products" :key="index" :product="product" />
		</div>
	</div>
</template>

<script>
import ProductCard from '../Product/ProductCard.vue';

export default {
	props: ['category_data'],
	components: { ProductCard },
	setup({ category_data }) {
		return {}
	}
}
</script>
